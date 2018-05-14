<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "UserCity".
 *
 * @property integer $useCityId
 * @property integer $userId
 * @property integer $cityId
 *
 * @property User $user
 * @property City $city
 */
class UserCity extends \app\components\ActiveRecord
{
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'UserCity';
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
            [['cityId', 'userId'], 'integer'],
            ['active', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userCityId' => 'City User ID',
            'cityId' => 'City ID',
            'userId' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffer()
    {
        return $this->hasOne(UserCity::className(), ['cityId' => 'cityId']);
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
    public static function getCount($cityId)
    {
        return UserCity::find()->where(['cityId' => $cityId])->count();
    }
}
