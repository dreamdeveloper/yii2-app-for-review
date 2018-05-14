<?php

use yii\db\Schema;
use yii\db\Migration;

class m151124_055712_addActiveForAllTables extends Migration
{
    public function up()
    {
        $this->addColumn('Category', 'active', $this->boolean()->defaultValue(1));
        $this->addColumn('City', 'active', $this->boolean()->defaultValue(1));
        $this->addColumn('Location', 'active', $this->boolean()->defaultValue(1));
        $this->addColumn('Offer', 'active', $this->boolean()->defaultValue(1));
        $this->addColumn('OfferCategory', 'active', $this->boolean()->defaultValue(1));
        $this->addColumn('OfferCity', 'active', $this->boolean()->defaultValue(1));
    }

    public function down()
    {
        $this->dropColumn('Category', 'active');
        $this->dropColumn('City', 'active');
        $this->dropColumn('Location', 'active');
        $this->dropColumn('Offer', 'active');
        $this->dropColumn('OfferCategory', 'active');
        $this->dropColumn('OfferCity', 'active');
    }
}
