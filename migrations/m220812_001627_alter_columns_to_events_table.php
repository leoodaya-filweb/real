<?php

use yii\db\Migration;

/**
 * Class m220812_001627_alter_columns_to_events_table
 */
class m220812_001627_alter_columns_to_events_table extends Migration
{
    public function tableName()
    {
        return '{{%events}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName(), 'date_from', $this->date()->notNull());
        $this->alterColumn($this->tableName(), 'date_to', $this->date()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName(), 'date_from', $this->datetime()->notNull());
        $this->alterColumn($this->tableName(), 'date_to', $this->datetime()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220812_001627_alter_columns_to_events_table cannot be reverted.\n";

        return false;
    }
    */
}
