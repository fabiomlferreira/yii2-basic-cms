<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div><!-- /.box-header -->

        
            <div class="box-body">
                <?= Html::a(Yii::t('app', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
            </div>

            <div class="box-body table-responsive">
                    <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
            'category',
            'parent_category',
            'type',
            'lang',
            // 'count',
            // 'created_at',
            // 'updated_at',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
                    </div>
        </div>
    </div>
</div>

