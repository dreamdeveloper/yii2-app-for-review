<?php

use yii\db\Migration;
use yii\db\Expression;

class m160613_125804_offer_rank extends Migration
{
    public function up()
    {


	$this->addColumn('Offer', 'rank', $this->integer());
	$expr = new Expression('`id`');
	$this->update('Offer', ['rank' => $expr]);

    }

    public function down()
    {

	$this->dropColumn('Offer', 'rank');
    }
}
