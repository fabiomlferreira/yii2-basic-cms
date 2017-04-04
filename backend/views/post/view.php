<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?=
                Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ])
                ?>
            </div>
            <div class="box-body table-responsive">

                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'user_id',
                        'date',
                        'content:ntext',
                        'title',
                        'slug',
                        'typeLabel',
                        'feature_image_id',
                        'commentStatusLabel',
                        'comment_count',
                        'post_lang_parent',
                        'lang',
                        'statusLabel',
                        'created_at:datetime',
                        'updated_at:datetime',
                    ],
                ])
                ?>

            </div>  
        </div>
    </div>
</div>
