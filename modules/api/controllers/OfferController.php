<?php

namespace app\modules\api\controllers;

use app\models\Type;
use app\models\Category;
use app\models\City;
use app\models\User;
use app\models\Location;
use app\models\OfferCity;
use app\models\Setting;
use app\modules\api\models\Client;
use app\modules\api\models\Offer;
use app\modules\api\models\OfferClient;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use Yii;
use app\components\rest\ActiveController;

class OfferController extends ActiveController
{
    public $modelClass = 'app\modules\api\models\Offer';
    public $folder;

    public function init()
    {
        $this->folder = Yii::$app->request->getHostInfo() . '/images/uploads/offers/';
    }

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

    /**
     * @param bool $featured
     * @param bool $cityId
     * @param bool $categoryId
     * @return ActiveDataProvider
     */
    public function actionIndex($featured = false, $locationId = null, $typeId = null, $from_date = null, $to_date = null, $days = null)
    {
        if ($featured == 'false' || $featured == '0') {
            $featured = false;
        } else {
            $featured = true;
        }
        if ($locationId == 'false' || $locationId == '0' || $locationId == 'null') {
            $locationId = false;
        }
        if ($typeId == 'false' || $typeId == '0' || $typeId == 'null') {
            $typeId = false;
        }
        $query = Offer::find()->select('Offer.offerId, Offer.description, Offer.short_description as shortDescription, Offer.image1, Offer.image2, Offer.image3, Offer.featured, Offer.hide_button, Offer.from_date, Offer.to_date, Offer.from_time, Offer.to_time, Offer.days');
        // if ($featured) {
        //     $query->andWhere(['Offer.featured' => $featured]);
        // }
        
        
        if( $from_date && !$to_date){
            $query->andWhere(['=', 'Offer.from_date', $from_date]);
        }
        
        if( !$from_date && $to_date ){
            $query->andWhere(['=', 'Offer.to_date', $to_date]);
        }
        
        if( $from_date && $to_date){
            $query->andFilterWhere(['like',"(date_format(Offer.from_date, '%Y-%m-%d' ))", $from_date])
            ->andFilterWhere(['like', "(date_format(Offer.to_date, '%Y-%m-%d' ))", $to_date]);
            // $query->andFilterWhere(['>=', 'Offer.from_date', $from_date]);
            // $query->andFilterWhere(['<=', 'Offer.to_date', $to_date]);
        }
        
        if( $from_time && $to_time){
            $query->andFilterWhere(['like',"(date_format(Offer.from_time, '%0:%0:%0' ))", $from_time])
            ->andFilterWhere(['like', "(date_format(Offer.to_time, '%0:%0:%0' ))", $to_time]);
            // $query->andFilterWhere(['>=', 'Offer.from_date', $from_date]);
            // $query->andFilterWhere(['<=', 'Offer.to_date', $to_date]);
        }
        
        if ($locationId) {
            $query->innerJoin('OfferLocation', 'Offer.offerId = OfferLocation.offerId')
                    ->innerJoin('Location', 'OfferLocation.locationId = Location.locationId')
                    ->andWhere(['OfferLocation.active' => 1])
                    ->andWhere(['Location.active' => Location::STATUS_ACTIVE])
                    ->andWhere(['OfferLocation.locationId' => $locationId]);
        }

	$query->innerJoin('OfferType', 'Offer.offerId = OfferType.offerId');
	$query->addSelect('OfferType.typeId');
        if ($typeId) {
            $query->innerJoin('Type', 'OfferType.typeId = Type.typeId')
                    ->andWhere(['OfferType.active' => 1])
                    ->andWhere(['Type.active' => Type::STATUS_ACTIVE])
                    ->andWhere(['OfferType.typeId' => $typeId]);
        }

	$query->orderBy(['Offer.rank' => SORT_ASC, 'Offer.offerId' => SORT_DESC]);

        $query = $query->asArray()->all();

        foreach($query as &$item) {
            $item['name'] = User::find()->select('name')->innerJoin('UserOffer', 'UserOffer.userId = user.id')
                    ->andWhere(['UserOffer.active' => 1])
                    ->andWhere(['UserOffer.offerId' => $item['offerId']])->asArray()->one()['name'];
                    
            $item['featured'] = ($item['featured'] == '1') ? true : false;
            $item['hide_button'] = ($item['hide_button'] == '1') ? true : false;
            $item['image1']  = Yii::$app->request->getHostInfo() . '/images/uploads/offers/'.$item['image1'];
            $item['image2']  = Yii::$app->request->getHostInfo() . '/images/uploads/offers/'.$item['image2'];
            $item['image3']  = Yii::$app->request->getHostInfo() . '/images/uploads/users/' . \app\models\User::getLogoImage($item['offerId']);
            
            $item['radiusGlobal'] = Setting::findOne(['settingId' => Setting::GLOBAL_RADIUS_ID])['settingValue'];
            $item['locations'] = Location::find()->select('address, longtitude, latitude')->innerJoin('OfferLocation', 'OfferLocation.locationId = Location.locationId')->where(['OfferLocation.offerId' => $item['offerId'], 'OfferLocation.active' => 1])->asArray()->all();
	        if (!$item['locations']) {
	            $item['locations']= false;
        	}

        }

        return $query;
    }

