<?php

use yii\db\Migration;

/**
 * Class m220817_023733_alter_columns_from_database_table
 */
class m220817_023733_alter_columns_from_database_table extends Migration
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
        $this->alterColumn($this->tableName(), 'skills', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName(), 'skills', $this->string(32));
    }
}
