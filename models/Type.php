<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Type".
 *
 * @property integer $typeId
 * @property string $name
 * @property integer $featured
 *
 * @property OfferType[] $offerTypes
 */
class Type extends \app\components\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Type';
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
            [['featured'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            ['active', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'typeId' => 'Type ID',
            'name' => 'Name',
            'featured' => 'Featured',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferTypes()
    {
        return $this->hasMany(OfferType::className(), ['typeId' => 'typeId']);
    }

    /**
     * @param bool $offerId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAllTypes($offerId = false)
    {
        $query = static::find();
        if ($offerId) {
            $query->innerJoin('OfferType', 'OfferType.typeId = Type.typeId')
                ->andWhere(['OfferType.offerId' => $offerId])
                ->andWhere(['OfferType.active' => 1]);
        }
        return $query->asArray()->all();
    }

    public static function setQuery()
    {
        return 'select
                    *
                from
                    Type
                        inner join
                    OfferType ON OfferType.typeId = Type.typeId
                where
                    OfferType.offerId = :offerId
                        and OfferType.active = :active
                        and Type.active = :activeType';
    }


    public static function drawTable($id)
    {
        $html = '<table class="table table-bordered table-hover">';
        $types = static::getAllTypes($id);
        foreach($types as $type) {
            $html .= '<tr><td>'.$type['name'].'</td></tr>';
        }

        $html .= '</table>';

        return $html;
    }
}
