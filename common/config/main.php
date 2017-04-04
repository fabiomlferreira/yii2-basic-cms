<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name' => "Dietas",
    'language' => 'pt-PT',
    'components' => [
        /*'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        'jquery.min.js'
                        //YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                    ],
                    'jsOptions' => ['position' => \yii\web\View::POS_HEAD] // activate to put jquery on head
                ],
                'frontend\assets\ModernizrAsset'=> [
                    'js' => [
                        'js/modernizr/modernizr.min.js'
                        //YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                    ],
                    'jsOptions' => ['position' => \yii\web\View::POS_HEAD] // activate to put jquery on head
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        'js/bootstrap.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        'css/bootstrap.min.css'
                    ]
                ],
            ],
        ],*/
        
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache', //caminho comum para guardar a cache para conseguir apagar atravez do backend
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
	    'defaultRoles' => ['admin','voter','user'], // here define your roles
            'itemFile' => '@console/data/items.php', //Default path to items.php | NEW CONFIGURATIONS
            'assignmentFile' => '@console/data/assignments.php', //Default path to assignments.php | NEW CONFIGURATIONS
	    'ruleFile' => '@console/data/rules.php', //Default path to rules.php | NEW CONFIGURATIONS
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            
        ],
        'config' => [
            'class' => 'common\components\Config'
        ],/*
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'en-US', // Developer language
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'cachingDuration' => 86400,
                    'enableCaching' => false,
                    'forceTranslation' => true,
                ],
                'app' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'en-US', // Developer language
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'forceTranslation' => true,
                    'cachingDuration' => 86400,
                    'enableCaching' => false,
                ],
                /*'user' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'en-US', // Developer language
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'forceTranslation' => true,
                    'cachingDuration' => 86400,
                    'enableCaching' => false,
                ],*//*
            ],
        ],*/
        'thumbnail' => [
            'class' => 'fabiomlferreira\filemanager\Thumbnail',
            'cachePath' => '@webroot/assets/thumbnails',
            'basePath' => '@webroot',
            'cacheExpire' => 2592000,
            'options' => [
                'placeholder' => [
                    'type' => fabiomlferreira\filemanager\Thumbnail::PLACEHOLDER_TYPE_JS,
                    'backgroundColor' => '#f5f5f5',
                    'textColor' => '#cdcdcd',
                    'text' => 'Ooops',
                    'random' => true,
                    'cache' => false,
                ],
                'quality' => 75
            ]
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableUnconfirmedLogin' => false,
            'enableFlashMessages' => false,
            'enableRegistration' => true,
            'confirmWithin' => 21600,
            'cost' => 12,
            'admins' => ['fabiomlferreira'],
            'modelMap' => [
                'User' => 'common\models\User',
                //'LoginForm' => 'common\models\user\LoginForm',
                //'Profile' => 'common\models\Profile',
                //'Account' => 'common\models\user\Account', //override to save access_token on data field on social_account table
                /*'RegistrationForm' => 'common\models\user\RegistrationForm',
                'LoginForm' => 'common\models\user\LoginForm',
                'SettingsForm' => 'common\models\user\SettingsForm',
                'RecoveryForm' => 'common\models\user\RecoveryForm',
                'ResendForm' => 'common\models\user\ResendForm',*/

            ],

        ],
        'filemanager' => [
            'class' => 'fabiomlferreira\filemanager\Module',
            //'class' => 'pendalf89\filemanager\Module',
            'rename' => true, //permite ter fazer upload de fotos com o mesmo nome
            'autoUpload' => true,
            'optimizeOriginalImage' => true,
            'maxSideSize' => 1200,
            'thumbnailOnTheFly' => true,  //if is true the component thumbnail should be used
            // Upload routes
            'routes' => [
                // Base absolute path to web directory
                'baseUrl' => '',
                // Base web directory url
                'basePath' => '@frontend/web',
                // Path for uploaded files in web directory
                'uploadPath' => 'uploads',
            ],
            // Thumbnails info
            'thumbs' => [ 
                'default' => [
                    'name' => 'default',
                    'size' => [125, 125],
                ],
                'small_square' => [
                    'name' => 'medium_square',
                    'size' => [300, 300],
                ],
            ],
            //restringe o acesso a este modulo
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        //'actions' => ['filemanager'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->can('adminApp');
                        }
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->isGuest)
                         Yii::$app->controller->redirect(['/login']);
                    else
                         Yii::$app->controller->redirect(['/site/restrito',
                            'name' => Yii::t('app', 'Access denied'),
                            'message' => Yii::t('app', 'You have no permission to access this page'),
                            'url' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/'])
                        ]); //frontend url;
                }

            ],
        ],
    ]
];
