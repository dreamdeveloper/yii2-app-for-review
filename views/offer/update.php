<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Offer */
/* @var $cities app\models\City::getAllCities() */
/* @var $categories app\models\Type::getAllTypes() */

$this->title = 'Update Offer: ' . ' ' . $model->offerId;
$this->params['breadcrumbs'][] = ['label' => 'Offers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->offerId, 'url' => ['view', 'id' => $model->offerId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="offer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'cities' => $cities,
        'result_types' => $types,
        'types_result' => $types_result,
        'result_locations' => $locations,
        'locations_result' => $locations_result,
        'locationModel' => $locationModel,
        'locationDataProvider' => $locationDataProvider,
        'cityDataProvider' => $cityDataProvider,
        'typeDataProvider' => $typeDataProvider,
    ]) ?>

</div>
