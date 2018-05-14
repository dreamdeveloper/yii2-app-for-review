<?php

namespace app\modules\api\models;

use app\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "Category".
 *
 * @property integer $categoryId
 * @property string $name
 * @property integer $featured
 * @property integer $active
 *
 * @property OfferCategory[] $offerCategories
 */
class Category extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Category';
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
            [['featured', 'active'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'categoryId',
            'name',
            'featured' => function ($model) {
                return ($model->featured) ? true : false;
            },
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'categoryId' => 'Category ID',
            'name' => 'Name',
            'featured' => 'Featured',
            'active' => 'Active',
        ];
    }

    /**
     * @return $this
     */
    public static function getCategories()
    {
        return static::find()->orderBy('featured DESC, name');
    }

}
