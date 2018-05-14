<?php

use yii\db\Schema;
use yii\db\Migration;

class m151210_100256_addSetting extends Migration
{
    public function up()
    {
        $this->createTable('Setting', [
            'settingId' => $this->primaryKey(),
            'settingName' => $this->string(),
            'settingValue' => $this->string(),
        ]);

        $this->batchInsert('Setting', ['settingId', 'settingName', 'settingValue'], [
            [1, 'adminEmail', '' ],
        ]);
    }

    public function down()
    {
        $this->dropTable('Setting');
    }
}
