<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OfferSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="offer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'offerId') ?>

    <?= $form->field($model, 'image1') ?>

    <?= $form->field($model, 'image2') ?>

    <?= $form->field($model, 'image3') ?>

    <?= $form->field($model, 'featured') ?>

    <?= $form->field($model, 'hide_button') ?>

    <?= $form->field($model, 'rank') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
