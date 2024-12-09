<?php


/**
 * Team: LFZW,NKU
 * Coding by LiangXiaochu 2110951
 * 创建了这个主要用于根据api来调用不同的函数的控制器，从而读取特定的表来返回不同的值给前端，为其他组员提供模板
 * 增加了用于注册和登录的api
 * 修改了评论查找的api，能够查找特定视频的评论，以及能够查找特定用户的评论
 * 修改了视频查找的api，能够根据VideoID获取视频信息
 * 增加了获取网页点击量的api
 * 修改了获取和增加视频和文章点赞量的api，能够增加特定值
 * 
 * Coding by FangYi 2112106
 * 增加了视频和评论的api及查找函数actionGetvideo和actionGetcomment
 * 修改了视频和评论的查找api，增加了查询页数
 * 增加了点击量的api
 * 增加了管理员登录的api
 * 更新了管理员登录的api
 * 增加了获取视频和文章页面总数的api
 * 增加了获取和增加视频和文章点赞量的api
 * 
 * Coding by ZhangHaoxing 2113419
 * 增加了发布评论的api
 * 增加了点击量增加的api
 * 增加了获取个人信息的api
 * 增加浏览量的api
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Users;
use app\models\Articles;
use app\models\Videos;
use app\models\Videocomments;
use app\models\Articlecomments;
use app\models\Admins;
use app\models\Clicks;
use app\models\Personalinfo;
use app\models\Webviews;
use app\models\Videolikes;
use app\models\Articlelikes;
use WebSocket\Client;


class ApiController extends Controller
{   
    //用于登录的api
    public function actionLogin()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $rawBody = \Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);
        $username = isset($data['username']) ? $data['username'] : null;
        $password = isset($data['password']) ? $data['password'] : null;

        if ($username !== null && $password !== null) {
            // 查询数据库检查用户名和密码是否匹配
            $user = Users::find()
                ->where(['Username' => $username])
                ->one();

            if ($user !== null && ($password == $user->Password)) {
                // 用户名和密码匹配
                return ['status' => 1];
            } else {
                // 用户名和密码不匹配
                return ['status' => 0];
            }
        } else {
            return ['status' => 0, 'message' => '缺少用户名或密码'];
        }
    }

    //用于注册的api
    public function actionSignup()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $rawBody = \Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);
        $username = isset($data['username']) ? $data['username'] : null;
        $password = isset($data['password']) ? $data['password'] : null;

        if ($username !== null && $password !== null) {
            // 检查用户名是否已存在
            $existingUser = Users::find()
                ->where(['Username' => $username])
                ->one();

            if ($existingUser !== null) {
                return ['status' => 0, 'message' => '用户已存在'];
            }

            $user = new Users();
            $user->Username = $username;
            $user->Password = $password;
            if ($user->save()) {
                return ['status' => 1, 'message' => '注册成功'];
            } else {
                return ['status' => 0, 'message' => '注册失败'];
            }
        } else {
            return ['status' => 0, 'message' => '缺少用户名或密码'];
        }
    }

    //用于判断管理员登录的api
    public function actionAdminlogin()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        $username = \Yii::$app->request->get('username');
        $password = \Yii::$app->request->get('password');
    
        if ($username !== null && $password !== null) {
            // 查询数据库检查用户名和密码是否匹配
            $user = Users::find()
                ->where(['Username' => $username])
                ->one();
    
            if ($user !== null && ($password == $user->Password)) {
                // 用户名和密码匹配
                // 检查用户是否为管理员
                $admin = Admins::find()
                    ->where(['Username' => $user->Username])
                    ->one();
                if($admin !== null){
                    return ['status' => 1];
                }else{
                    return ['status' => 0];
                }
            } else {
                // 用户名和密码不匹配
                return ['status' => 0];
            }
        }
    }

    //用于文章页面获取文章的api
    public function actionGetarticle()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // 获取页数
        $page = \Yii::$app->request->get('page');
        $intpage = (int)$page;
        $id = \Yii::$app->request->get('id');
        if ($id !== null) {
            $articles = Articles::find()->select(['ArticleID', 'Title', 'Content', 'PublicationDate'])->where(['ArticleID' => $id])->one();
        }
        else{
            // 查询数据库获取对应页数的文章信息
            $articles = Articles::find()->select(['ArticleID', 'Title', 'Content', 'PublicationDate'])->offset(15*($intpage-1))->limit(15)->all();
        }
        // 格式化为 JSON 并返回
        return $articles;
    }

    //用于视频页面获取视频的api
    public function actionGetvideo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // 获取页数
        $page = \Yii::$app->request->get('page');
        $intpage = (int)$page;
        $id = \Yii::$app->request->get('id');

        if ($id !== null) {
            // 如果有 id 参数，则查询指定 VideoID 的视频信息
            $videos = Videos::find()->select(['VideoID', 'Title', 'Description', 'PictureURL', 'UploadDate', 'VideoURL'])->where(['VideoID' => $id])->one();
        } else {
            // 否则按照原来的逻辑查询分页数据
            $videos = Videos::find()->select(['VideoID', 'Title', 'Description', 'PictureURL', 'UploadDate', 'VideoURL'])->offset(18 * ($intpage - 1))->limit(18)->all();
        }

        // 格式化为 JSON 并返回
        return $videos;
    }

    //用于视频播放页获取评论的api
    public function actionGetvideocomment()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $vid = \Yii::$app->request->get('vid');
        $username = \Yii::$app->request->get('username');
        
        if ($username !== null) {
            // 如果有 username 参数，则查询指定 Username 的评论信息
            $comments = Videocomments::find()
                ->select(['CommentID', 'VideoID', 'Comment', 'CommentDate', 'Username'])
                ->where(['Username' => $username])
                ->all();
        } elseif ($vid !== null) {
            // 如果有 vid 参数，则查询指定 VideoID 的评论信息
            $comments = Videocomments::find()
                ->select(['CommentID', 'VideoID', 'Comment', 'CommentDate', 'Username'])
                ->where(['VideoID' => $vid])
                ->all();
        } else {
            // 否则按照原来的逻辑查询分页数据
            $comments = Videocomments::find()
                ->select(['CommentID', 'VideoID', 'Comment', 'CommentDate', 'Username'])
                ->all();
        }

        // 格式化为 JSON 并返回
        return $comments;
    }

    //用于文章详情页获取文章的api
    public function actionGetarticlecomment()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $vid = \Yii::$app->request->get('vid');
        $username = \Yii::$app->request->get('username');
        
        if ($username !== null) {
            // 如果有 username 参数，则查询指定 Username 的评论信息
            $comments = Articlecomments::find()
                ->select(['CommentID', 'ArticleID', 'Comment', 'CommentDate', 'Username'])
                ->where(['Username' => $username])
                ->all();
        } elseif ($vid !== null) {
            // 如果有 vid 参数，则查询指定 ArticleID 的评论信息
            $comments = Articlecomments::find()
                ->select(['CommentID', 'ArticleID', 'Comment', 'CommentDate', 'Username'])
                ->where(['ArticleID' => $vid])
                ->all();
        } else {
            // 否则按照原来的逻辑查询分页数据
            $comments = Articlecomments::find()
                ->select(['CommentID', 'ArticleID', 'Comment', 'CommentDate', 'Username'])
                ->all();
        }

        // 格式化为 JSON 并返回
        return $comments;
    }

    //查询点击量的api
    public function actionGetclick()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // 查询数据库获取点击量信息
        $clicks = Clicks::find()->select(['ClickID', 'ContentID', 'ContentType', 'ClickCount'])->all();

        // 格式化为 JSON 并返回
        return $clicks;
    }

    //在视频播放页添加评论的api
    public function actionAddvideocomment()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $username = \Yii::$app->request->get('username');
        $comment = \Yii::$app->request->get('comment');
        $videoID = \Yii::$app->request->get('videoID');

        $comments = new Videocomments();
        $comments->Username = $username;
        $comments->Comment = $comment;
        $comments->VideoID = $videoID;

        if ($comments->save()) {
            return ['status' => 1, 'message' => '发布评论成功'];
        } else {
            return ['status' => -1, 'message' => '发布评论失败'];
        }
    }

    //在文章详情页添加评论的api
    public function actionAddarticlecomment()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $username = \Yii::$app->request->get('username');
        $comment = \Yii::$app->request->get('comment');
        $articleID = \Yii::$app->request->get('articleID');

        $comments = new Articlecomments();
        $comments->Username = $username;
        $comments->Comment = $comment;
        $comments->ArticleID = $articleID;

        if ($comments->save()) {
            return ['status' => 1, 'message' => '发布评论成功'];
        } else {
            return ['status' => -1, 'message' => '发布评论失败'];
        }
    }

    //在视频播放页或文章详情页增加点击量的api
    public function actionAddclick()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $contenttype = \Yii::$app->request->get('contenttype');
        $contentID = \Yii::$app->request->get('contentID');

        $click = Clicks::find()
                ->where(['ContentType' => $contenttype])
                ->andWhere(['ContentID' => $contentID])
                ->one();

        if($click == null){
            $click = new Clicks();
            $click->ContentType = $contenttype;
            $click->ContentID = $contentID;
            $click->ClickCount = 0;
        }
        $click->ClickCount = $click->ClickCount + 1;

        if ($click->save()) {
            return ['status' => 1, 'message' => '点击量增加成功'];
        } else {
            return ['status' => -1, 'message' => '点击量增加失败'];
        }
    }

    //关于页面获取小组成员信息的api
    public function actionGetpersonalinfo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $name = \Yii::$app->request->get('name');
        if ($name !== null) {
        // 查询数据库获取个人信息信息
        $personalinfo = Personalinfo::find()->select(['Name', 'Info', 'AvatarURL', 'Email', 'GitHubAccount', 'WeChatID'])->where(['Name' => $name])->one();
        }
        else{
            $personalinfo = Personalinfo::find()->select(['Name', 'Info', 'AvatarURL', 'Email', 'GitHubAccount', 'WeChatID'])->all();
        }
        // 格式化为 JSON 并返回
        return $personalinfo;
    }

    //用于添加网站点击量的api
    public function actionAddwebviews()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $webviews = Webviews::find()->one();
        $webviews->Views = $webviews->Views + 1;

        if ($webviews->save()) {
            return ['status' => 1, 'message' => '浏览量增加成功'];
        } else {
            return ['status' => -1, 'message' => '浏览量增加失败'];
        }
    }

    //用于查询网站点击量的api
    public function actionCheckwebviews()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $webviews = Webviews::find()->one();

        return $webviews;
    }

    //用于获取视频总页数的api
    public function actionGetvideopagecount()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $count = Videos::find()->count();
        $pagecount = intval($count / 18);

        if($count % 18 !== 0){
            $pagecount = $pagecount + 1;
        }

        $pagecount=json_encode($pagecount);
        return $pagecount;
    }

    //用于获取文章总页数的api
    public function actionGetarticlepagecount()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $count = Articles::find()->count();
        $pagecount = intval($count / 15);

        if($count % 15 !== 0){
            $pagecount = $pagecount + 1;
        }

        $pagecount=json_encode($pagecount);
        return $pagecount;
    }

    //用于视频播放页获取视频点赞数的api
    public function actionGetvideolikes()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $videoID = \Yii::$app->request->get('videoID');
        
        $likes = Videolikes::find()
            ->where(['VideoID' => $videoID])
            ->one();
        
        if($likes == null){
            $likes = new Videolikes();
            $likes->VideoID = $videoID;
            $likes->Likes = 0;
            $likes->save();
        }

        $likesnum = $likes->Likes;
        $likesnum=json_encode($likesnum);
        // 格式化为 JSON 并返回
        return $likesnum;
    }

    //用于文章详情页获取文章点赞数的api
    public function actionGetarticlelikes()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $articleID = \Yii::$app->request->get('articleID');
        
        $likes = Articlelikes::find()
            ->where(['ArticleID' => $articleID])
            ->one();
        
        if($likes == null){
            $likes = new Articlelikes();
            $likes->ArticleID = $articleID;
            $likes->Likes = 0;
            $likes->save();
        }

        $likesnum = $likes->Likes;
        $likesnum=json_encode($likesnum);
        // 格式化为 JSON 并返回
        return $likesnum;
    }

    //在视频播放页增加点赞量的api
    public function actionAddvideolikes()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $videoID = \Yii::$app->request->get('videoID');
        $num = \Yii::$app->request->get('num');
        
        $likes = Videolikes::find()
            ->where(['VideoID' => $videoID])
            ->one();
        
        if($likes == null){
            $likes = new Videolikes();
            $likes->VideoID = $videoID;
            $likes->Likes = 0;
            $likes->save();
        }

        $likes->Likes = $likes->Likes + $num;
        if ($likes->save()) {
            return ['status' => 1, 'message' => '点赞量增加成功'];
        } else {
            return ['status' => -1, 'message' => '点赞量增加失败'];
        }
    }

    //在文章详情页增加点赞量的api
    public function actionAddarticlelikes()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $articleID = \Yii::$app->request->get('articleID');
        $num = \Yii::$app->request->get('num');
        
        $likes = Articlelikes::find()
            ->where(['ArticleID' => $articleID])
            ->one();
        
        if($likes == null){
            $likes = new Articlelikes();
            $likes->ArticleID = $articleID;
            $likes->Likes = 0;
            $likes->save();
        }

        $likes->Likes = $likes->Likes + $num;
        if ($likes->save()) {
            return ['status' => 1, 'message' => '点赞量增加成功'];
        } else {
            return ['status' => -1, 'message' => '点赞量增加失败'];
        }
    }

    public function actionChatbot()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        $rawBody = \Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);
        $userMessage = isset($data['message']) ? $data['message'] : null;
    
        if ($userMessage !== null) {
            // $botReply = "You said: " . $userMessage;
    
            // if (strpos($userMessage, 'hello') !== false) {
            //     // $botReply = 'Hi there! How can I help you today?';
            // }

            $botReply = $this->callXfYunModel($userMessage);
    
            return ['status' => 1, 'reply' => $botReply];
        } 
        else {
            return ['status' => 0, 'message' => 'No message received'];
        }
    }   
    
    private $Appid = "d900ec04";
    private $Apikey = "a0cfd3a9e05b9d8aaa9cad33112e047a";
    private $ApiSecret = "ZjE5NmJlNjQwOTg3YzA2ZGZiNzAxYWVh";
    private $Addr = "wss://spark-api.xf-yun.com/v3.1/chat";

    /**
     * 使用 iFlytek 的 WebSocket 接口调用大模型
     * @param string $userMessage 用户输入的消息
     * @return string 机器人回复
     */
    private function callXfYunModel($userMessage)
    {
        // 组装鉴权URL
        $authUrl = $this->assembleAuthUrl("GET", $this->Addr, $this->Apikey, $this->ApiSecret);

        // 创建WebSocket客户端
        $client = new Client($authUrl);

        $answer = "";
        if ($client) {
            // 构造要发送的数据
            $data = $this->getBody($this->Appid, $userMessage);
            $client->send($data);

            // 循环接收服务器返回的数据，直到 status == 2 表示结束
            while (true) {
                $response = $client->receive();
                $resp = json_decode($response, true);
                $code = $resp["header"]["code"];

                if (0 == $code) {
                    $status = $resp["header"]["status"];
                    $content = $resp['payload']['choices']['text'][0]['content'];

                    $answer .= $content;

                    if ($status == 2) {
                        // 会话结束
                        break;
                    }
                } else {
                    // 服务返回报错
                    $answer = "Service error: " . $response;
                    break;
                }
            }
        } else {
            $answer = "Failed to connect to the WebSocket server.";
        }

        return $answer;
    }

    /**
     * 构造请求体
     */
    private function getBody($appid, $question)
    {
        $header = array(
            "app_id" => $appid,
            "uid" => "12345"
        );

        $parameter = array(
            "chat" => array(
                "domain" => "generalv3",
                "temperature" => 0.5,
                "max_tokens" => 1024
            )
        );

        $payload = array(
            "message" => array(
                "text" => array(
                    array("role" => "user", "content" => $question)
                )
            )
        );

        $json_string = json_encode(array(
            "header" => $header,
            "parameter" => $parameter,
            "payload" => $payload
        ));

        return $json_string;
    }

    /**
     * 组装鉴权 URL
     */
    private function assembleAuthUrl($method, $addr, $apiKey, $apiSecret)
    {
        if ($apiKey == "" && $apiSecret == "") {
            return $addr;
        }

        $ul = parse_url($addr);
        if ($ul === false) {
            return $addr;
        }

        $timestamp = time();
        $rfc1123_format = gmdate("D, d M Y H:i:s \G\M\T", $timestamp);

        $signString = array(
            "host: " . $ul["host"],
            "date: " . $rfc1123_format,
            $method . " " . $ul["path"] . " HTTP/1.1"
        );

        $sgin = implode("\n", $signString);
        $sha = hash_hmac('sha256', $sgin, $apiSecret, true);
        $signature_sha_base64 = base64_encode($sha);

        $authUrl = "api_key=\"$apiKey\", algorithm=\"hmac-sha256\", headers=\"host date request-line\", signature=\"$signature_sha_base64\"";

        $authAddr = $addr . '?' . http_build_query(array(
            'host' => $ul['host'],
            'date' => $rfc1123_format,
            'authorization' => base64_encode($authUrl),
        ));

        return $authAddr;
    }
}
