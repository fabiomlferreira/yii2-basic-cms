<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        /*'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],*/
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
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
            //'enableStrictParsing' => true,
            'rules' => [
                'registar' => 'user/registration/register',
                'reenviar' => 'user/registration/resend',
                'confirmar/<id:\d+>/<token:\w+>' => 'user/registration/confirm',
                'login' => '/user/security/login', //nao funciona
                'logout' => '/user/security/logout', //nao funciona
                'recuperar' => 'user/recovery/request',
                'reset/<id:\d+>/<token:\w+>' => 'user/recovery/reset',
                'perfil' => 'user/settings/profile',
                'minha-conta' => 'user/settings/account',
                'admin-utilizadores' => 'user/admin/index',
                'user/<id:\d+>'  => 'user/profile/show', //tive que meter isto senão não conseguia aceder ao perfil do utilizador via link/user/1

                //'file/filemanager' => 'filemanager/file/filemanager',
                /*'<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',*/

            ],  
        ],
        'urlManagerFrontend' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => '/',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ], 
         'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@backend/views/user'
                   //'@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-advanced-app'
                ],
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
          'user' => [
              'class' => 'dektrium\user\Module',
              // following line will restrict access to admin page
              //'as backend' => 'backend\filters\BackendFilter',
              'as backend' => 'dektrium\user\filters\BackendFilter',
              'controllerMap' => [
                  'security' => [
                      'class' => 'dektrium\user\controllers\SecurityController',
                      'layout' => "/wrapper-black",
                  ],
                  //'security' => 'app\controllers\user\SecurityController',
                  //'registration' => [
                  //    'class' => 'dektrium\user\controllers\RegistrationController',
                  //    'layout' => "main",
                  //],
                  'registration' => [
                      'class' => 'dektrium\user\controllers\RegistrationController',
                      'layout' => "/wrapper-black",
                  ],
                  'recovery' => [
                      'class' => 'dektrium\user\controllers\RecoveryController',
                      'layout' => "/wrapper-black",
                  ],
                  //'admin' => 'app\controllers\user\AdminController',
                  //'profile' => 'app\controllers\user\ProfileController'
              ],
          ],
    ]
];
