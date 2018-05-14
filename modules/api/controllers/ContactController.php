<?php

namespace app\modules\api\controllers;

use Yii;
use app\components\rest\ActiveController;
use app\models\Setting;

class ContactController extends ActiveController
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

    public function actionEmail($email, $name, $message)
    {
    	/*$email = Yii::$app->request->post('email'); 
    	$name = Yii::$app->request->post('name');
    	$message = Yii::$app->request->post('message');
    	*/
        $adminEmail = Setting::getAdminEmail();
        if (!$adminEmail) {

            return [
                'message' => 'Admin\'s email absent',
            ];
        }
        if (!$email) {

            return [
                'message' => "Email is required parameter",
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'message' => "This ({$email}) email address is invalid.",
            ];
        }
        $subject = 'Contact request from ' . $name;
        $content = "You have a request from {$name}.<br>  Contact email {$email}<br>{$message}";

        if (\Yii::$app->Email->sendMessage($adminEmail, $subject, $content)) {

            return [
                'message' => 'Takk for din melding',
            ];
        } else {

            return [
                'message' => 'Message was not sent.',
            ];
        }
    }

}