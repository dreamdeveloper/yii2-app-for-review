<?php

namespace app\modules\api\controllers;

use app\components\rest\ActiveController;
use app\modules\api\models\City;
use yii\data\ActiveDataProvider;

class CityController extends ActiveController
{
    public $modelClass = 'app\modules\api\models\City';

    public function behaviors()
    {
        return parent::behaviors();
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => City::getCities(),
        ]);
    }

}