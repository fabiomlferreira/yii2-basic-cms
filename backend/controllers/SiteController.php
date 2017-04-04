<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\db\Query;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'restrito'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'userlist'],
                        'allow' => true,
                        //'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->can('adminApp');
                        }
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->isGuest)
                        $this->redirect(['/login']);
                    else
                        $this->redirect(['/site/restrito', 
                            'name' => Yii::t('app', 'Access denied'), 
                            'message' => Yii::t('app', 'You have no permission to access this page'), 
                            'url' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/'])
                        ]); //frontend url;
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    
    public function beforeAction($action)
    {
        if ($action->id == 'error')
            $this->layout = 'wrapper-black';

        return parent::beforeAction($action);
    }
    
    public function actionRestrito($name, $message, $url = null)
    {
        $this->layout = 'wrapper-black';
        return $this->render('/site/error', [
                'name' => $name,
                'message' => $message,
                'url' => $url
        ]);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    /**
     * Return the user that we can send a message
     * @param type $q
     * @param type $id
     * @return type
     */
    public function actionUserlist($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, username AS text')
                ->from('user')
                ->where('username LIKE "%' . $q .'%"')
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => \common\models\User::find($id)->name];
        }
        return $out;
    }
    
}
