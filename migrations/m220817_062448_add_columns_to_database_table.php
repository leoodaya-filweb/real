<?php

/**
 * Handles adding columns to table `{{%database}}`.
 */
class m220817_062448_add_columns_to_database_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%database}}';
    }

    public function columns()
    {
        return [
            'email' => $this->string(),
        ];

        // FOR SETTING utf
        // ->append('CHARACTER SET utf8 COLLATE utf8_general_ci')
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumns($this->tableName(), $this->columns());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumns($this->tableName(), $this->columns());
    }
}
