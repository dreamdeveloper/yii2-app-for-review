<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Locations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-index">

    <h1><?= Html::encode($this->title) ?> <?= Html::a('Add a location', ['create'], ['class' => 'btn btn-info right-align']) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [   
                'class' => 'yii\grid\SerialColumn', 
                'header' => '',
                'headerOptions' => ['class' => 'col-sm-1 first-column']
            ],
            [
                'attribute'=>'name',
                'format' => 'html',
                'value' => function($data){ return \app\models\Location::drawTableForCity($data->cityId)." - ".$data->name; },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['class' => 'col-sm-1']
            ],
        ],
    ]); ?>

</div>
