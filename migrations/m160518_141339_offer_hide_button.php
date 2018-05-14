<?php

use yii\db\Migration;

class m160518_141339_offer_hide_button extends Migration
{
    public function up()
    {

	$this->addColumn('Offer', 'hide_button', $this->boolean());

    }

    public function down()
    {

	$this->dropColumn('Offer', 'hide_button');
    }

}
