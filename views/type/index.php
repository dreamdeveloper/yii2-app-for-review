<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-index">

    <h1><?= Html::encode($this->title) ?> <?= Html::a('Create Type', ['create'], ['class' => 'btn btn-success right-align']) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'typeId',
            'name',
            [
                'attribute' => 'featured',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->featured) {
                        return '<span class="glyphicon glyphicon-ok" style="color: green"></span>';
                    }
                    return '<span class="glyphicon glyphicon-remove" style="color: red"></span>';
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
