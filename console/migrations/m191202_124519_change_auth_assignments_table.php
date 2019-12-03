<?php

use yii\db\Migration;

/**
 * Class m191202_124519_change_auth_assignments_table
 */
class m191202_124519_change_auth_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('ALTER TABLE auth_assignments ALTER COLUMN user_id TYPE integer USING (user_id::integer)');
        $this->createIndex('{{%idx-auth_assignments-user_id}}', '{{%auth_assignments}}', 'user_id');
//        $this->addForeignKey('{{%fk-auth_assignments-user_id}}', '{{%auth_assignments}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        $this->dropForeignKey('{{%fk-auth_assignments-user_id}}', '{{%auth_assignments}}');
        $this->dropIndex('{{%idx-auth_assignments-user_id}}', '{{%auth_assignments}}');
        $this->alterColumn('{{%auth_assignments}}', 'user_id', $this->string(64));
    }
}
