<?php

/**
 * Handles adding columns to table `{{%medicines}}`.
 */
class m220801_002832_add_columns_to_medicines_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%medicines}}';
    }

    public function columns()
    {
        return [
            'quantity' => $this->integer()->notNull()->defaultValue(0),
            'unit' => $this->string(),
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
