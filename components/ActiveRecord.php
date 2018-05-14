<?php

namespace app\components;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\components\ActiveQuery;

class ActiveRecord extends \yii\db\ActiveRecord 
{

    /**
     * @inheritdoc
     * @return ActiveQuery
     */
    public static function find()
    {
        return new ActiveQuery(get_called_class());
    }

    /**
     * This delete don't remove table rows from DB tables and only 
     * update them if there if 'active' field available
     */
    public static function deleteAll($condition = '', $params = []) {
        $modelName = self::className();
        $model = new $modelName;
        if($model->hasAttribute('active')) {
            $command = static::getDb()->createCommand();
            $command->update(static::tableName(), ['active' => 0], $condition, $params);
            return $command->execute();
        }

        return parent::deleteAll($condition = '', $params = []);
    }

}