<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model app\models\City */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="city-form">

    <?php $form = ActiveForm::begin(); ?>
    <table class="table">
        <tr>
            <td colspan="2" width="50%" >
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </td>
            <td rowspan="2" width="70%">
                <div id="map"></div>
                <br/>
                <?= $form->field($locationModel, 'latitude')->hiddenInput()->label(false) ?>
                <?= $form->field($locationModel, 'longtitude')->hiddenInput()->label(false) ?>
                
            </td>
        </tr>
        <tr>
            <td colspan="2" width="50%">
                <?= $form->field($locationModel, 'address')->textInput(['onchange' => 'codeAddress()']) ?>
                <br/>
                <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-info btn-location-right' : 'btn btn-info btn-location-right']) ?>
            </td>
        </tr>
    </table>
    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript" src="/web/js/map.js"></script>

