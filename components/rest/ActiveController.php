<?php
namespace app\components\rest;

use yii\web\Response;

class ActiveController extends \yii\rest\ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete'], $actions['update'], $actions['create'], $actions['options'], $actions['view']);
        return $actions;
    }
}