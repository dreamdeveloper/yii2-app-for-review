<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\City */

$this->title = 'Update a location: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->cityId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyD4Ywp9jCnVp8eV_hsQNAjU9cF9RE88vjA&language=en&region=en" type="text/javascript"></script>

<div class="city-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'locationModel' => $locationModel
    ]) ?>

</div>
