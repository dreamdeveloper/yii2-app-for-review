<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    
    <div class="form-login-center">
        <h1 class='helvetica'>fenceapp</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tristique ex luctus, convallis mi nec, venenatis purus. Donec ultrices neque et nisl laoreet, eget tempor ante facilisis. </p>

        <br/>
        <div class="form-login-content">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => ''],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n",
                    'labelOptions' => ['class' => 'col-lg-1 control-label'],
                    'errorOptions'=> ['class'=>'help-block help-block-error login-form-error']
                ],
            ]); ?>
        
                <?= $form->field($model, 'username')->textInput(['placeholder' => "Login", 'class' => "form-control input-lg"])->label(false)?>
        
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password'), 'class' => "form-control input-lg"])->label(false) ?>
        
        
                <div class="form-group">
                    <!--<div class="col-lg-offset-1 col-lg-11">-->
                        <?= Html::submitButton('Login', ['class' => 'btn btn-info btn-login', 'name' => 'login-button']) ?>
                    <!--</div>-->
                    <br/>
                    <!--<div class="col-lg-offset-1 col-lg-11">-->
                        <?= Html::a('Register', '/signup', ['class' => 'link-login']) ?>
                    <!--</div>-->
        
                </div>
        
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="home-footer">
        <p class="lr">fence discounting.</p>
    </div>
</div>
