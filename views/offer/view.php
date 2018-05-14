<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Offer */

$this->title = $model->offerId;
$this->params['breadcrumbs'][] = ['label' => 'Offers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->offerId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->offerId], [
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
            [
                'label' => 'image1',
                'format' => 'html',
                'value' => Html::img(Yii::getAlias('@web') . '/images/uploads/offers/' . $model->image1, [
                    'class' => 'small-image'
                ]),
            ],
            [
                'label' => 'image2',
                'format' => 'html',
                'value' => Html::img(Yii::getAlias('@web') . '/images/uploads/offers/' . $model->image2, [
                    'class' => 'small-image'
                ]),
            ],
            [
                'label' => 'image3',
                'format' => 'html',
                'value' => Html::img(Yii::getAlias('@web') . '/images/uploads/users/' . \app\models\User::getLogoImage($model->offerId), [
                    'class' => 'small-image'
                ]),
            ],
            [
                'attribute' => 'featured',
                'format' => 'html',
                'value' => ($model->featured) ? '<span class="glyphicon glyphicon-ok" style="color: green"></span>' :'<span class="glyphicon glyphicon-remove" style="color: red"></span>',
            ],
            'maxCount',
            'used',
            [
                'label' => 'Cities',
                'format' => 'html',
                'value' => \app\models\City::drawTable(Yii::$app->request->get('id')),
            ],
            [
                'label' => 'Type',
                'format' => 'html',
                'value' => \app\models\Type::drawTable(Yii::$app->request->get('id')),
            ],
            [
                'label' => 'Addresses',
                'format' => 'html',
                'value' => \app\models\Location::drawTableByOfferId(Yii::$app->request->get('id')),
            ],
            [
                'label' => 'Date',
                'format' => 'html',
                'value' => date('Y-m-d H:i:s', $model->created),
            ],
            'from_date'
        ],
    ]) ?>

</div>
