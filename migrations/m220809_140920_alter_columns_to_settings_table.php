<?php

use yii\db\Migration;

/**
 * Class m220809_140920_alter_columns_to_settings_table
 */
class m220809_140920_alter_columns_to_settings_table extends Migration
{

    public function tableName()
    {
        return '{{%settings}}';
    }
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName(), 'value', $this->getDb()->getSchema()->createColumnSchemaBuilder('mediumtext'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName(), 'value', $this->text());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220809_140920_alter_columns_to_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
