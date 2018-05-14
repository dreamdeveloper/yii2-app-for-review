<?php

namespace app\components;

use yii\base\Component;
use Yii;
use yii\base\InvalidConfigException;
use Mailgun\Mailgun;

class EmailComponent extends Component
{
    public $emailSend;
    public $sendAllEmailTo;
    public $domain;
    public $apiKey;
    public $emailFrom;
    private $_mailgun;

    public function init()
    {
        parent::init();
        if (!$this->domain) {
            throw new InvalidConfigException('The "domain" property must be set.');
        }
        if (!$this->apiKey) {
            throw new InvalidConfigException('The "apiKey" property must be set.');
        }
        if (!$this->emailFrom) {
            throw new InvalidConfigException('The "emailFrom" property must be set.');
        }

        //$this->_mailgun = new Mailgun($this->apiKey);
    }
    
    public function sendMessage($to, $subject, $content)
    {
        $response = $this->sendEmail($to, $subject, $content);

        //if($response->http_response_code == 200) {
        if($response) {
            return true;
        }

        return false;
    }

    public function sendEmail($to, $subject, $content)
    {

        return Yii::$app->mailer->compose()
	    ->setFrom($this->emailFrom, "Studentpakken")
	    ->setTo($to)
	    ->setReplyTo(Yii::$app->params['adminEmail'])
	    ->setSubject($subject)
	    //->setTextBody('Plain text content')
	    ->setHtmlBody($content)
	    ->send();



	    /*
        return $this->_mailgun->sendMessage($this->domain, [
            'from' => "Studentpakken <{$this->emailFrom}>",
            'to' => $to,
            'subject' => $subject,
            'text' => $content,
            'h:Reply-To' => Yii::$app->params['adminEmail'],
        ]);*/
    }
}