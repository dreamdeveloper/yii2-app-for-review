<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "OfferCity".
 *
 * @property integer $offerCityId
 * @property integer $cityId
 * @property integer $offerId
 *
 * @property Offer $offer
 * @property City $city
 */
class OfferCity extends \app\components\ActiveRecord
{
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'OfferCity';
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
            [['cityId', 'offerId'], 'integer'],
            ['active', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'offerCityId' => 'Offer City ID',
            'cityId' => 'City ID',
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
    public function getCity()
    {
        return $this->hasOne(City::className(), ['cityId' => 'cityId']);
    }

    /**
     * @param $offerId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function offersCity($offerId)
    {
        return static::find()->select('cityId')->andWhere(['offerId' => $offerId])->asArray()->all();
    }

    /**
     * @param $offerId
     * @return bool|null|string
     */
    public static function getCount($offerId)
    {
        return OfferCity::find()->where(['offerId' => $offerId])->count();
    }
}
