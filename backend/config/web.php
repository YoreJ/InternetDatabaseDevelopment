<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'u9l3RMHhW59fyXjdztPqEr22gAlH_gPA',
            'enableCsrfValidation' => false 
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'api/addwebviews' => 'api/addwebviews', // finish 添加网站访问量
                'api/getwebviews' => 'api/getwebviews', // finish 获取网站访问量
                'api/login' => 'api/login', // finish 登录
                'api/signup' => 'api/signup', // finish 注册

                'api/getuser' => 'api/getuser', // finish 获取用户信息
                'api/chat' => 'api/chatbot', // finish 聊天机器人

                'api/getarticle' => 'api/getarticle', // finish 获取文章
                'api/addarticle' => 'api/addarticle', // finish 添加文章
                'api/deletearticle' => 'api/deletearticle', // finish 删除文章
                'api/viewarticle' => 'api/viewarticle', // finish 增加文章访问量
                'api/likearticle' => 'api/likearticle', // finish 喜欢文章
                'api/likenumarticle' => 'api/likenumarticle', // finish 获取文章喜欢数
                'api/getlikearticle' => 'api/getlikearticle', // finish 获取是否喜欢文章
                'api/commentarticle' => 'api/commentarticle', // finish 评论文章
                'api/showcommentarticle' => 'api/showcommentarticle', //finish 显示文章评论
                'api/deletecommentarticle' => 'api/deletecommentarticle', //finish 删除文章评论
                'api/getarticlepagecount' => 'api/getarticlepagecount', //finish 获取文章页数
                
                'api/getvideo' => 'api/getvideo', // finish 获取视频
                'api/addvideo' => 'api/addvideo', // finish 添加视频
                'api/deletevideo' => 'api/deletevideo', // finish 删除视频
                'api/viewvideo' => 'api/viewvideo', // finish 增加视频访问量 
                'api/likevideo' => 'api/likevideo', // finish 喜欢视频 
                'api/likenumvideo' => 'api/likenumvideo', // finish 获取视频喜欢数
                'api/getlikevideo' => 'api/getlikevideo', // finish 获取是否喜欢视频
                'api/commentvideo' => 'api/commentvideo', // finish 评论视频
                'api/showcommentvideo' => 'api/showcommentvideo', //finish 显示视频评论
                'api/deletecommentvideo' => 'api/deletecommentvideo', //finish 删除视频评论  
                'api/getvideopagecount' => 'api/getvideopagecount', //finish 获取视频页数
                
            ],
        ],
        'response' => [
            // 'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $response->headers->set('Access-Control-Allow-Origin', '*');//这个是设置跨域
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
                //$event->sender->headers->remove('Access-Control-Allow-Origin');//这个是删除跨域规则
            },
        ],
        'as corsFilter' => [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['http://localhost:6262'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Expose-Headers' => [],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
