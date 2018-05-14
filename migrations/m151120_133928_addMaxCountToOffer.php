<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_133928_addMaxCountToOffer extends Migration
{
    public function up()
    {
        $this->addColumn('Offer', 'maxCount', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('Offer', 'maxCount');
    }
}
