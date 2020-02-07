<?php

use yii\db\Migration;

/**
 * Class m191018_073700_alter_tables_comments_count
 */
class m191018_073700_alter_tables_comments_count extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%blog_posts}}', 'comments_count', $this->integer()->defaultValue(0));
        $this->dropColumn('{{%blog_comments}}', 'comments_count');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%blog_posts}}', 'comments_count');
        $this->addColumn('{{%blog_comments}}', 'comments_count', $this->integer()->defaultValue(0));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191018_073700_alter_tables_comments_count cannot be reverted.\n";

        return false;
    }
    */
}
