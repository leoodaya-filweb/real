<?php

// namespace app\modules\chat\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%space_notification}}`.
 */
class m221101_055543_create_space_notifications_table extends Migration
{
    public function tableName() 
    {
        return '{{%space_notifications}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName(), [
            'id' => $this->bigPrimaryKey(),
            'user_id' => $this->bigInteger(20)->notNull()->defaultValue(0),
            'space_id' => $this->bigInteger(20)->notNull()->defaultValue(0),
            'space_message_id' => $this->bigInteger(20)->notNull()->defaultValue(0),
            'state' => $this->tinyInteger(2)->defaultValue(0), //unread
            'token' => $this->string()->notNull()->unique(),
            'record_status' => $this->tinyInteger(2)->notNull()->defaultValue(1),
            'created_by' => $this->bigInteger(20)->notNull()->defaultValue(0),
            'updated_by' => $this->bigInteger(20)->notNull()->defaultValue(0),
            // 'created_at' => $this->datetime()->notNull(),
            // 'updated_at' => $this->timestamp()->notNull()
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP')
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

        $this->createIndex('user_id', $this->tableName(), 'user_id');
        $this->createIndex('space_id', $this->tableName(), 'space_id');
        $this->createIndex('space_message_id', $this->tableName(), 'space_message_id');
        $this->createIndex('created_by', $this->tableName(), 'created_by');
        $this->createIndex('updated_by', $this->tableName(), 'updated_by');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName());
    }
}
