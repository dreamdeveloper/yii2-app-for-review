<?php

namespace app\modules\api\models;

use app\components\ActiveRecord;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "Offer".
 *
 * @property integer $offerId
 * @property string $image1
 * @property string $image2
 * @property string $image3
 * @property integer $featured
 * @property integer $maxCount
 * @property integer $active
 *
 * @property Location[] $locations
 * @property OfferCategory[] $offerCategories
 * @property OfferCity[] $offerCities
 */
class Offer extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Offer';
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return parent::find()->active();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['featured', 'maxCount', 'active', 'used'], 'integer'],
            [['image1', 'image2', 'image3'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'offerId' => 'Offer ID',
            'image1' => 'Image1',
            'image2' => 'Image2',
            'image3' => 'Image3',
            'featured' => 'Featured',
            'maxCount' => 'Max Count',
            'active' => 'Active',
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'offerId',
            'image1',
            'image2',
            'image3',
            'featured' => function ($model) {
                return ($model->featured) ? true : false;
            },
        ];
    }

    public static function incOfferUsed($offerId)
    {
        $offer = static::findOne(['offerId' => $offerId]);
        $offer->used++;
        if (!$offer->save()) {
            throw new Exception('Can\'t inc offer used');
        }

    }

}
