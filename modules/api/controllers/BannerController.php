<?php

namespace app\modules\api\controllers;

use app\components\rest\ActiveController;
use app\modules\api\models\Banner;

class BannerController extends ActiveController
{
    public $modelClass = 'app\modules\api\models\Banner';

    public function behaviors()
    {
        return parent::behaviors();
    }

    public function actions()
    {
        $artions = parent::actions();
        unset($artions['index']);
        return $artions;
    }

    public function actionIndex()
    {
        $model = Banner::findOne(['id' => 1]);

        return [
            'image' => \Yii::$app->request->getHostInfo() . $model->image,
            'url' => $model->url,
        ];
    }
}