<?php

use yii\db\Migration;

/**
 * Class m191011_093139_add_shop_product_fields
 */
class m191011_093139_add_shop_product_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_products}}', 'weight', $this->integer());
        $this->addColumn('{{%shop_products}}', 'quantity', $this->integer());
        $this->addColumn('{{%shop_modifications}}', 'quantity', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_modifications}}', 'quantity');
        $this->dropColumn('{{%shop_products}}', 'quantity');
        $this->dropColumn('{{%shop_products}}', 'weight');
    }

}
