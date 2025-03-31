<?php

use yii\db\Migration;

/**
 * Class m220822_010427_alter_columns_to_database_table
 */
class m220822_010427_alter_columns_to_database_table extends Migration
{
    public function tableName()
    {
        return '{{%database}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName(), 'client_category', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName(), 'client_category', $this->string(32));
    }
}
