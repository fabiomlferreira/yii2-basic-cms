<?php

use yii\widgets\Breadcrumbs;
use backend\widgets\Alert;
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php
            if ($this->title !== null)
                echo $this->title;
            else
                echo Yii::$app->name;
            ?>
            <small>Administração</small>
        </h1>
        <?=
        Breadcrumbs::widget(
                [
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]
        )
        ?>
    </section>

    <!-- Main content -->
    <section class="content"> 
        <?= Alert::widget() ?>
        <?= $content ?>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->