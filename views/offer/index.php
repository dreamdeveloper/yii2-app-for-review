<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Offer;
use app\models\User;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OfferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Activities';
$this->params['breadcrumbs'][] = $this->title;

$calc_exp_date = User::getExpiresDate();
$current_date = Yii::$app->formatter->asTimestamp(date("Y-m-d"));
$expires_date = Yii::$app->formatter->asTimestamp($calc_exp_date);
                
if( $current_date >= $expires_date ){
    
    Alert::begin([
        'options' => [
            'class' => 'alert-warning',
        ]
    ]);
    
    echo "Your paid period has expired, please pay for the use of our services, so that you can create new Offers.";
    echo Html::a('Pay now', ['/payment'], ['class' => 'btn-sm btn-info btn-pay-now-offer'] );
    
    Alert::end();
}
?>

<div class="offer-index">

    <h1><?= Html::encode($this->title) ?><?= Html::a('Create offer', ['create'], ['class' => 'btn btn-info right-align btn-new-design'] ) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'summary'=>'', 
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => '',
                'headerOptions' => ['class' => 'col-sm-1 first-column'],
            ],
            'maxCount' => [
                'attribute' => 'maxCount',
                'headerOptions' => ['class' => 'col-sm-1'],
            ],
            'used' => [
                'attribute' => 'used',
                'headerOptions' => ['class' => 'col-sm-1'],
                'format' =>'html',
                'value' => function ($model){
                    if($model->used < $model->maxCount){
                        $value = "<span class='round-bullet-success'></span>";
                    }elseif($model->used == $model->maxCount){
                        $value = "<span class='round-bullet-bad'></span>";
                    }
                    return $value;
                }
            ],
            'image1' => [
                'attribute' => 'image1',
                'headerOptions' => ['class' => 'col-sm-1'],
                'format' => 'html',
                'value' => function ($model) {
                    return Html::img(Yii::getAlias('@web') . '/images/uploads/offers/' . $model->image1, [
                        'class' => 'medium-image'
                    ]);
                }
            ],
            'description',
            // 'image2' => [
            //     'attribute' => 'image2',
            //     'format' => 'html',
            //     'value' => function ($model) {
            //         return Html::img(Yii::getAlias('@web') . '/images/uploads/offers/' . $model->image2, [
            //             'class' => 'small-image'
            //         ]);
            //     }
            // ],
            'image3' => [
                'attribute' => 'image3',
                'headerOptions' => ['class' => 'col-sm-1'],
                'format' => 'html',
                'value' => function ($model) {
                    if( !empty(\app\models\User::getLogoImage($model->offerId)) ){

                        return Html::img(Yii::getAlias('@web') . '/images/uploads/users/' . \app\models\User::getLogoImage($model->offerId), [
                            'class' => 'small-image'
                        ]);
                    } else {
                        return 'No image';
                    }

                }
            ],
            
            ['class' => 'yii\grid\ActionColumn','headerOptions' => ['class' => 'col-sm-1'],],
        ],
    ]); ?>

</div>
