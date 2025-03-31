<?php

/**
 * Handles adding columns to table `{{%transactions}}`.
 */
class m220826_034917_add_columns_to_transactions_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%transactions}}';
    }

    public function columns()
    {
        return [
            'masterlist_status' => $this->tinyInteger(2)->notNull()->defaultValue(0),
        ];
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
