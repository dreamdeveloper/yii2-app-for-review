<?php

namespace app\controllers;

// use app\models\OfferCity;
// use app\models\Location;
// use app\models\UserCity;

use Yii;
use app\models\Payment;
use app\models\User;
use app\models\Setting;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\base\ErrorException;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends Controller
{
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors('index', 'view', 'create', 'update', 'delete');
    }
    
    public function actionIndex(){
        
        $model = new Payment();
        $fee = Setting::findOne(['settingId' => Setting::FEE_AMOUNT_ID])['settingValue'];
        $get = Yii::$app->request->get();
        $userPayer = User::getUser();
        
        if(!empty($userPayer->payed_at)){
            $date_expires = date("Y-m-d", mktime(0, 0, 0, date("m", $userPayer->payed_at)+1, date("d", $userPayer->payed_at), date("Y", $userPayer->payed_at))); 
        }
        
        if(isset($get['paymentId'])){

            $response = Yii::$app->payPalRest->getResult($get['paymentId']);
            
            if(!isset($response['state'])){
                $this->redirect("payment");
            } elseif(isset($response) && isset($response['state'])){
                
                $model->paypalPaymentId = $response['id'];
                $model->userId = $userPayer->id;
                $model->emailPayment = $response['payer']['payer_info']['email'];
                $model->description =  $response['transactions'][0]['description'];
                $model->amount = $response['transactions'][0]['amount']['total'];
                $model->currency = $response['transactions'][0]['amount']['currency'];
                
                if($model->save()){
                    $userPayer->payed_at = Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s'));
                    $userPayer->update();
                }

                if($response['state'] == 'approved'){
                    $infoPayment = "Payment completed successfully. You can continue to use the services.";
                } else {
                    $infoPayment = "There was an error with payment, please, try again.";
                }
            }
        }
        
        return $this->render('index', [
            'model' => $model,
            'fee'   => $fee,
            'infoPayment' => $infoPayment,
            'date_expires' => $date_expires
        ]);
    }
    
    public function actionCreate(){
        
        $model = new Payment();
        $post = Yii::$app->request->post();
        
        $fee = Setting::findOne(['settingId' => Setting::FEE_AMOUNT_ID])['settingValue'];
        $userModel = User::findOne(['id' => Yii::$app->user->identity->id]);

        if( $post ){
            $params = [
                'currency' => 'USD',
                'description' => 'Fee for using the services',
                'total_price' => $fee,
                'email' => $userModel->email,
                'first_name' => $userModel->name,
                'items' => [
                    [
                        'name' => 'Fee for using the services',
                        'quantity' => 1,
                        'price' => $fee
                    ]
                ]
            ];
            
            $response = Yii::$app->payPalRest->getLinkCheckOut($params);
            $this->redirect($response['redirect_url']);
        }
    }
}