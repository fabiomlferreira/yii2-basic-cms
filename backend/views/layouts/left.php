<?php

use yii\bootstrap\Nav;
use yii\helpers\Url;
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php                     
                    $name = !empty(Yii::$app->user->identity->profile->name) ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->username;
                ?>
                <img src="<?= Yii::$app->user->identity->profile->getAvatarUrl(45) ?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p><?= $name?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        
        <ul class="sidebar-menu">
            <?php 
            if(Yii::$app->user->can('adminApp') && Yii::$app->user->id == 1): ?>
            <li class="header">DESENVOLVIMENTO</li>
                <li><a href="<?= Url::to(['/gii']) ?>"><i class="fa fa-file-code-o"></i> Gii</a></li>
                <li><a href="<?= Url::to(['/debug']) ?>"><i class="fa fa-dashboard"></i> Debug</a></li>
                <li class="treeview">
                    <a href="<?= Url::to(['/menus']) ?>">
                        <i class="fa fa-medium"></i> 
                        <span>Menus</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="active"><a href="<?= Url::to(['/menus']) ?>"><i class="fa fa-circle-o"></i> Listar</a></li>
                        <li><a href="<?= Url::to(['/menus/create']) ?>"><i class="fa fa-circle-o"></i> Criar Novo</a></li>
                    </ul>
                </li>
            <?php endif; ?>
            <li class="header">Dietas</li>
           

            
            <?php 
            if(Yii::$app->user->can('adminApp')): ?>
                <li><a href="<?= Url::to(['/user/admin/index']) ?>"><i class="fa fa-user"></i> Utilizadores</a></li> 
                <li>
                  <a href="<?= Url::to(['/filemanager'])?>">
                    <i class="fa fa-th"></i> <span>Gestor de ficheiros</span>
                  </a>
                </li>
                <li class="treeview <?= Yii::$app->controller->id == 'config' ? 'active' : '' ?>">
                    <a href="<?= Url::to(['/config/index']) ?>">
                        <i class="fa fa-gear"></i> 
                        <span>Configurações</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?= Yii::$app->controller->action->id == 'index' ? 'active' : '' ?>"><a href="<?= Url::to(['/config/index']) ?>" ><i class="fa fa-circle-o"></i> Listar configurações</a></li>
                        <li class="<?= Yii::$app->controller->action->id == 'create' ? 'active' : '' ?>"><a href="<?= Url::to(['/config/create']) ?>" ><i class="fa fa-circle-o"></i> Nova configuração</a></li>
                    </ul>
                </li>
            
            <?php endif; ?>
            
          </ul>
        

    </section>
    <!-- /.sidebar -->
</aside>