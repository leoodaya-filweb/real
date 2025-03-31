<?php

/**
 * Handles adding columns to table `{{%database}}`.
 */
class m220817_022937_add_columns_to_database_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%database}}';
    }

    public function columns()
    {
        return [
            'purok' => $this->string(),
            'sitio' => $this->string(),
            'landmark' => $this->text(),
            'other_contact_no' => $this->string(),
            'other_income_source_amount' => $this->decimal(11, 2),
            'reasons' => $this->text(),
            'id_cards' => $this->text(),
            'documents' => $this->text(),
            'sogie' => $this->string(),
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
