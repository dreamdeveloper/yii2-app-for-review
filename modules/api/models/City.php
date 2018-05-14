<?php

namespace app\modules\api\models;

use app\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "City".
 *
 * @property integer $cityId
 * @property string $name
 * @property integer $featured
 * @property integer $active
 *
 * @property OfferCity[] $offerCities
 */
class City extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'City';
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
    public function fields()
    {
        return [
            'cityId',
            'name',
            'featured' => function ($model) {
                return ($model->featured) ? true : false;
            },
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['featured', 'active'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cityId' => 'City ID',
            'name' => 'Name',
            'featured' => 'Featured',
            'active' => 'Active',
        ];
    }

    /**
     * @return $this
     */
    public static function getCities()
    {
        return static::find()->orderBy('featured DESC, name');
    }
}
