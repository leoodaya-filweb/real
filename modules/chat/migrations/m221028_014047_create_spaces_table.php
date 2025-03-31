<?php

// namespace app\modules\chat\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%space}}`.
 */
class m221028_014047_create_spaces_table extends Migration
{
    public function tableName() 
    {
        return '{{%spaces}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName(), [
            'id' => $this->bigPrimaryKey(),
            'name' => $this->string()->notNull(),
            'type' => $this->tinyInteger(2)->defaultValue(1),
            'is_block' => $this->tinyInteger(2)->defaultValue(0),
            'is_block_by' => $this->bigInteger(20)->defaultValue(0),
            'token' => $this->string()->notNull()->unique(),
            'photo' => $this->string(),
            'user_id' => $this->bigInteger(20)->notNull()->defaultValue(0),
            'record_status' => $this->tinyInteger(2)->notNull()->defaultValue(1),
            'created_by' => $this->bigInteger(20)->notNull()->defaultValue(0),
            'updated_by' => $this->bigInteger(20)->notNull()->defaultValue(0),
            // 'created_at' => $this->datetime()->notNull(),
            // 'updated_at' => $this->timestamp()->notNull()
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP')
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

        $this->createIndex('is_block_by', $this->tableName(), 'is_block_by');
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
