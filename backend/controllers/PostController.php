<?php

namespace backend\controllers;

use Yii;
use common\models\Post;
use common\models\Tag;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
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
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex($postType = 'post')
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Post::find()->where(['lang' => \Yii::$app->language, 'type' => $postType]),
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'postType' => $postType 
        ]);
    }
    
    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        //se for um número tenta achar usando o id se nao for tenta achar 
        if(is_numeric($id)){
            $model = $this->findModel($id);
            return $this->render('view', [
                'model' => $model,
                'postType' => $model->type
            ]);
        }else{
            $model = $this->findModelBySlug($id);
             return $this->render('view', [
                'model' => $model,
                'postType' => $model->type
            ]);
        }

    }
    
    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($postType = 'post')
    {
        $model = new Post();
        //vai buscar todas as tags para o autocomplete
        /*$tags = Tag::find()
                //->select(['tag as label'])
                ->select(['id','tag'])
                ->where(['lang' => \Yii::$app->language])
                ->asArray()
                ->all();*/
        
        $model->lang = \Yii::$app->language;
        $model->type = $postType;
        $model->user_id = Yii::$app->user->id;
        $model->status = Post::STATUS_PUBLISHED;
        if ($model->load(Yii::$app->request->post()) ) {
            if($model->save()){
                $model->updateTags();
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else {
                return $this->render('create', [
                    'model' => $model,
                    'postType' => $postType,
                    //'tags' => $tags
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'postType' => $postType,
                //'tags' => $tags
            ]);
        }
    }
    
    /**
     * Creates a new model in other languages. It clones the original post
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionTranslate($id)
    {
        $model = $this->findModel($id);
        $model->isNewRecord = true; //adiciona como novo record
        $model->id = null;
        $model->created_at = null;
        $model->updated_at = null;
        $model->post_lang_parent = $id;
        
        /*//vai buscar todas as tags para o autocomplete
        $tags = Tag::find()
                //->select(['tag as label'])
                ->select(['id','tag'])
                ->where(['lang' => \Yii::$app->language])
                ->asArray()
                ->all();*/

        $model->loadCurrentTags();
        
        $model->lang = \Yii::$app->language;
        $model->user_id = Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post()) ) {

            if($model->save()){
               
                $model->updateTags();
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else {
                return $this->render('create', [
                    'model' => $model,
                    'postType' => $model->type,
                    //'tags' => $tags
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'postType' => $model->type,
                //'tags' => $tags
            ]);
        }
    }

    /**
     * Updates an existing Posts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        /*//vai buscar todas as tags para o autocomplete
        $tags = Tag::find()
                //->select(['tag as label'])
                ->select(['id','tag'])
                ->where(['lang' => \Yii::$app->language])
                ->asArray()
                ->all();*/

        $model->loadCurrentTags();
        if ($model->load(Yii::$app->request->post()) ) {
                if($model->save()){
                    $model->updateTags();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else {
                    return $this->render('update', [
                        'model' => $model,
                        'postType' => $model->type,
                        //'tags' => $tags
                    ]);
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'postType' => $model->type,
                    //'tags' => $tags
                ]);
            }
      
    }
    

    /**
     * Deletes an existing Posts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model =  $this->findModel($id);
        $postType = $model->type;
        $model->delete();
        
        if($postType == 'page')
            return $this->redirect(['/post/index/page']);
        else  
            return $this->redirect(['/post/index']);
    }
    
    /**
     * Publish a schedule post
     */
    public function actionPublishschedule()
    {
        $model = Post::find()->where('status = :status AND date <= NOW()', [':status' => Post::STATUS_SCHEDULED])->all();
        foreach($model as $post){
            $post->status = Post::STATUS_PUBLISHED;
            $post->save();
        }
        //print_r($model[0]->title);
       // echo "true";
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $slug
     * @return Post the loaded model or return false if not exist
     */
    protected function findModelBySlug($slug)
    {
        if (($model = Post::findOne(['slug' => $slug])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
            //return false;
        }
    }
}
