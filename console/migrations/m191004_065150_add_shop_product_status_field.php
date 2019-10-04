<?php

use yii\db\Migration;

/**
 * Class m191004_065150_add_shop_product_status_field
 */
class m191004_065150_add_shop_product_status_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_products}}', 'status', $this->smallInteger()->defaultValue(0)->notNull());
        $this->update('{{%shop_products}}', ['status' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_products}}', 'status');
    }
}
