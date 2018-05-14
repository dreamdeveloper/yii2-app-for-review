<?php
namespace app\components;

use yii\base\Component;

class NotificationComponent extends Component {

    public function addAlert($message, $type, $url = false, $title = 'Notification')
    {
        \Yii::$app->session->setFlash('alert', [
            'state' => true,
            'message' => $message,
            'title' => $title,
            'type' => $type,
            'url' => $url,
        ]);
    }
}