<?php

namespace app\modules\api\controllers;

use app\components\rest\ActiveController;
use yii\data\ActiveDataProvider;
use app\modules\api\models\Category;

class CategoryController extends ActiveController
{
    public $modelClass = 'app\modules\api\models\Category';

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
            'query' => Category::getCategories(),
        ]);
    }

}