    public function actionView($id, $phoneId = false)
    {
        if ($phoneId === 'null' || !$phoneId) {
            return [
                'massege' => 'phoneId is required parametr',
            ];
        }
        $result = []    ;
        $offer = Offer::find()
            ->andWhere(['Offer.offerId' => $id])
            ->asArray()->one();

        $used = OfferClient::getUsedByClient($id, $phoneId);

        if (!$offer) {
            return [
                'message' => "Offers not found",
            ];
        }
        $locations = Location::find()->select('address, long, latitude')->where(['offerId' => $id, 'active' => 1])->asArray()->all();
        if (!$locations) {
            $locations = false;
        }
        $result['offerId'] = $offer['offerId'];
        $result['image1'] = $this->folder.$offer['image1'];
        $result['image2'] = $this->folder.$offer['image2'];
        $result['image3'] = $this->folder.$offer['image3'];
        $result['featured'] = ($offer['featured']) ? true : false;
	$result['hide_button'] = ($offer['hide_button']) ? true : false;
        $result['locations'] = $locations;
        $result['available'] = ($offer['maxCount'] > $used) ? true : false;

        return $result;
    }

    public function actionCreateUser($id, $phoneId = false) // put
    {
        $offer = Offer::findOne(['offerId' => $id]);
        if (!$phoneId) {
            return [
                'code' => 3,
                'message' => 'phoneId is required parameter',
            ];
        }
        if (!$offer) {
            return [
                'code' => 2,
                'message' => 'Offers not found',
            ];
        }
        $maxCount = $offer['maxCount'];
        $used = OfferClient::getUsedByClient($id, $phoneId);
        if ($maxCount > $used) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $clientId = Client::clientExists($phoneId);
                if (!$clientId) {
                    $modelClient = new Client();
                    $modelClient->phone = $phoneId;
                    if (!$modelClient->save()) {
                        if (isset($modelClient->getErrors()['phone'][0])) {
                            throw new Exception($modelClient->getErrors()['phone'][0]);
                        }
                        throw new Exception('Client not saved');
                    }
                    $clientId = $modelClient->clientId;
                }

                if (!OfferClient::linkExists($id, $clientId)) {
                    $modelOfferClient = new OfferClient();
                    $modelOfferClient->offerId = $id;
                    $modelOfferClient->clientId = $clientId;
                    $modelOfferClient->count = 1;

                    if (!$modelOfferClient->save()) {
                        throw new Exception('Link not saved');
                    }
                } else {
                    OfferClient::incClientUsed($id, $phoneId);
                }
                Offer::incOfferUsed($id);
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return [
                    'message' => 'Something went wrong'
                ];
            }

        } else {
            return [
                'code' => 0,
                'message' => 'The proposal has already been used',
            ];
        }
        $used++;

        $diff = $maxCount - $used;

        if ($diff <= 0) {
            return [
                'code' => 4,
                'message' => 'Offer used, no more uses possible',
            ];
        } else {
            return [
                'code' => 1,
                'message' => 'Offer used, more uses possible',
            ];
        }
    }
}