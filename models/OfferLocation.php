<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "OfferLocation".
 *
 * @property integer $offerLocationId
 * @property integer $locationId
 * @property integer $offerId
 *
 * @property Offer $offer
 * @property Location $location
 */
class OfferLocation extends \app\components\ActiveRecord
{
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'OfferLocation';
    }

    /**
     * @inheritdoc
     */
    public static function find($state = 1)
    {
        return parent::find()->active($state);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['locationId', 'offerId'], 'integer'],
            ['active', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'offerLocationId' => 'Offer Location ID',
            'locationId' => 'Location ID',
            'offerId' => 'Offer ID',
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
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['locationId' => 'locationId']);
    }

    /**
     * @param $offerId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function offersLocation($offerId)
    {
        return static::find()->select('locationId')->andWhere(['offerId' => $offerId])->asArray()->all();
    }
    
    /**
     * @param $offerId
     * @return bool|null|string
     */
    public static function getCount($offerId)
    {
        return OfferLocation::find()->where(['offerId' => $offerId])->count();
    }
}
