<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\bootstrap\ActiveForm;
use kartik\datetime\DateTimePicker;

use fabiomlferreira\filemanager\widgets\TinyMCE;
use fabiomlferreira\filemanager\widgets\FileInput;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use common\models\Category;
use kartik\select2\Select2;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(/*['options'=> ["onsubmit"=>"return false;"]]*/); //o onsubmit impede que submeta com o enter
/*$form = ActiveForm::begin([
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-sm-4',
            'offset' => 'col-sm-offset-4',
            'wrapper' => 'col-sm-8',
            'error' => 'col-sm-4',
            'hint' => '',
        ],
    ],
]);*/
?>
<div class="box-body">
    <?php //echo $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>
    
    <?php
    echo $form->field($model, 'content')->widget(TinyMCE::className(), [
        'clientOptions' => [
            'language' => 'pt_PT',
            'menubar' => false,
            'height' => 200,
            'image_dimensions' => false,
            'forced_root_block' => "",
            'valid_elements' => '+*[*]',
            'entity_encoding' => "raw",
            'plugins' => [
                'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code contextmenu table',
            ],
            'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
        ],
    ]);
    ?>
    
    <?= $form->field($model, 'date')->widget(DateTimePicker::classname(), [
	//'options' => ['placeholder' => 'Enter event time ...'],
	'pluginOptions' => [
		'autoclose' => true
	]
]);
    
?>

    <?php
    $thumbImage = $model->featureImage;
    $imgTag = is_object($thumbImage) ? $thumbImage->getThumbImage('default') : '';
    echo $form->field($model, 'feature_image_id')->widget(FileInput::className(), [
        'buttonTag' => 'a',
        'buttonName' => 'Carregar imagem de destaque',
        'buttonOptions' => ['class' => 'btn btn-default'],
        'options' => ['class' => 'form-control'],
        // Widget template
        'template' => '<div class="img" style="margin-bottom:10px;">'.$imgTag.'</div><div class="input-group">'.Html::activeHiddenInput($model, 'feature_image_id').'<span class="input-group-btn">{button}</span></div>',
        // Optional, if set, only this image can be selected by user
        'thumb' => 'small',
        // Optional, if set, in container will be inserted selected image
        'imageContainer' => '.img',
        // Default to FileInput::DATA_URL. This data will be inserted in input field
        'pasteData' => FileInput::DATA_ID,
        // JavaScript function, which will be called before insert file data to input.
        // Argument data contains file data.
        // data example: [alt: "Ведьма с кошкой", description: "123", url: "/uploads/2014/12/vedma-100x100.jpeg", id: "45"]
        'callbackBeforeInsert' => 'function(e, data) {
        console.log( data );
        //$("#img").attr("src", data.url);
    }',
    ]);
    ?>
    
    <?php
        if($model->type == 'post'){
            echo  $form->field($model, "categoriesIds")->checkboxList(ArrayHelper::map(Category::find()->where(['lang'=> Yii::$app->language, 'type' => 'post'])->all(), 'id', 'category'));

            // echo $form->checkBoxList($model, "selectedCategoryIds", GiftCategory::listData()); 
        }
    ?>
    
    
    <?php

        // Tagging support Multiple (maintain the order of selection)
        echo $form->field($model, 'tagsArray')->widget(Select2::classname(), [
            //'value' => ['red', 'green'], // initial value
            //'data' => ArrayHelper::map($tags, 'tag', 'tag'),
            'maintainOrder' => true,
            'options' => ['placeholder' => Yii::t('app', 'Select the tags') , 'multiple' => true],
            'showToggleAll' => false,
            'pluginOptions' => [
                'tags' => true,
                'tokenSeparators' => [','],
                //'maximumInputLength' => 10,
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['/tag/tag-list']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                    //'cache' => true
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(tag) { return tag.text; }'),
                'templateSelection' => new JsExpression('function (tag) { return tag.text; }'),
            ],
        ]);
        
    ?>
    
    <?= $form->field($model, 'comment_status')->dropDownList(
                $model->getCommentStatusOptions()
        ) 
    ?>

    <?php // $form->field($model, 'comment_count')->textInput() ?>

    <?php // $form->field($model, 'lang')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'status')->dropDownList(
                $model->getStatusOptions()
        ) 
    ?>

</div>
<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

