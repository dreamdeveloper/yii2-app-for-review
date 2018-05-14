<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "Payment".
 *
 * @property integer $paymentId
 * @property string $description
 *
 */
class Payment extends \app\components\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Payment';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => false,
                'value' => Yii::$app->formatter->asTimestamp(date('Y-d-m')),
            ],
        ];   
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['name'], 'required'],
            // [['featured'], 'integer'],
            // ['featured', 'default', 'value' => 0],
            // [['name'], 'string', 'max' => 255],
            // ['active', 'boolean'],
        ];
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
    public function attributeLabels()
    {
        return [
            'paymentId' => 'Payment ID',
            'description' => 'Description',
        ];
    }
}
