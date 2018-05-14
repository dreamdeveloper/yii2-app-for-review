<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;


/* @var $this yii\web\View */
/* @var $model app\models\Type */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?=$form->field($model, 'featured')->widget(CheckboxX::classname(), ['attribute' => 'featured',
        'pluginOptions' => [
            'threeState' => false,
            'size' => 'lg'
        ]]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
