<?php
namespace app\components;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;

class Controller extends \yii\web\Controller
{
    public function behaviors()
    {
        $actions = ['delete' => ['post']];
        $only = ['index'];
        $params = func_get_args();

        foreach($params as $action) {
            if (is_array($action)) {
                $only[] = $action[0];
                $actions[$action[0]] = ['post'];
            } else {
                $only[] = $action;
            }
        }

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => $actions
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => $only,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}