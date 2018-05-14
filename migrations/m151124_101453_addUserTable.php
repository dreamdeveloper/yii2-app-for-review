<?php

use yii\db\Schema;
use yii\db\Migration;

class m151124_101453_addUserTable extends Migration
{
    public function up()
    {
        $this->createTable('Client', [
            'clientId' => $this->primaryKey(),
            'phone' => $this->string(),
        ]);

        $this->createTable('OfferClient', [
            'offerClientId' => $this->primaryKey(),
            'offerId' => $this->integer(),
            'clientId' => $this->integer(),
        ]);

        $this->addForeignKey('OfferClientToClient', 'OfferClient', 'clientId', 'Client', 'clientId');
        $this->addForeignKey('OfferClientToOffer', 'OfferClient', 'offerId', 'Offer', 'offerId');
    }

    public function down()
    {
        $this->dropForeignKey('OfferClientToClient', 'OfferClient');
        $this->dropForeignKey('OfferClientToOffer', 'OfferClient');
        $this->dropTable('Client');
        $this->dropTable('OfferClient');
    }
}
