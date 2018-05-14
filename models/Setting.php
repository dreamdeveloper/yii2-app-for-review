<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Setting".
 *
 * @property integer $settingId
 * @property string $settingName
 * @property string $settingValue
 */
class Setting extends \yii\db\ActiveRecord
{
    const EMAIL_ID = 1;
    const GLOBAL_RADIUS_ID = 2;
    const FEE_AMOUNT_ID = 3;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['settingName', 'settingValue'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'settingId' => 'Setting ID',
            'settingName' => 'Setting Name',
            'settingValue' => 'Setting Value',
        ];
    }

    /**
     * @return bool
     */
    public static function getAdminEmail()
    {
        $email = static::findOne(['settingId' => self::EMAIL_ID]);
        if ($email) {
            return $email['settingValue'];
        }

        return false;
    }
}
