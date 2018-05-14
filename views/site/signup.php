<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    
    <div class="form-register-center">
        <h1 class="helvetica">fenceapp</h1>
    
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tristique ex luctus, convallis mi nec, venenatis purus. Donec ultrices neque et nisl laoreet, eget tempor ante facilisis. </p>

        <br/>
        <div class="form-register-content">
            <?php $form = ActiveForm::begin([
                'id' => 'signup-form',
                'options' => ['class' => ''],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n",
                    'labelOptions' => ['class' => 'col-lg-1 control-label'],
                ],
            ]); ?>
                
                <?= $form->field($model, 'email')->textInput(['placeholder' => "Email", 'class' => "form-control input-lg"])->label(false) ?>
                
                <?= $form->field($model, 'username')->textInput(['placeholder' => "Username", 'class' => "form-control input-lg"])->label(false) ?>
        
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => "Password", 'class' => "form-control input-lg"])->label(false) ?>
                <?= $form->field($model, 'repeatpassword')->passwordInput(['placeholder' => "Confirm password", 'class' => "form-control input-lg"])->label(false) ?>
        
                <div class="form-group">
                    <!--<div class="col-lg-offset-1 col-lg-11">-->
                        <?= Html::submitButton('Register', ['class' => 'btn btn-info btn-login', 'name' => 'signup-button']) ?>
                    <!--</div>-->
                    <br/>
                        <?= Html::a('Login', '/login', ['class' => 'link-register']) ?>
                </div>
        
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="home-footer">
        <p class="lr">fence discounting.</p>
    </div>  
</div>
