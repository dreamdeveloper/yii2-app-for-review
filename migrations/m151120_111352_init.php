<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_111352_init extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING . ' NOT NULL',

            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);

        $this->createTable('Category', [
            'categoryId' => $this->primaryKey(),
            'name' => $this->string(),
            'featured' => $this->boolean(),
        ]);

        $this->createTable('City', [
            'cityId' => $this->primaryKey(),
            'name' => $this->string(),
            'featured' => $this->boolean(),
        ]);

        $this->createTable('Location', [
            'locationId' => $this->primaryKey(),
            'address' => $this->string(),
            'long' => $this->string(),
            'latitude' => $this->string(),
            'offerId' => $this->integer(),
        ]);

        $this->createTable('Offer', [
            'offerId' => $this->primaryKey(),
            'image1' => $this->string(),
            'image2' => $this->string(),
            'image3' => $this->string(),
            'featured' => $this->boolean(),
        ]);

        $this->createTable('OfferCity', [
            'offerCityId' => $this->primaryKey(),
            'cityId' => $this->integer(),
            'offerId' => $this->integer(),
        ]);

        $this->createTable('OfferCategory', [
            'offerCategoryId' => $this->primaryKey(),
            'categoryId' => $this->integer(),
            'offerId' => $this->integer(),
        ]);

        $this->createTable('Banner', [
            'id' => $this->primaryKey(),
            'image' => $this->string(),
            'url' => $this->string(),
        ]);

        $this->addForeignKey('OfferCategoryToCategory', 'OfferCategory', 'categoryId', 'Category', 'categoryId');
        $this->addForeignKey('OfferCategoryToOffer', 'OfferCategory', 'offerId', 'Offer', 'offerId');

        $this->addForeignKey('OfferCityToCity', 'OfferCity', 'cityId', 'City', 'CityId');
        $this->addForeignKey('OfferCityToOffer', 'OfferCity', 'offerId', 'Offer', 'offerId');
        $this->addForeignKey('OfferToLocation', 'Location', 'offerId', 'Offer', 'offerId');

        $this->batchInsert('Banner', ['id', 'image', 'url'], [
            [1, '', ''],
        ]);

        $this->batchInsert('user', ['id', 'username', 'auth_key', 'password_hash', 'status', 'created_at', 'updated_at'], [
            [1, 'admin', 'X_gvlVu7BmXGKBKtA49jgHXoqmG_dc8J', '$2y$13$2oC4QVOMYhueYfFbEdcVdO9e1B6mkGybvTcoGPmCjh77PAsspco1S', 10, time(), time()],

        ]);
    }

    public function down()
    {
        $this->dropForeignKey('OfferCategoryToCategory', 'OfferCategory');
        $this->dropForeignKey('OfferCategoryToOffer', 'OfferCategory');
        $this->dropForeignKey('OfferCityToCity', 'OfferCity');
        $this->dropForeignKey('OfferCityToOffer', 'OfferCity');
        $this->dropForeignKey('OfferToLocation', 'Location');
        $this->dropTable('user');
        $this->dropTable('Category');
        $this->dropTable('City');
        $this->dropTable('Location');
        $this->dropTable('Offer');
        $this->dropTable('OfferCity');
        $this->dropTable('OfferCategory');
        $this->dropTable('Banner');
    }
}
