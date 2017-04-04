<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Config */

$this->title = Yii::t('app', 'Create Config');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
    ]) ?>
        </div>
    </div>
</div>
