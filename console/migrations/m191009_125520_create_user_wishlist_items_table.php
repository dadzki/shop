<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_wishlist_items}}`.
 */
class m191009_125520_create_user_wishlist_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_wishlist_items}}', [
            'user_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('{{%pk-user_wishlist_items}}', '{{%user_wishlist_items}}', ['user_id', 'product_id']);

        $this->createIndex('{{%idx-user_wishlist_items-user_id}}', '{{%user_wishlist_items}}', 'user_id');
        $this->createIndex('{{%idx-user_wishlist_items-product_id}}', '{{%user_wishlist_items}}', 'product_id');

        $this->addForeignKey('{{%fk-user_wishlist_items-user_id}}', '{{%user_wishlist_items}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-user_wishlist_items-product_id}}', '{{%user_wishlist_items}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_wishlist_items}}');
    }
}
