<?php

use yii\db\Migration;

/**
 * Class m220801_031416_alter_columns_to_transactions_table
 */
class m220801_031416_alter_columns_to_transactions_table extends Migration
{
    public function tableName()
    {
        return '{{%transactions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName(), 'recommended_services_assistance', $this->tinyInteger(2)->notNull()->defaultValue(8));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName(), 'recommended_services_assistance', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220801_031416_alter_columns_to_transactions_table cannot be reverted.\n";

        return false;
    }
    */
}
