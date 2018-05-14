<?php

namespace app\modules\api\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "OfferClient".
 *
 * @property integer $offerClientId
 * @property integer $offerId
 * @property integer $clientId
 *
 * @property Offer $offer
 * @property Client $client
 */
class OfferClient extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'OfferClient';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['offerId', 'clientId', 'count'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'offerClientId' => 'Offer Client ID',
            'offerId' => 'Offer ID',
            'clientId' => 'Client ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffer()
    {
        return $this->hasOne(Offer::className(), ['offerId' => 'offerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['clientId' => 'clientId']);
    }

    /**
     * @param $id
     * @param $clientId
     * @return bool
     */
    public static function linkExists($id, $clientId)
    {
        return static::find()
            ->where(['offerId' => $id])
            ->andWhere(['clientId' => $clientId])
            ->exists();
    }

    public static function incClientUsed($offerId, $phone)
    {
        $res = static::find()
            ->select('offerClientId')
            ->innerJoin('Client', 'OfferClient.clientId = Client.clientId')
            ->andWhere(['phone' => $phone])
            ->andWhere(['offerId' => $offerId])
            ->asArray()->all();

        $id = (isset($res[0]['offerClientId'])) ? $res[0]['offerClientId'] : false;

        if ($id) {
            $model = static::findOne(['offerClientId' => $id]);
            $count = (int)$model->count;
            $count++;
            $model->count = $count;
            if (!$model->save(false)) {
                throw new Exception('Can\'t update');
            }
        }
    }

    public static function getUsedByClient($offerId, $phoneId)
    {
        $res = static::find()
            ->innerJoin('Client', 'OfferClient.clientId = Client.clientId')
            ->andWhere(['Client.phone' => $phoneId])
            ->andWhere(['OfferClient.offerId' => $offerId])
            ->one();

        if (!$res) {
            return 0;
        } else {

            return $res['count'];
        }
    }
}
