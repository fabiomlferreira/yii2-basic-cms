<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $postType == 'post' ?  Yii::t('app', 'Posts') : Yii::t('app', 'Pages') ;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div><!-- /.box-header -->

        
            <div class="box-body">
                <?= Html::a($postType == 'post' ?  Yii::t('app', 'Create Posts') : Yii::t('app', 'Create Pages'), ['/post/create/'.$postType], ['class' => 'btn btn-success']) ?>
            </div>

            <div class="box-body table-responsive">
                    <?php
                    $gridViewColumnsArray = [
                        //['class' => 'yii\grid\SerialColumn'],
                        'id',
                        [ 
                            'attribute' => 'title',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                return Html::a($model->title, ['/post/update/'.$model->id]);
                            },
                        ],
                        [                      // the owner name of the model
                            'label' => Yii::t('app', 'Author'),
                            'attribute' => 'user_id',
                            'value' => function ($model, $key, $index, $widget) {
                                return $model->user->username . " (" . $model->user_id . ")";
                            },
                        ],
                        //'content:ntext',
                        [
                            'attribute' => 'status',
                            'value' => function ($model, $key, $index, $widget) {
                                return $model->getStatus($model->status);
                            },
                        ],
                        'date',
                        [  
                            'label' => Yii::t('app', 'See'),
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                return Html::a('Link', Yii::$app->urlManagerFrontend->createAbsoluteUrl(["/pagina/$model->slug"]), ['target' => '_blank']);
                            },
                        ],
                            // 'slug',
                            // 'type',
                            // 'feature_image_id',
                            // 'comment_status',
                            // 'comment_count',
                            // 'lang',
                            // 'status',
                            // 'created_at',
                            // 'updated_at',
                    ];
                    //$other_languages = array_diff(Yii::$app->localeUrls->languages, [Yii::$app->language]); //devolve o array com todas as linguagens que não a escolhida
                    $other_languages =[];
                    //$langArray =[];
                    foreach($other_languages as $language){
                        $gridViewColumnsArray[] = [
                            'label' => $language,
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $column) {
                                $otherLangModel = $model->getLang($column->label);
                                $languageExist = is_object($otherLangModel) ? Html::a(Yii::t('app', 'Edit'), ['/post/update/'.$otherLangModel->id , 'language' =>$column->label ]) : Html::a(Yii::t('app', 'Translate'), ['/post/translate/'.$model->id , 'language' =>$column->label ]);
                               // Url::to(['/update/'.$otherLangModel->id , 'language' =>$column->label ])
                                //Html::a(Yii::t('app', 'Edit'), ['/update/'.$otherLangModel->id , 'language' =>$column->label ]);
                                return $languageExist; 
                                //return 'as'; 
                            },
                            /*'content' => function ($model, $key, $index, $column) {
                                //return $model->lang($language); 
                                return $column->label;
                            },*/
                        ];
                    }

                    $gridViewColumnsArray[] = [
                        'class' => 'yii\grid\ActionColumn',
                        //código para meter a mostrar os posts com o nome em vez do id
                        'urlCreator' => function ($action, $model, $key, $index) {
                            if ($action === 'view') {
                                return Url::to(['/post/view/' . $model->slug]);
                            } else {
                                $params = is_array($key) ? $key : ['id' => (string) $key];
                                $params[0] = 'post' . '/' . $action;
                                return Url::toRoute($params);
                            }
                        }
                    ]
                ?>
                
                
                
                
                
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridViewColumnsArray,
                        ]);
                        ?>
            </div>
        </div>
    </div>
</div>

