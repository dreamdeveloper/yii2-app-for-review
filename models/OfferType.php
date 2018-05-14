<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "OfferType".
 *
 * @property integer $offerTypeId
 * @property integer $typeId
 * @property integer $offerId
 *
 * @property Offer $offer
 * @property Type $type
 */
class OfferType extends \app\components\ActiveRecord
{
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'OfferType';
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
            [['typeId', 'offerId'], 'integer'],
            ['active', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'offerTypeId' => 'Offer Type ID',
            'typeId' => 'Type ID',
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
    public function getType()
    {
        return $this->hasOne(Type::className(), ['typeId' => 'typeId']);
    }

    /**
     * @param $offerId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function offersType($offerId)
    {
        return static::find()->select('typeId')->andWhere(['offerId' => $offerId])->asArray()->all();
    }

    /**
     * @param $offerId
     * @return bool|null|string
     */
    public static function getCount($offerId)
    {
        return OfferType::find()->where(['offerId' => $offerId])->count();
    }
}
