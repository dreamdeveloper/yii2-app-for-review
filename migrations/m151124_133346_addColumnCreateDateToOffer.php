<?php

use yii\db\Schema;
use yii\db\Migration;

class m151124_133346_addColumnCreateDateToOffer extends Migration
{
    public function up()
    {
        $this->addColumn('Offer', 'created', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('Offer', 'created');
    }
}
