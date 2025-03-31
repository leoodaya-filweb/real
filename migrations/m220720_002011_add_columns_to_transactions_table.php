<?php

/**
 * Handles adding columns to table `{{%transactions}}`.
 */
class m220720_002011_add_columns_to_transactions_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%transactions}}';
    }

    public function columns()
    {
        return [
            'patient_name' => $this->string(),
            'relation_to_patient' => $this->string(),
            'diagnosis' => $this->string(),
            'client_category' => $this->text(),
            'recommended_services_assistance' => $this->text(),
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
