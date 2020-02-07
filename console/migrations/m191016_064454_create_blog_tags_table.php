<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_tags}}`.
 */
class m191016_064454_create_blog_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%blog_tags}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
        ]);

        $this->createIndex('{{%idx-blog_tags-slug}}', '{{%blog_tags}}', 'slug', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_tags}}');
    }
}
