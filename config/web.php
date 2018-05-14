<?php
use \kartik\datecontrol\Module;
$params = require(__DIR__ . '/params.php');
$email = require(__DIR__ . '/email.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'app\modules\api\Api',
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module',
        ]
    ],
    'components' => [
        'payPalRest' => [
            'class'        => 'kun391\paypal\RestAPI',
            'pathFileConfig' => __DIR__.'/config.php',
            'successUrl' => '/payment?success=true', //full url action return url
            'cancelUrl' => '/payment?success=false' //full url action return url
        ],
        'request' => [
            'cookieValidationKey' => 'aF-05D2FTu_Uzn6fpUpbepMiTGdCtkqV',
            'baseUrl'=> '',
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
            'class' => 'yii\swiftmailer\Mailer',
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
        'Notification' => [
            'class' => '\app\components\NotificationComponent',
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/offer', 'api/city', 'api/category']
                ],
                'login' => 'site/login',
                'logout' => 'site/logout',
                'signup' => 'site/signup',
                [
                    'class' => 'yii\web\UrlRule',
                    'route' => 'api/offer/create-user',
                    'pattern' => 'api/offers/<id:\d+>/use/<phoneId:[\w\-]*>',
                    'verb' => 'PUT',
                ],
                [
                    'class' => 'yii\web\UrlRule',
                    'route' => 'api/offer/create-user',
                    'pattern' => 'api/offers/<id:\d+>/use',
                    'verb' => 'PUT',
                ],
                [
                    'class' => 'yii\web\UrlRule',
                    'route' => 'api/contact/email',
                    'pattern' => 'api/contacts',
                    'verb' => 'POST',
                ],
            ]
        ],
        'Email' => $email,
    ],
    'params' => $params,
    'defaultRoute' => '/offer',
    'homeUrl' => '/offer',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
