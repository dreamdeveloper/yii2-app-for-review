<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "City".
 *
 * @property integer $cityId
 * @property string $name
 * @property integer $featured
 *
 * @property OfferCity[] $offerCities
 */
class City extends \app\components\ActiveRecord
{
    const STATUS_ACTIVE = 1;
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
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['featured'], 'integer'],
            ['featured', 'default', 'value' => 0],
            [['name'], 'string', 'max' => 255],
            ['active', 'boolean'],
            [['name'], 'unique'],
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
            'cityId' => 'City ID',
            'name' => 'Name',
            'featured' => 'Featured',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferCities()
    {
        return $this->hasMany(OfferCity::className(), ['cityId' => 'cityId']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationsCities()
    {
        return $this->hasMany(Location::className(), ['cityId' => 'cityId']);
    }

    /**
     * @param bool $offerId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAllCities($offerId = false)
    {
        $query = static::find();
        if ($offerId) {
            $query->innerJoin('OfferCity', 'OfferCity.cityId = City.cityId')
                ->andWhere(['OfferCity.offerId' => $offerId])
                ->andWhere(['OfferCity.active' => 1]);
        }

        return $query->asArray()->all();
    }

    /**
     * @return string
     */
    public static function setQuery()
    {
        return 'select
                    *
                from
                    City
                        inner join
                    OfferCity ON OfferCity.cityId = City.cityId
                where
                    OfferCity.offerId = :offerId
                        and OfferCity.active = :active
                        and City.active = :activeCity';
    }

    public static function drawTable($id)
    {
        $html = '<table class="table table-bordered table-hover">';
        $cities = static::getAllCities($id);
        foreach($cities as $city) {
            $html .= '<tr><td>'.$city['name'].'</td></tr>';
        }

        $html .= '</table>';

        return $html;
    }
}
