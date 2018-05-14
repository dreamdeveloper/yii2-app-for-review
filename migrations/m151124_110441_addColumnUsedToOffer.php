<?php

use yii\db\Schema;
use yii\db\Migration;

class m151124_110441_addColumnUsedToOffer extends Migration
{
    public function up()
    {
        $this->addColumn('Offer', 'used', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('Offer', 'used');
    }
}
