<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payment';
$this->params['breadcrumbs'][] = $this->title;

$calc_exp_date = User::getExpiresDate();
$current_date = Yii::$app->formatter->asTimestamp(date("Y-m-d"));
$expires_date = Yii::$app->formatter->asTimestamp($calc_exp_date);

$showButton = false;            
if( $current_date >= $expires_date ){
    $showButton = true;
}

?>
<div class="payment-left-content">
    <h2>Make a payment</h2>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tristique ex luctus, convallis mi nec, venenatis purus. Donec ultrices neque et nisl laoreet, eget tempor ante facilisis. </p>
</div>
<div class="payment-right-content">
    <h2>Fee for using the services</h2>
    
    <?= $infoPayment ?>
    <div class="MembershipPlan-inner">
        <div class="MembershipPlan-header">
            <div class="MembershipPlan-header-inner">
                <span class="MembershipPlan-price">
                    $<?= $fee ?>
                </span>
    
                <span class="MembershipPlan-duration">per month</span>
    
            </div>
            <?php if($showButton): ?>
            <div class="MembershipPlan-cta">
                <?php $form = ActiveForm::begin(['action'=>'payment/create']); ?>
                
                <?= $form->field($model, 'fee')->hiddenInput(['maxlength' => true, 'value' => $fee])->label(false) ?>
                
                <div class="form-group">
                    <?= Html::submitButton('Pay', ['class' => 'btn btn-info']) ?>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if(!empty($date_expires)): ?>
    <div class="pay-info-label">
        <p class="bg-info">
            Your plan expires in: <?=$date_expires?>
        </p>
    </div>
    <?php endif; ?>
</div>
