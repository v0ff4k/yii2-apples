<?php

declare(strict_types=1);
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => \yii\rest\Module::class,
            'controllerNamespace' => 'backend\controllers\api',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // API routes
                'GET api/apple' => 'api/apple/index',
                'POST api/apple' => 'api/apple/create',
                'GET api/apple/<id:\d+>' => 'api/apple/view',
                'POST api/apple/<id:\d+>/fall' => 'api/apple/fall',
                'POST api/apple/<id:\d+>/eat' => 'api/apple/eat',
                'DELETE api/apple/<id:\d+>' => 'api/apple/delete',
            ],
        ],
    ],
    'params' => $params,
];
