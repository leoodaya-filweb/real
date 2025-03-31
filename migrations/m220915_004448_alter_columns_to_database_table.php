<?php

use yii\db\Migration;

/**
 * Class m220915_004448_alter_columns_to_database_table
 */
class m220915_004448_alter_columns_to_database_table extends Migration
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
        $this->alterColumn($this->tableName(), 'signature', $this->text());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName(), 'signature', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220915_004448_alter_columns_to_database_table cannot be reverted.\n";

        return false;
    }
    */
}
