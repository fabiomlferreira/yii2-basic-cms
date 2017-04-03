<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */
$this->title = $name;
?>
<!-- Main content -->
<section class="content">

    <div class="error-page">
        <h2 class="headline text-info"><i class="fa fa-warning text-yellow"></i></h2>

        <div class="error-content">
            <h3><?= $name ?></h3>

            <p>
                <?= nl2br(Html::encode($message)) ?>
            </p>
            <p>
                O erro acima ocorreu enquanto tentamos processar o seu pedido.
            </p>
            <p>
                Por favor contacte-nos se acha que é um erro do site. Obrigado.
            </p>
            <br>
            <?php $url = empty($url) ? ['/'] : $url; ?> 
            <h4><?= Html::a('Voltar para <b>Dietas</b>', $url)?>   </h4>
        </div>
    </div>

</section>