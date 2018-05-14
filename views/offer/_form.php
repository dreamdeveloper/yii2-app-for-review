<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\checkbox\CheckboxX;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use kartik\touchspin\TouchSpin;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Offer */
/* @var $form yii\widgets\ActiveForm */
/* @var $cities array */
/* @var $types array */
$update = !$model->isNewRecord;
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/createOffer.js',['depends' => [\yii\web\JqueryAsset::className()]]);

?>
<style type="text/css">
    .select2-selection__clear { display: none;}
</style>
<div class="offer-form">

    <?php $form = ActiveForm::begin([
        'id' => 'dynamic-form',
        'enableAjaxValidation' => true,
        'options' => [
            'enctype'=>'multipart/form-data'
        ]
    ]); ?>

    <table class="table">
        <tr>
            <td colspan="<?=(Yii::$app->user->identity && Yii::$app->user->identity->isAdmin())?'2':'3'?>" width="33%">
                <?=$form->field($model, 'file1')->widget(FileInput::classname(), [
                    'pluginOptions' => [
                        'showUpload' => false,
                        'browseLabel' => '',
                        'removeLabel' => '',
                        'mainClass' => 'input-group-sm',
                        'initialPreview' => [
                            ($model->image1) ? Html::img(Yii::getAlias('@web') . '/images/uploads/offers/' . $model->image1, ['class' => 'file-preview-image', 'alt' => 'Image1', 'title' => 'Image1']) : null,
                        ],
                        'showRemove' => true,
                    ],
                    'options' => [
                        'accept' => 'image/*'
                    ],
                ]);?>
            </td>
            <td colspan="<?=(Yii::$app->user->identity && Yii::$app->user->identity->isAdmin())?'2':'3'?>" width="33%">
                <?=$form->field($model, 'file2')->widget(FileInput::classname(), [
                    'pluginOptions' => [
                        'showUpload' => false,
                        'browseLabel' => '',
                        'removeLabel' => '',
                        'mainClass' => 'input-group-sm',
                        'initialPreview' => [
                            ($model->image2) ? Html::img(Yii::getAlias('@web') . '/images/uploads/offers/' . $model->image2, ['class' => 'file-preview-image', 'alt' => 'Image1', 'title' => 'Image1']) : null,
                        ],
                        'showRemove' => false,

                    ],
                    'options' => [
                        'accept' => 'image/*'
                    ],
                ]);?>
            </td>
            <?php if( Yii::$app->user->identity && Yii::$app->user->identity->isAdmin() ): ?>
            <td colspan="2" width="33%">
                <?=$form->field($model, 'file3')->widget(FileInput::classname(), [
                    'pluginOptions' => [
                        'showUpload' => false,
                        'browseLabel' => '',
                        'removeLabel' => '',
                        'mainClass' => 'input-group-sm',
                        'initialPreview' => [
                            (\app\models\User::getLogoImage($model->offerId)) ? Html::img(Yii::getAlias('@web') . '/images/uploads/users/' . \app\models\User::getLogoImage($model->offerId), ['class' => 'file-preview-image', 'alt' => 'logo_image', 'title' => 'logo_image']) : null,
                        ],
                        'showRemove' => false,

                    ],
                    'options' => [
                        'accept' => 'image/*'
                    ],
                ]);?>
            </td>
            <?php endif; ?>
        </tr>
        <?php
            $model->locations = $locations_result;
            $model->types = $types_result;
            
            $location_unselect = "function(evt) { if(confirm('Are you sure you want to delete this item?')){ window.location.href='/offer/delete-location-from-offer?id='+evt.params.args.data['id']+'&offer=".$model->offerId."'; } else { evt.preventDefault(); evt.stopPropagation(); return false;} }";
            $eventUnselectLocation = ($update)?$location_unselect:'function(evt) {}';
            
            $type_unselect = "function(evt) { if(confirm('Are you sure you want to delete this item?')){ window.location.href='/offer/delete-type-from-offer?id='+evt.params.args.data['id']+'&offer=".$model->offerId."'; } else { evt.preventDefault(); evt.stopPropagation(); return false;} }";
            $eventUnselectType = ($update)?$type_unselect:'function(evt) {}';
        ?>
        <tr>
            <td colspan="2">
                <?=$form->field($model, 'locations')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map($result_locations, 'locationId', 'address'),
                    'pluginEvents' => [
                        "select2:unselecting" => $eventUnselectLocation
                    ],
                    'options' => [
                        'placeholder' => 'Search for a address ...',
                        'multiple' => true
                    ],
                    
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);?>
            </td>
            <td colspan="2">
                <?=$form->field($model, 'types')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map($result_types, 'typeId', 'name'),
                    'pluginEvents' => [
                        "select2:unselecting" => $eventUnselectType
                    ],
                    'options' => [
                        'placeholder' => 'Search for a types ...',
                        'multiple' => false
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);?>
            </td>
            <td colspan="2" style="width: 100px;">
                <?=$form->field($model, 'maxCount')->widget(TouchSpin::classname(), [
                    'options' => [
                        'placeholder' => 'Max count'
                    ],
                    'pluginOptions' => [
                        'verticalbuttons' => true,
                        'min' => 1,
                        'max' => 2147483647,
                    ]

                ]);?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <?= $form->field($model, 'description')->textarea(['rows' => '6']) ?>
            </td>
            <td colspan="3">
                <?php if(!$update):
                    $model->from_date = date("Y-m-d");
                    $model->to_date = date("Y-m-d");
                    
                    $model->from_time = "00:00:00";
                    $model->to_time = "23:00:00";
                endif;?>
                
                <?php echo $form->field($model, 'from_date', ['options' => ['class' => 'col-md-6']])->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                    'saveFormat' => 'php:Y-m-d',
                    'displayFormat' => 'php:Y-m-d',
                ]);?>
                <?php echo $form->field($model, 'to_date', ['options' => ['class' => 'col-md-6']])->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                    'saveFormat' => 'php:Y-m-d',
                    'displayFormat' => 'php:Y-m-d',
                ]);?>
                
                <?php echo $form->field($model, 'from_time', ['options' => ['class' => 'col-md-6']])->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_TIME,
                    'saveFormat' => 'php:H:i:s',
                    'displayFormat' => 'php:H:i',
                ]);?>
                
                <?php echo $form->field($model, 'to_time', ['options' => ['class' => 'col-md-6']])->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_TIME,
                    'saveFormat' => 'php:H:i:s',
                    'displayFormat' => 'php:H:i',
                ]);?>

                <?php 
                    $timestamp = strtotime('next Monday');
                    $days = array();
                    for($i = 0; $i < 7; $i++){
                        $sel[$i+1] = $i+1;
                        $days[$i+1] = mb_substr(strftime('%A', $timestamp), 0, 3, 'UTF-8');
                        $timestamp = strtotime('+1 day', $timestamp);
                    }
                   
                   
                    if(gettype($model->days) == "string"){
                        $model->days = explode(',', $model->days);
                    } else {
                        $model->days = $sel;
                    }
                    
                    echo $form->field($model, 'days', ['options' => ['class' => 'col-md-9']])->checkboxList($days);
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <?= $form->field($model, 'short_description')->textarea(['rows' => '6']) ?>
            </td>
        </tr>
        <?php if( Yii::$app->user->identity && Yii::$app->user->identity->isAdmin() ): ?>
        <tr>
            <td>
                <?=$form->field($model, 'featured')->widget(CheckboxX::classname(), ['attribute' => 'featured',
                    'pluginOptions' => [
                        'threeState' => false,
                        'size' => 'lg'
                    ],
                ]); ?>
            </td>
        </tr>
        <?php endif; ?>
    </table>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
