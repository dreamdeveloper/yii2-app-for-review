<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\City */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Cities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->cityId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->cityId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'label' => 'featured',
                'format' => 'html',
                'value' => ($model->featured) ? '<span class="glyphicon glyphicon-ok" style="color: green"></span>' :'<span class="glyphicon glyphicon-remove" style="color: red"></span>',
            ],
            [
                'label' => 'Addresses',
                'format' => 'html',
                'value' => \app\models\Location::drawTableForCity(Yii::$app->request->get('id')),
            ],
        ],
    ]) ?>

</div>
