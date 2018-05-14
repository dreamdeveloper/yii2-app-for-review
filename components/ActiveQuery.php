<?php

namespace app\components;

use Yii;

class ActiveQuery extends \yii\db\ActiveQuery
{
    public function active($state = 1)
    {
        $modelClass = $this->modelClass;
        $tableName = $modelClass::tableName();
        $this->andWhere(['`'.$tableName.'`.active' => $state]);
        return $this;
    }
}