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
                'api/addwebviews' => 'api/addwebviews', // finish
                'api/getwebviews' => 'api/getwebviews', // finish
                'api/login' => 'api/login', // finish
                'api/signup' => 'api/signup', // finish

                'api/getuser' => 'api/getuser', // finish
                'api/chat' => 'api/chatbot', // finish

                'api/getarticle' => 'api/getarticle', // finish
                'api/addarticle' => 'api/addarticle', // finish
                'api/deletearticle' => 'api/deletearticle', // finish
                'api/viewarticle' => 'api/viewarticle', // finish
                'api/likearticle' => 'api/likearticle', // finish
                'api/getlikearticle' => 'api/getlikearticle', // finish
                'api/commentarticle' => 'api/commentarticle', // finish
                'api/showcommentarticle' => 'api/showcommentarticle', //finish
                'api/deletecommentarticle' => 'api/deletecommentarticle', //finish
                
                'api/getvideo' => 'api/getvideo', // finish
                'api/addvideo' => 'api/addvideo', // finish
                'api/deletevideo' => 'api/deletevideo', // finish
                'api/viewvideo' => 'api/viewvideo', // finish
                'api/likevideo' => 'api/likevideo', // finish
                'api/getlikevideo' => 'api/getlikevideo', // finish
                'api/commentvideo' => 'api/commentvideo', // finish
                'api/showcommentvideo' => 'api/showcommentvideo', //finish
                'api/deletecommentvideo' => 'api/deletecommentvideo', //finish      
                
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
