<?php

namespace app\models;

use Yii;
use kartik\growl\Growl;
/**
 * This is the model class for table "Offer".
 *
 * @property integer $offerId
 * @property string $image1
 * @property string $image2
 * @property string $image3
 * @property integer $featured
 * @property integer $hide_button
 * @property integer $rank
 *
 * @property Location[] $locations
 * @property OfferType[] $offerTypes
 * @property OfferCity[] $offerCities
 * @property OfferLocation[] $offerLocations
 */
class Offer extends \app\components\ActiveRecord
{
    const STATUS_ACTIVE = 1;

    public $cities;
    public $types;
    public $locations;
    public $selected_locations;
    public $file1;
    public $file2;
    public $file3;

    public function beforeValidate()
    {
        if(parent::beforeValidate()){
            if( $this->scenario == 'create' ){
                $calc_exp_date = User::getExpiresDate();

                $current_date = Yii::$app->formatter->asTimestamp(date("Y-m-d"));
                $expires_date = Yii::$app->formatter->asTimestamp($calc_exp_date);
                
                if( $current_date >= $expires_date ){
                    Yii::$app->Notification->addAlert('Your paid period has expired, please pay for the use of our services, so that you can create new Offers.', Growl::TYPE_WARNING);
                    return false;
                }
            }
            return true;
        }
        
        return false;
    }

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
            [['maxCount'], 'required', 'on' => 'create'],
            [['featured', 'hide_button'], 'boolean'],
            [['maxCount', 'rank'], 'integer'],
            [['image1', 'image2', 'image3'], 'string'],
            [['cities', 'types', 'locations'], 'safe'],
            [['file1', 'file2', 'file3'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 5, 'tooBig' => 'The file is too big. Image size shouldn\'t exceed 5MB'],
            ['cities', 'uniqueCities', 'on' => 'update'],
            // ['types', 'uniqueTypes', 'on' => 'update'],
            // ['locations', 'uniqueLocations', 'on' => 'update'],
            ['active', 'boolean'],
            ['description', 'string'],
            ['short_description', 'string'],
            [['from_date', 'to_date'], 'date', 'format' => 'Y-m-d'],
            [['from_time', 'to_time'], 'string'],
            ['days', 'safe']
        ];
    }

    public function uniqueCities($attribute, $params)
    {
        $citiesIds = OfferCity::offersCity(Yii::$app->request->get('id'));
        foreach($citiesIds as $cityId) {
            foreach($this->cities as $city) {
                if ($city == $cityId['cityId']) {
                    $this->addError($attribute, 'City exists');
                }
            }
        }
    }

    public function uniqueTypes($attribute, $params)
    {
        $typesId = OfferType::offersType(Yii::$app->request->get('id'));
        foreach($typesId as $typeId) {
            foreach($this->types as $type) {
                if ($type == $typeId['typeId']) {
                    $this->addError($attribute, 'Type exists');
                }
            }
        }
    }
    
    public function uniqueLocations($attribute, $params)
    {
        $locationsId = OfferLocation::offersLocation(Yii::$app->request->get('id'));
        foreach($locationsId as $locationId) {
            foreach($this->locations as $location) {
                if ($location == $locationId['locationId']) {
                    $this->addError($attribute, 'Location exists');
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'offerId' => 'Offer ID',
            'image1' => 'Featured Image',
            'image2' => 'Kupong Image',
            'image3' => 'Logo Image',
            'file1' => 'Upload Featured Image',
            'file2' => 'Upload QR Code',
            'file3' => 'Upload Logo Image',
            'featured' => 'Featured',
            'hide_button' => 'Hide "Use Coupon" button',
            'rank' => 'Rank',
            'file' => 'logo1',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'used' => 'Active'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['offerId' => 'offerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferLocations()
    {
        return $this->hasMany(OfferLocation::className(), ['offerId' => 'offerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferTypes()
    {
        return $this->hasMany(OfferType::className(), ['offerId' => 'offerId']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOffers()
    {
        return $this->hasMany(UserOffer::className(), ['offerId' => 'offerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferCities()
    {
        return $this->hasMany(OfferCity::className(), ['offerId' => 'offerId']);
    }
    

    /**
     * @return string
     */
    public static function getPictureFolder()
    {
        return Yii::getAlias('@app') . '/web/images/uploads/offers/';
    }

    public static function setQuery()
    {
        return 'select
                    *
                from
                    Offer
                        inner join
                    UserOffer ON UserOffer.offerId = Offer.offerId
                where
                    UserOffer.userId = :userId
                        and UserOffer.active = :active';
    }
}
