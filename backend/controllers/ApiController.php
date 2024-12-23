<?php

/**
 * Coding by JiangYu 2210705
 * 实现接口功能
 * 包括：登录、注册、聊天机器人、获取聊天记录、
 * 获取文章、添加文章、删除文章、增加文章访问量、喜欢文章、获取文章喜欢数、获取是否喜欢文章、评论文章、显示文章评论、删除文章评论、获取文章页数、获取文章总数、
 * 获取视频、添加视频、删除视频、增加视频访问量、喜欢视频、获取视频喜欢数、获取是否喜欢视频、评论视频、显示视频评论、删除视频评论、获取视频页数、获取视频总数
 */

/**
 * Coding by TangMinghao 2113927
 * 获取用户信息、更新头像
 * 获取学生、获取所有学生
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

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
use app\models\Students;

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
            $avatar = \Yii::$app->urlManager->createAbsoluteUrl(['src/avatar/' . $user->Avatar]);
            return [
                'status' => 1,
                'user' => [
                    'userID' => $user->UserID,
                    'username' => $user->Username,
                    'role' => $user->Role,
                    'avatar' => $avatar,
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

    public function actionUpdateavatar()
    {
        $request = Yii::$app->request;
        $userId = $request->get('userId'); // 从GET请求中获取用户ID
    
        if ($userId === null) {
            return $this->asJson(['success' => false, 'message' => '用户ID未提供。']);
        }
    
        $uploadedFile = UploadedFile::getInstanceByName('avatar');
        if ($uploadedFile) {
            $allowedExtensions = ['jpg', 'png', 'gif', 'jpeg'];
            if (!in_array(strtolower($uploadedFile->extension), $allowedExtensions)) {
                return $this->asJson(['success' => false, 'message' => '无效的文件类型。']);
            }
    
            $avatarDir = Yii::getAlias('@webroot/src/avatar/');
            if (!is_dir($avatarDir)) {
                mkdir($avatarDir, 0755, true);
            }
    
            $fileName = uniqid() . '.' . $uploadedFile->extension;
            $filePath = 'src/avatar/' . $fileName;
            $fullPath = Yii::getAlias('@webroot/') . $filePath;
    
            if ($uploadedFile->saveAs($fullPath)) {
                $user = Users::findOne($userId);
                if ($user === null) {
                    return $this->asJson(['success' => false, 'message' => '用户未找到。']);
                }
    
                $user->Avatar = $fileName;
                if ($user->save()) {
                    $avatarUrl = Yii::$app->urlManager->createAbsoluteUrl(['src/avatar/' . $user->Avatar]);
                    return $this->asJson([
                        'success' => true,
                        'message' => '头像更新成功。',
                        'url' => $avatarUrl
                    ]);
                } else {
                    return $this->asJson(['success' => false, 'message' => '更新头像时发生错误。']);
                }
            } else {
                return $this->asJson(['success' => false, 'message' => '保存头像文件失败。']);
            }
        }
        return $this->asJson(['success' => false, 'message' => '未收到头像文件。']);
    }

    public function actionChatbot()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $rawBody = \Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);
        $userMessage = isset($data['message']) ? $data['message'] : null;
        $userID = isset($data['UserID']) ? $data['UserID'] : null;

        if (!$userMessage || !$userID) {
            return ['status' => 0, 'message' => '缺少必要参数'];
        }

        // 获取最新对话ID
        $latestConversation = Conversations::find()
            ->where(['UserID' => $userID])
            ->orderBy(['ConversationID' => SORT_DESC])
            ->one();

        if (!$latestConversation) {
            return ['status' => 0, 'message' => '未找到有效对话'];
        }

        // 保存用户消息
        $userMsg = new Messages();
        $userMsg->ConversationID = $latestConversation->ConversationID;
        $userMsg->Sender = 'user';
        $userMsg->Content = $userMessage;
        $userMsg->Timestamp = date('Y-m-d H:i:s');

        if (!$userMsg->save()) {
            return ['status' => 0, 'message' => '保存用户消息失败'];
        }

        // 获取AI回复
        $botReply = $this->callXfYunModel($userMessage);

        // 保存AI回复
        $aiMsg = new Messages();
        $aiMsg->ConversationID = $latestConversation->ConversationID;
        $aiMsg->Sender = 'model';
        $aiMsg->Content = $botReply;
        $aiMsg->Timestamp = date('Y-m-d H:i:s');

        if (!$aiMsg->save()) {
            return ['status' => 0, 'message' => '保存AI回复失败'];
        }

        return ['status' => 1, 'reply' => $botReply];
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

    public function actionAddonversation()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $userID = \Yii::$app->request->get('UserID');

        // 1. 如果该用户已存在对话，则查找最近一次对话并设置结束时间
        $lastConversation = Conversations::find()
            ->where(['UserID' => $userID])
            ->orderBy(['ConversationID' => SORT_DESC])
            ->one();

        if ($lastConversation) {
            $lastConversation->EndedAt = date('Y-m-d H:i:s');
            $lastConversation->Status = 'ended';
            $lastConversation->save(false); // 不需要再验证，直接保存
        }

        // 2. 创建新的对话
        $model = new Conversations();
        $model->UserID = $userID;
        $model->StartedAt = date('Y-m-d H:i:s');
        $model->Status = 'active';

        if ($model->save()) {
            return [
                'status' => 1,
                'message' => 'Conversation created successfully',
                'conversation' => $model
            ];
        } else {
            return [
                'status' => 0,
                'message' => 'Failed to create conversation',
                'errors' => $model->errors
            ];
        }
    }

    public function actionGetarticle()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // 获取页数
        $page = \Yii::$app->request->get('page');
        // 获取文章 IDGetarticle
        $id = \Yii::$app->request->get('id');

        $searchModel = new ArticlesSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        if ($id !== null) {
            $articles = Articles::find()->where(['ArticleID' => $id])->one();
            if($articles !== null) {
                // $articles->ViewCount = $articles->ViewCount + 1;
                $articles->save();

                return [
                    'ArticleID' => $articles->ArticleID,
                    'Title' => $articles->Title,
                    'Content' => $articles->Content,
                    'PublicationDate' => $articles->PublishedAt,
                    'ViewCount' => $articles->ViewCount,
                ];
            } 
            else {
                return [
                    'status' => 0,
                    'message' => 'Article not found.',
                ];
            }
        } 
        else {
            $dataProvider->pagination->pageSize = 8;
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
    
        $title = \Yii::$app->request->get('title');
        $content = \Yii::$app->request->get('content');
        $userId = \Yii::$app->request->get('userId');
    
        if ($title === null) {
            return [
                'status' => 0,
                'message' => 'Title is required.',
            ];
        }
    
        if ($content === null) {
            return [
                'status' => 0,
                'message' => 'Content is required.',
            ];
        }
    
        if ($userId === null) {
            return [
                'status' => 0,
                'message' => 'User ID is required.',
            ];
        }

        $user = Users::findOne($userId);
        if ($user === null) {
            return [
                'status' => 0,
                'message' => 'Invalid User ID.',
            ];
        }

        $article = new Articles();
        $article->Title = $title;
        $article->Content = $content;
        $article->PublishedAt = date('Y-m-d H:i:s');
        $article->AuthorID = $userId;
    
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

    public function actionGetarticlepagecount()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $searchModel = new ArticlesSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 8;
        $pageCount = $dataProvider->pagination->pageCount;

        return [
            'status' => 1,
            'pageCount' => $pageCount,
        ];
    }

    public function actionGetarticletotal()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $searchModel = new ArticlesSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        $totalCount = $dataProvider->getTotalCount();

        return [
            'status' => 1,
            'count' => $totalCount,
        ];
    }

    public function actionLikearticle()
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

    public function actionLikenumarticle()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // 获取文章 ID
        $articleId = \Yii::$app->request->get('articleId');

        if ($articleId === null) {
            return [
                'status' => 0,
                'message' => 'Article ID is required.',
            ];
        }

        // 获取文章的点赞数量
        $likeCount = ArticleLikes::find()->where(['ArticleID' => $articleId])->count();

        return [
            'status' => 1,
            'likeCount' => $likeCount,
        ];
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

        $userId = \Yii::$app->request->get('userId');
        $articleId = \Yii::$app->request->get('articleId');
        $content = \Yii::$app->request->get('content');

        if ($userId === null || $articleId === null || $content === null) {
            return [
                'status' => 0,
                'message' => 'User ID, Article ID, and Content are required.',
                'userId' => $userId,
                'articleId' => $articleId,
                'content' => $content,
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
        } 
        else {
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
                // $video->ViewCount = $video->ViewCount + 1;
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
        
            foreach ($videos as &$video) {
                $pictureUrl = \Yii::$app->urlManager->createAbsoluteUrl(['src/pic/' . $video->PictureURL]);
                $video->PictureURL = $pictureUrl;
            }
            unset($video);
        
            return $videos;
        }
    }

    public function actionAddvideo()
    {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '300');

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        // 获取POST参数
        $title = \Yii::$app->request->post('title');
        $userId = \Yii::$app->request->post('userId');

        // 获取上传的文件
        $videoFile = UploadedFile::getInstanceByName('video');
        $coverFile = UploadedFile::getInstanceByName('cover');
    
        // 检查必填字段
        if ($title === null || $userId === null || $videoFile === null || $coverFile === null) {
            return [
                'status' => 0,
                'message' => '标题、视频、封面和用户 ID 是必需的。',
            ];
        }   
    
        // 验证用户是否存在
        $user = Users::findOne($userId);
        if ($user === null) {
            return [
                'status' => 0,
                'message' => '无效的用户 ID。',
            ];
        }
    
        // 验证视频文件
        $allowedVideoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv'];
        $videoExtension = strtolower($videoFile->extension);
        if (!in_array($videoExtension, $allowedVideoExtensions)) {
            return [
                'status' => 0,
                'message' => '无效的视频文件类型。',
            ];
        }
    
        // 验证封面文件
        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $coverExtension = strtolower($coverFile->extension);
        if (!in_array($coverExtension, $allowedImageExtensions)) {
            return [
                'status' => 0,
                'message' => '无效的图片文件类型。',
            ];
        }
    
        // 保存视频文件
        $videoDir = Yii::getAlias('@webroot/src/videos/');
        if (!is_dir($videoDir)) {
            if (!mkdir($videoDir, 0755, true)) {
                return [
                    'status' => 0,
                    'message' => '无法创建视频上传目录。',
                ];
            }
        }
        $uniqueVideoName = uniqid('video_', false) . '.' . $videoFile->extension;
        $videoPath = 'src/videos/' . $uniqueVideoName;
        $fullVideoPath = Yii::getAlias('@webroot/') . $videoPath;

        if (!$videoFile->saveAs($fullVideoPath)) {
            return [
                'status' => 0,
                'message' => '保存视频文件失败。',
            ];
        }

        $coverDir = Yii::getAlias('@webroot/src/pic/');
        if (!is_dir($coverDir)) {
            if (!mkdir($coverDir, 0755, true)) {
                unlink($fullVideoPath);
                return [
                    'status' => 0,
                    'message' => '无法创建封面上传目录。',
                ];
            }
        }
        $uniqueCoverName = uniqid('cover_', true) . '.' . $coverFile->extension;
        $coverPath = 'src/pic/' . $uniqueCoverName;
        $fullCoverPath = Yii::getAlias('@webroot/') . $coverPath;

        if (!$coverFile->saveAs($fullCoverPath)) {
            unlink($fullVideoPath);
            return [
                'status' => 0,
                'message' => '保存封面文件失败。',
            ];
        }
    
        $video = new Videos();
        $video->Title = $title;
        $video->URL = $uniqueVideoName;
        $video->UserID = $userId;
        $video->PictureURL = $uniqueCoverName;
        $video->UploadedAt = date('Y-m-d H:i:s');
    
        // 尝试保存视频
        if ($video->save()) {
            return [
                'status' => 1,
                'message' => '视频添加成功。',
                'video' => [
                    'VideoID' => $video->VideoID,
                ],
            ];
        } else {
            unlink($fullVideoPath);
            if ($coverPath !== null) {
                unlink($fullCoverPath);
            }
    
            \Yii::error('保存视频失败: ' . json_encode($video->errors), __METHOD__);
            return [
                'status' => 0,
                'message' => '视频添加失败。',
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

    public function actionLikenumvideo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        // 获取视频 ID
        $videoId = \Yii::$app->request->get('videoId');
    
        if ($videoId === null) {
            return [
                'status' => 0,
                'message' => 'Video ID is required.',
            ];
        }
    
        // 获取视频的点赞数量
        $likeCount = VideoLikes::find()->where(['VideoID' => $videoId])->count();
    
        return [
            'status' => 1,
            'likeCount' => $likeCount,
        ];
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
        } 
        else {
            return [
                'status' => 1,
                'liked' => false,
            ];
        }
    }

    public function actionCommentvideo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $userId = \Yii::$app->request->get('userId');
        $videoId = \Yii::$app->request->get('videoId');
        $content = \Yii::$app->request->get('content');

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

    public function actionGetvideototal()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $count = Videos::find()->count();

        return [
            'status' => 1,
            'count' => $count,
        ];
    }

    public function actionGetstudent()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $student_id = Yii::$app->request->get('student_id');

        if ($student_id === null) {
            return [
                'status' => 0,
                'message' => 'ID is required.',
            ];
        }

        $student = Students::find()->where(['student_id' => $student_id])->one();

        if ($student === null) {
            return [
                'status' => 0,
                'message' => 'Student not found.',
            ];
        }

        $studentUrl = \Yii::$app->urlManager->createAbsoluteUrl(['src/personal/' . $student->file_path]);

        // 返回学生的全部信息
        return [
            'status' => 1,
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'student_id' => $student->student_id,
                'role' => $student->role,
                'file_path' => $studentUrl,
                'email' => $student->email,
                'github' => $student->github,
                'wechat' => $student->wechat,
            ],
        ];
    }

    public function actionGetallstudents()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $students = Students::find()->all();

        $studentData = [];
        foreach ($students as $student) {
            $studentUrl = \Yii::$app->urlManager->createAbsoluteUrl(['src/personal/' . $student->file_path]);
            $studentData[] = [
                'id' => $student->id,
                'name' => $student->name,
                'student_id' => $student->student_id,
                'role' => $student->role,
                'file_path' => $studentUrl,
                'email' => $student->email,
                'github' => $student->github,
                'wechat' => $student->wechat,
            ];
        }

        return [
            'status' => 1,
            'students' => $studentData,
        ];
    }
}
