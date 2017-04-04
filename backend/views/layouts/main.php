<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
/*
if (!Yii::$app->user->can('adminApp')) {
    $this->render('wrapper-black', ['content' => '@app/view/site/restrito']);
}*/

if (Yii::$app->controller->action->id === 'login') {
    echo $this->render(
        'wrapper-black',
        ['content' => $content]
    );
} else {
    backend\assets\AppAsset::register($this);
    ?> 
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        
        <?php $this->head() ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-yellow-light">
    <?php $this->beginBody() ?>
        <div class="wrapper">
            <?= $this->render(
                'header'
            ) ?>

            <?= $this->render(
                'left'
            )
            ?>

            <?= $this->render(
                'content',
                ['content' => $content]
            ) ?>
            
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Versão</b> 1.0
                </div>
                <strong>Copyright &copy; <?= date('Y') ?> <a href="<?= Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/']) ?>"><?= Yii::$app->name ?></a>.</strong> Todos os direitos reservados.
            </footer>

        </div>
        
    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
