<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

use app\models\Users;
use app\models\UsersSearch;
use app\models\Conversations;
use app\models\ConversationsSearch;
use app\models\Messages;
use app\models\MessagesSearch;
use app\models\Articles;
use app\models\ArticlesSearch;
use app\models\Videos;
use app\models\VideosSearch;
use app\models\WebsiteVisits;
use app\models\WebsiteVisitsSearch;
use app\models\ArticleComments;
use app\models\ArticleCommentsSearch;
use app\models\VideoComments;
use app\models\VideoCommentsSearch;
use app\models\ArticleLikes;
use app\models\ArticleLikesSearch;
use app\models\VideoLikes;
use app\models\VideoLikesSearch;

use WebSocket\Client;


class ApiController extends Controller
{   
    private $Appid = "d900ec04";
    private $Apikey = "a0cfd3a9e05b9d8aaa9cad33112e047a";
    private $ApiSecret = "ZjE5NmJlNjQwOTg3YzA2ZGZiNzAxYWVh";
    private $Addr = "wss://spark-api.xf-yun.com/v3.1/chat";

    public function actionAddwebviews()
    {
        $searchModel = new WebsiteVisitsSearch();
        if ($searchModel->incrementVisitCount()) {
            return $this->asJson(['status' => 1]);
        } 
        else {
            return $this->asJson(['status' => 0]);
        }
    }

    public function actionGetwebviews()
    {
        $searchModel = new WebsiteVisitsSearch();
        $visitCount = $searchModel->getVisitCount();
        return $this->asJson(['status' => 1, 'visitCount' => $visitCount]);
    }

