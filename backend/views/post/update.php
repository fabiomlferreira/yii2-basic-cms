<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

if($postType == 'post'){
    $this->title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => 'Posts',
    ]) . ' ' . $model->title;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['index']];
}else{
    $this->title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => 'Pages',
    ]) . ' ' . $model->title;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="row">

    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <?= $this->render('_form', [
                'model' => $model,
                //'tags' => $tags,
            ]) ?>
        </div>
    </div>
</div>
