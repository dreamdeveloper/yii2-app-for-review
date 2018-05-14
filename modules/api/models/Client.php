<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "Client".
 *
 * @property integer $clientId
 * @property string $phone
 *
 * @property OfferClient[] $offerClients
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone'], 'string', 'max' => 255],
            [['phone'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'clientId' => 'Client ID',
            'phone' => 'Phone',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferClients()
    {
        return $this->hasMany(OfferClient::className(), ['clientId' => 'clientId']);
    }

    /**
     * @param $phoneId
     * @return bool|mixed
     */
    public static function clientExists($phoneId)
    {
        $client = static::find()->where(['phone' => $phoneId])->one();
        if ($client) {
            return $client['clientId'];
        }

        return false;
    }
}
