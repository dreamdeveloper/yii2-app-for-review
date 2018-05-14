<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Offer */
/* @var $cities app\models\City::getAllCities() */
/* @var $categories app\models\Type::getAllTypes() */

$this->title = 'Create Offer';
$this->params['breadcrumbs'][] = ['label' => 'Offers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'cities' => $cities,
        'result_types' => $types,
        'types_result' => $types_result,
        'result_locations' => $locations,
        'locationModel' => $locationModel,

    ]) ?>

</div>
