<?php

use yii\db\Migration;

/**
 * Class m220827_113405_alter_columns_from_database_table
 */
class m220827_113405_alter_columns_from_database_table extends Migration
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
        $this->alterColumn($this->tableName(), 'age', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName(), 'age', $this->tinyInteger(3));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220827_113405_alter_columns_from_database_table cannot be reverted.\n";

        return false;
    }
    */
}
