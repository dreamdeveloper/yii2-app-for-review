<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\City */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Setting Index';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="city-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => false,
        'options' => [
            'enctype'=>'multipart/form-data'
        ]
    ]); ?>

    <table class="table">
        <tr>
            <td rowspan="1"><?= $form->field($model, 'username') ?></td>
        </tr>
        <tr>
            <td ><?= $form->field($model, 'name') ?></td>
        </tr>
        <trcolspan="3">
            <td colspan="3" width="33%">
                <?=$form->field($model, 'file3')->widget(FileInput::classname(), [
                    'pluginOptions' => [
                        'showUpload' => false,
                        'browseLabel' => '',
                        'removeLabel' => '',
                        'mainClass' => 'input-group-sm',
                        'initialPreview' => [
                            ($model->logo_image) ? Html::img(Yii::getAlias('@web') . '/images/uploads/users/' . $model->logo_image, ['class' => 'file-preview-image', 'alt' => 'logo_image', 'title' => 'logo_image']) : null,
                        ],
                        'showRemove' => false,
            
                    ],
                    'options' => [
                        'accept' => 'image/*'
                    ],
                ]);?>
            </td>
        </tr>
        <tr>
            
        </tr>
    </table>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
