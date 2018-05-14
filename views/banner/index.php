<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\City */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="city-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
        'options' => [
            'enctype'=>'multipart/form-data'
        ]
    ]); ?>

    <?=$form->field($model, 'file')->widget(FileInput::classname(), [
        'pluginOptions' => [
            'showUpload' => false,
            'browseLabel' => '',
            'removeLabel' => '',
            'mainClass' => 'input-group-sm',
            'initialPreview' => [
                ($model->image) ? Html::img($model->image, ['class' => 'file-preview-image', 'alt' => 'Image1', 'title' => 'Image1']) : null,
            ],
            'showRemove' => false,
        ],
        'options' => [
            'accept' => 'image/*'
        ],
    ]);?>

    <?=$form->field($model, 'url')->textInput();?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
