<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\City */

$this->title = 'Add a location';
$this->params['breadcrumbs'][] = ['label' => 'Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyD4Ywp9jCnVp8eV_hsQNAjU9cF9RE88vjA&language=en&region=en" type="text/javascript"></script>

<div class="city-create">
    <style>
        .table > tbody > tr > td {
            border: none;
        }
    </style>
    <h1 class="gray-title"><?= Html::encode($this->title) ?></h1>
    <p class="gray-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tristique ex luctus, convallis mi nec, venenatis purus. Donec ultrices neque et nisl laoreet, eget tempor ante facilisis. </p>
    <br/>
    
    <?= $this->render('_form', [
        'model' => $model,
        'locationModel' => $locationModel
    ]) ?>
    
</div>