    public function actionLogin()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $rawBody = \Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);
        $username = isset($data['username']) ? $data['username'] : null;
        $password = isset($data['password']) ? $data['password'] : null;

        if ($username === null || $password === null) {
            return [
                'status' => 0,
                'message' => 'Username and password are required.',
            ];
        }

        $user = Users::findOne(['Username' => $username]);

        if ($user && Yii::$app->getSecurity()->validatePassword($password, $user->Password)) {
            return [
                'status' => 1,
                'message' => 'Login successful.',
                'user' => [
                    'UserID' => $user->UserID,
                    'Username' => $user->Username,
                    'Role' => $user->Role,
                ],
            ];
        } 
        else {
            return [
                'status' => 0,
                'message' => 'Invalid username or password.',
            ];
        }
    }

    public function actionSignup()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $rawBody = \Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);
        $username = isset($data['username']) ? $data['username'] : null;
        $password = isset($data['password']) ? $data['password'] : null;

        if ($username === null || $password === null) {
            return [
                'status' => 0,
                'message' => 'Username and password are required.',
            ];
        }

        if (Users::find()->where(['Username' => $username])->exists()) {
            return [
                'status' => 0,
                'message' => 'Username already exists.',
            ];
        }

        $user = new Users();
        $user->Username = $username;
        $user->Password = Yii::$app->getSecurity()->generatePasswordHash($password);

        if ($user->save()) {
            return [
                'status' => 1,
                'message' => 'Signup successful.',
                'user' => [
                    'UserID' => $user->UserID,
                    'Username' => $user->Username,
                    'Role' => $user->Role,
                ],
            ];
        } 
        else {
            return [
                'status' => 0,
                'message' => 'Signup failed.',
                'errors' => $user->errors,
            ];
        }
    }

    public function actionGetuser()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $userId = \Yii::$app->request->get('userId');

        if ($userId === null) {
            return [
                'status' => 0,
                'message' => 'User ID is required.',
            ];
        }

        $user = Users::findOne($userId);

        if ($user !== null) {
            return [
                'status' => 1,
                'user' => [
                    'UserID' => $user->UserID,
                    'Username' => $user->Username,
                    'Role' => $user->Role,
                ],
            ];
        } 
        else {
            return [
                'status' => 0,
                'message' => 'User not found.',
            ];
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

    public function actionGetarticle()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // 获取页数
        $page = \Yii::$app->request->get('page');
        // 获取文章 ID
        $id = \Yii::$app->request->get('id');

        $searchModel = new ArticlesSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        if ($id !== null) {
            $articles = Articles::find()->select(['ArticleID', 'Title', 'Content', 'PublicationDate'])->where(['ArticleID' => $id])->one();
        } 
        else {
            $dataProvider->pagination->pageSize = 15;
            $dataProvider->pagination->page = $page - 1;
            $articles = $dataProvider->getModels();
        }

        return $articles;
    }

    public function actionViewarticle()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = \Yii::$app->request->get('id');

        $article = Articles::find()->where(['ArticleID' => $id])->one();
        if ($article !== null) {
            $article->ViewCount = $article->ViewCount + 1;
            $article->save();

            return [
                'ArticleID' => $article->ArticleID,
                'Title' => $article->Title,
                'Content' => $article->Content,
                'PublicationDate' => $article->PublishedAt,
                'ViewCount' => $article->ViewCount,
            ];
        } 
        else {
            return [
                'status' => 0,
                'message' => 'Article not found.',
            ];
        }
    }

    public function actionAddarticle()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $rawBody = \Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);
        $title = isset($data['title']) ? $data['title'] : null;
        $content = isset($data['content']) ? $data['content'] : null;
        $userId = isset($data['userId']) ? $data['userId'] : null;

        if ($title === null || $content === null || $userId === null) {
            return [
                'status' => 0,
                'message' => 'Title, content, and user ID are required.',
            ];
        }

        $article = new Articles();
        $article->Title = $title;
        $article->Content = $content;
        $article->PublishedAt = date('Y-m-d H:i:s');
        $article->UserID = $userId;

        if ($article->save()) {
            return [
                'status' => 1,
                'message' => 'Article added successfully.',
                'article' => [
                    'ArticleID' => $article->ArticleID,
                    'Title' => $article->Title,
                    'Content' => $article->Content,
                    'PublicationDate' => $article->PublishedAt,
                ],
            ];
        } 
        else {
            return [
                'status' => 0,
                'message' => 'Failed to add article.',
                'errors' => $article->errors,
            ];
        }
    }

    public function actionDeletearticle()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $articleId = \Yii::$app->request->post('articleId');

        if ($articleId === null) {
            return [
                'status' => 0,
                'message' => 'Article ID is required.',
            ];
        }

        $article = Articles::findOne($articleId);

        if ($article === null) {
            return [
                'status' => 0,
                'message' => 'Article not found.',
            ];
        }

        if ($article->delete()) {
            return [
                'status' => 1,
                'message' => 'Article deleted successfully.',
            ];
        } 
        else {
            return [
                'status' => 0,
                'message' => 'Failed to delete article.',
            ];
        }
    }

    public function actionLikearticle()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $userId = \Yii::$app->request->post('userId');
        $articleId = \Yii::$app->request->post('articleId');

        if ($userId === null || $articleId === null) {
            return [
                'status' => 0,
                'message' => 'User ID and Article ID are required.',
            ];
        }

        // 查找是否已经存在点赞记录
        $like = ArticleLikes::find()->where(['UserID' => $userId, 'ArticleID' => $articleId])->one();

        if ($like !== null) {
            // 如果存在点赞记录，则删除点赞
            if ($like->delete()) {
                return [
                    'status' => 1,
                    'message' => 'Like removed.',
                ];
            } 
            else {
                return [
                    'status' => 0,
                    'message' => 'Failed to remove like.',
                ];
            }
        } 
        else {
            // 如果不存在点赞记录，则添加点赞
            $like = new ArticleLikes();
            $like->UserID = $userId;
            $like->ArticleID = $articleId;
            $like->LikedAt = date('Y-m-d H:i:s');

            if ($like->save()) {
                return [
                    'status' => 1,
                    'message' => 'Article liked.',
                ];
            } 
            else {
                return [
                    'status' => 0,
                    'message' => 'Failed to like article.',
                    'errors' => $like->errors,
                ];
            }
        }
    }

    public function actionGetlikearticle()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $userId = \Yii::$app->request->get('userId');
        $articleId = \Yii::$app->request->get('articleId');

        if ($userId === null || $articleId === null) {
            return [
                'status' => 0,
                'message' => 'User ID and Article ID are required.',
            ];
        }

        // 查找是否已经存在点赞记录
        $like = ArticleLikes::find()->where(['UserID' => $userId, 'ArticleID' => $articleId])->one();

        if ($like !== null) {
            return [
                'status' => 1,
                'liked' => true,
            ];
        } else {
            return [
                'status' => 1,
                'liked' => false,
            ];
        }
    }

    public function actionCommentarticle()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $userId = \Yii::$app->request->post('userId');
        $articleId = \Yii::$app->request->post('articleId');
        $content = \Yii::$app->request->post('content');

        if ($userId === null || $articleId === null || $content === null) {
            return [
                'status' => 0,
                'message' => 'User ID, Article ID, and Content are required.',
            ];
        }

        $comment = new ArticleComments();
        $comment->UserID = $userId;
        $comment->ArticleID = $articleId;
        $comment->Content = $content;
        $comment->CommentedAt = date('Y-m-d H:i:s');

        if ($comment->save()) {
            return [
                'status' => 1,
                'message' => 'Comment added successfully.',
                'comment' => [
                    'CommentID' => $comment->CommentID,
                    'UserID' => $comment->UserID,
                    'ArticleID' => $comment->ArticleID,
                    'Content' => $comment->Content,
                    'CommentedAt' => $comment->CommentedAt,
                ],
            ];
        } else {
            return [
                'status' => 0,
                'message' => 'Failed to add comment.',
                'errors' => $comment->errors,
            ];
        }
    }

    public function actionShowcommentarticle()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $articleId = \Yii::$app->request->get('articleId');

        if ($articleId === null) {
            return [
                'status' => 0,
                'message' => 'Article ID is required.',
            ];
        }

        $comments = ArticleComments::find()
            ->where(['ArticleID' => $articleId])
            ->with('user') // 预加载用户数据
            ->all();

        $commentData = [];
        foreach ($comments as $comment) {
            $commentData[] = [
                'CommentID' => $comment->CommentID,
                'Username' => $comment->user->Username, // 获取用户名
                'Content' => $comment->Content,
                'CommentedAt' => $comment->CommentedAt,
            ];
        }

        return [
            'status' => 1,
            'comments' => $commentData,
        ];
    }

    public function actionDeletecommentarticle()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        $commentId = \Yii::$app->request->post('commentId');
    
        if ($commentId === null) {
            return [
                'status' => 0,
                'message' => 'Comment ID is required.',
            ];
        }
    
        $comment = ArticleComments::findOne($commentId);
    
        if ($comment === null) {
            return [
                'status' => 0,
                'message' => 'Comment not found.',
            ];
        }
    
        if ($comment->delete()) {
            return [
                'status' => 1,
                'message' => 'Comment deleted successfully.',
            ];
        } else {
            return [
                'status' => 0,
                'message' => 'Failed to delete comment.',
            ];
        }
    }

    public function actionGetvideo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        // 获取页数
        $page = \Yii::$app->request->get('page');
        // 获取视频 ID
        $id = \Yii::$app->request->get('id');
    
        $searchModel = new VideosSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
    
        if ($id !== null) {
            $video = Videos::find()->where(['VideoID' => $id])->one();
            if ($video !== null) {
                // 增加访问量
                $video->ViewCount = $video->ViewCount + 1;
                $video->save();

                $videoUrl = \Yii::$app->urlManager->createAbsoluteUrl(['src/videos/' . $video->URL]);
    
                return [
                    'VideoID' => $video->VideoID,
                    'Title' => $video->Title,
                    'URL' => $videoUrl,
                    'UserID' => $video->UserID,
                    'UploadedAt' => $video->UploadedAt,
                    'UpdatedAt' => $video->UpdatedAt,
                    'ViewCount' => $video->ViewCount,
                    'LikeCount' => $video->LikeCount,
                ];
            } 
            else {
                return [
                    'status' => 0,
                    'message' => 'Video not found.',
                ];
            }
        } 
        else {
            $dataProvider->pagination->pageSize = 12;
            $dataProvider->pagination->page = $page - 1;
            $videos = $dataProvider->getModels();
    
            return $videos;
        }
    }

    public function actionAddvideo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        $title = \Yii::$app->request->post('title');
        $url = \Yii::$app->request->post('url');
        $userId = \Yii::$app->request->post('userId');
        $pictureUrl = \Yii::$app->request->post('pictureUrl');
    
        if ($title === null || $url === null || $userId === null) {
            return [
                'status' => 0,
                'message' => 'Title, URL, and User ID are required.',
            ];
        }
    
        $video = new Videos();
        $video->Title = $title;
        $video->URL = $url;
        $video->UserID = $userId;
        $video->PictureURL = $pictureUrl;
        $video->UploadedAt = date('Y-m-d H:i:s');
    
        if ($video->save()) {
            return [
                'status' => 1,
                'message' => 'Video added successfully.',
                'video' => [
                    'VideoID' => $video->VideoID,
                    'Title' => $video->Title,
                    'URL' => $video->URL,
                    'UserID' => $video->UserID,
                    'UploadedAt' => $video->UploadedAt,
                    'UpdatedAt' => $video->UpdatedAt,
                    'ViewCount' => $video->ViewCount,
                    'LikeCount' => $video->LikeCount,
                    'PictureURL' => $video->PictureURL,
                ],
            ];
        } else {
            return [
                'status' => 0,
                'message' => 'Failed to add video.',
                'errors' => $video->errors,
            ];
        }
    }

    public function actionDeletevideo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $videoId = \Yii::$app->request->post('videoId');

        if ($videoId === null) {
            return [
                'status' => 0,
                'message' => 'Video ID is required.',
            ];
        }

        $video = Videos::findOne($videoId);

        if ($video === null) {
            return [
                'status' => 0,
                'message' => 'Video not found.',
            ];
        }

        if ($video->delete()) {
            return [
                'status' => 1,
                'message' => 'Video deleted successfully.',
            ];
        } else {
            return [
                'status' => 0,
                'message' => 'Failed to delete video.',
            ];
        }
    }

    public function actionViewvideo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = \Yii::$app->request->get('id');

        $video = Videos::find()->where(['VideoID' => $id])->one();
        if ($video !== null) {
            $video->ViewCount = $video->ViewCount + 1;
            $video->save();

            return [
                'VideoID' => $video->VideoID,
                'Title' => $video->Title,
                'URL' => $video->URL,
                'UserID' => $video->UserID,
                'UploadedAt' => $video->UploadedAt,
                'UpdatedAt' => $video->UpdatedAt,
                'ViewCount' => $video->ViewCount,
                'LikeCount' => $video->LikeCount,
            ];
        } else {
            return [
                'status' => 0,
                'message' => 'Video not found.',
            ];
        }
    }

    public function actionLikevideo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $userId = \Yii::$app->request->post('userId');
        $videoId = \Yii::$app->request->post('videoId');

        if ($userId === null || $videoId === null) {
            return [
                'status' => 0,
                'message' => 'User ID and Video ID are required.',
            ];
        }

        // 查找是否已经存在点赞记录
        $like = VideoLikes::find()->where(['UserID' => $userId, 'VideoID' => $videoId])->one();

        if ($like !== null) {
            // 如果存在点赞记录，则删除点赞
            if ($like->delete()) {
                return [
                    'status' => 1,
                    'message' => 'Like removed.',
                ];
            } else {
                return [
                    'status' => 0,
                    'message' => 'Failed to remove like.',
                ];
            }
        } else {
            // 如果不存在点赞记录，则添加点赞
            $like = new VideoLikes();
            $like->UserID = $userId;
            $like->VideoID = $videoId;
            $like->LikedAt = date('Y-m-d H:i:s');

            if ($like->save()) {
                return [
                    'status' => 1,
                    'message' => 'Video liked.',
                ];
            } else {
                return [
                    'status' => 0,
                    'message' => 'Failed to like video.',
                    'errors' => $like->errors,
                ];
            }
        }
    }

    public function actionGetlikevideo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $userId = \Yii::$app->request->get('userId');
        $videoId = \Yii::$app->request->get('videoId');

        if ($userId === null || $videoId === null) {
            return [
                'status' => 0,
                'message' => 'User ID and Video ID are required.',
            ];
        }

        // 查找是否已经存在点赞记录
        $like = VideoLikes::find()->where(['UserID' => $userId, 'VideoID' => $videoId])->one();

        if ($like !== null) {
            return [
                'status' => 1,
                'liked' => true,
            ];
        } else {
            return [
                'status' => 1,
                'liked' => false,
            ];
        }
    }

    public function actionCommentvideo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $userId = \Yii::$app->request->post('userId');
        $videoId = \Yii::$app->request->post('videoId');
        $content = \Yii::$app->request->post('content');

        if ($userId === null || $videoId === null || $content === null) {
            return [
                'status' => 0,
                'message' => 'User ID, Video ID, and Content are required.',
            ];
        }

        $comment = new VideoComments();
        $comment->UserID = $userId;
        $comment->VideoID = $videoId;
        $comment->Content = $content;
        $comment->CommentedAt = date('Y-m-d H:i:s');

        if ($comment->save()) {
            return [
                'status' => 1,
                'message' => 'Comment added successfully.',
                'comment' => [
                    'CommentID' => $comment->CommentID,
                    'UserID' => $comment->UserID,
                    'VideoID' => $comment->VideoID,
                    'Content' => $comment->Content,
                    'CommentedAt' => $comment->CommentedAt,
                ],
            ];
        } else {
            return [
                'status' => 0,
                'message' => 'Failed to add comment.',
                'errors' => $comment->errors,
            ];
        }
    }

    public function actionShowcommentvideo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $videoId = \Yii::$app->request->get('videoId');

        if ($videoId === null) {
            return [
                'status' => 0,
                'message' => 'Video ID is required.',
            ];
        }

        $comments = VideoComments::find()
            ->where(['VideoID' => $videoId])
            ->with('user') // 预加载用户数据
            ->all();

        $commentData = [];
        foreach ($comments as $comment) {
            $commentData[] = [
                'CommentID' => $comment->CommentID,
                'Username' => $comment->user->Username, // 获取用户名
                'Content' => $comment->Content,
                'CommentedAt' => $comment->CommentedAt,
            ];
        }

        return [
            'status' => 1,
            'comments' => $commentData,
        ];
    }

    public function actionDdeletecommentvideo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $commentId = \Yii::$app->request->post('commentId');

        if ($commentId === null) {
            return [
                'status' => 0,
                'message' => 'Comment ID is required.',
            ];
        }

        $comment = VideoComments::findOne($commentId);

        if ($comment === null) {
            return [
                'status' => 0,
                'message' => 'Comment not found.',
            ];
        }

        if ($comment->delete()) {
            return [
                'status' => 1,
                'message' => 'Comment deleted successfully.',
            ];
        } else {
            return [
                'status' => 0,
                'message' => 'Failed to delete comment.',
            ];
        }
    }

    public function actionGetvideopagecount()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $count = Videos::find()->count();
        $pagecount = intval($count / 12);

        if($count % 12 != 0) {
            $pagecount += 1;
        }

        return [
            'status' => 1,
            'pagecount' => $pagecount,
        ];
    }

}
