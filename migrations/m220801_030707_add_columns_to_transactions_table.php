<?php

/**
 * Handles adding columns to table `{{%transactions}}`.
 */
class m220801_030707_add_columns_to_transactions_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%transactions}}';
    }

    public function columns()
    {
        return [
            'referral_to' => $this->string(),
            'other_rsa' => $this->text(),
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
