<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Location".
 *
 * @property integer $locationId
 * @property string $address
 * @property string $long
 * @property string $latitude
 * @property integer $offerId
 *
 * @property Offer $offer
 */
class Location extends \app\components\ActiveRecord
{
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Location';
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
            [['address', 'longtitude', 'latitude'], 'string', 'max' => 255],
            ['address', 'required'],
            ['address', 'unique'],
            ['active', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'locationId' => 'Location ID',
            'address' => 'Address',
            'longtitude' => 'Longtitude',
            'latitude' => 'Latitude',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferLocations()
    {
        return $this->hasMany(OfferLocation::className(), ['locationId' => 'locationId']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffer()
    {
        return $this->hasOne(Offer::className(), ['offerId' => 'offerId']);
    }
    
    /**
     * @param $offerId
     * @return $this
     */
    public static function getLocationByOfferId($offerId)
    {
        return static::find()->andWhere(['offerId' => $offerId]);
    }
    
    /**
     * @param bool $offerId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAllLocations($offerId = false)
    {
        $query = static::find();
        if ($offerId) {
            $query->innerJoin('OfferLocation', 'OfferLocation.locationId = Location.locationId')
                ->andWhere(['OfferLocation.offerId' => $offerId])
                ->andWhere(['OfferLocation.active' => 1]);
        }
        return $query->asArray()->all();
    }
    
    /**
     * @param $cityId
     * @return $this
     */
    public static function getLocationByCityId($cityId)
    {
        return static::find()->andWhere(['cityId' => $cityId]);
    }

    public function uniqueCoords()
    {
        return $this->find()
            ->andWhere(['longtitude' => $this->long])
            ->andWhere(['latitude' => $this->latitude])->exists();
    }
    
    /**
     * @return string
     */
    public static function setQuery()
    {
        return 'select
                    *
                from
                    Location
                        inner join
                    OfferLocation ON OfferLocation.locationId = Location.locationId
                where
                    OfferLocation.offerId = :offerId
                        and OfferLocation.active = :active
                        and Location.active = :activeLocation';
    }
    
    
    /**
     * @param bool $offerId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function drawTableByOfferId($offerId = false)
    {
        $query = static::find();
        if ($offerId) {
            $query->innerJoin('OfferLocation', 'OfferLocation.locationId = Location.locationId')
                ->andWhere(['OfferLocation.offerId' => $offerId])
                ->andWhere(['OfferLocation.active' => 1]);
        }
        
        $html = '<span>';
        $locations = $query->asArray()->all();
        foreach($locations as $location) {
            $html .= '<tr><td>'.$location['address'].'</td></tr>';
        }

        $html .= '</span>';
        return $html;
    }
    
    public static function drawTableForCity($id)
    {
        $html = '<span>';
        $locations = static::getLocationByCityId($id)->all();
        foreach($locations as $location) {
            $html .= '<tr><td>'.$location['address'].'</td></tr>';
        }

        $html .= '</span>';

        return $html;
    }
    
    public static function drawTable($id)
    {
        $html = '<table class="table table-bordered table-hover">';
        $locations = static::getLocationByOfferId($id)->all();
        foreach($locations as $location) {
            $html .= '<tr><td>'.$location['address'].'</td><td>'.$location['long'].'</td><td>'.$location['latitude'].'</td></tr>';
        }

        $html .= '</table>';

        return $html;
    }
}
