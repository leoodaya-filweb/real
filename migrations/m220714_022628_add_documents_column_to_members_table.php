<?php

/**
 * Handles adding columns to table `{{%members}}`.
 */
class m220714_022628_add_documents_column_to_members_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%members}}';
    }

    public function columns()
    {
        return [
            'documents' => $this->text(),
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
