<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\growl\Growl;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php
if ($alert = Yii::$app->session->getFlash('alert')) {
    if ($alert['state']) {
        echo Growl::widget([
            'type' => $alert['type'],
            'title' => $alert['title'],
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => $alert['message'],
            'showSeparator' => true,
            'delay' => 0,
            'linkUrl' => \yii\helpers\Url::to($alert['url']),
            'linkTarget' => ($alert['url']) ? true : false,
            'pluginOptions' => [
                'placement' => [
                    'from' => 'top',
                    'align' => 'right',
                ]
            ]
        ]);
        Yii::$app->session->setFlash('alert');
    }
}

$controller = Yii::$app->controller;
$isHome = $controller->action->id;
?>
<div class="wrap <?= ($isHome == "login" || $isHome == "signup") ? 'home-wrap' : ''?>">
    <?php
    
    if($isHome != "login" && $isHome != "signup"){
        NavBar::begin([
            'brandLabel' => 'fenceapp',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
              
                [
                    'label' => 'Activities',
                    'url' => ['/offer'],
                    'visible' => !Yii::$app->user->isGuest,
                ],
                [
                    'label' => 'Locations',
                    'url' => ['/city'],
                    'visible' => !Yii::$app->user->isGuest,
                ],
                [
                    'label' => 'Type',
                    'url' => ['/type'],
                    'visible' => (Yii::$app->user->identity && Yii::$app->user->identity->isAdmin())?
                    Yii::$app->user->identity->isAdmin():
                    false,
                ],
                [
                    'label' => 'Account settings',
                    'url' => ['/setting/index'],
                    'visible' => !Yii::$app->user->isGuest,
                ],
                [
                    'label' => 'Payments',
                    'url' => ['/payment'],
                    'visible' => !Yii::$app->user->isGuest,
                ],
                [
                    'label' => 'Change password',
                    'url' => ['/site/change-password'],
                    'visible' => !Yii::$app->user->isGuest,
                ],
                [
                    'label' => 'Signup',
                    'url' => ['/site/signup'],
                    'visible' => Yii::$app->user->isGuest,
                ],
                Yii::$app->user->isGuest ?
                [
                    'label' => 'Login',
                    'url' => ['/site/login'],
                ]:
                [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post'],
                ],
            ],
        ]);
        NavBar::end();
    }
    
    ?>

    <div class="container">
        <?php 
            if($controller->id == "payment"){
                echo Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]); 
            }
        ?>
        <?= $content ?>
    </div>
</div>
<?php if($isHome != "login" && $isHome != "signup"){ ?>
<footer class="footer">
    <div class="container">
        <p>fenceapp (C) 2017</p>
    </div>
</footer>
<?php } ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
