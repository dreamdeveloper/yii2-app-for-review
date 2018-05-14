<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "UserOffer".
 *
 * @property integer $userOfferId
 * @property integer $offerId
 * @property integer $userId
 *
 * @property Offer $offer
 * @property User $user
 */
class UserOffer extends \app\components\ActiveRecord
{
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'UserOffer';
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
            [['userId', 'offerId'], 'integer'],
            ['active', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userOfferId' => 'User Offer ID',
            'userId' => 'User ID',
            'offerId' => 'Offer ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffer()
    {
        return $this->hasOne(UserOffer::className(), ['offerId' => 'offerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    /**
     * @param $offerId
     * @return array|\yii\db\ActiveRecord[]
     */
    // public static function offersType($offerId)
    // {
    //     return static::find()->select('typeId')->andWhere(['offerId' => $offerId])->asArray()->all();
    // }

    /**
     * @param $offerId
     * @return bool|null|string
     */
    public static function getCount($userId)
    {
        return UserOffer::find()->where(['userId' => $userId])->count();
    }
}
