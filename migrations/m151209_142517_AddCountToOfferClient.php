<?php

use yii\db\Schema;
use yii\db\Migration;

class m151209_142517_AddCountToOfferClient extends Migration
{
    public function up()
    {
        $this->addColumn('OfferClient', 'count', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('OfferClient', 'count');
    }
}
