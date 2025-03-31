<?php

use yii\db\Migration;

/**
 * Class m220725_034011_alter_columns_to_households_table
 */
class m220725_034011_alter_columns_to_households_table extends Migration
{

    public function tableName()
    {
        return '{{%households}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName(), 'purok_no', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName(), 'purok_no', $this->integer());
    }
}
