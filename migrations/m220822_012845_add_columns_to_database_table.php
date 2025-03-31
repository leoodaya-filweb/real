<?php

/**
 * Handles adding columns to table `{{%database}}`.
 */
class m220822_012845_add_columns_to_database_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%database}}';
    }

    public function columns()
    {
        return [
            'fathers_name' => $this->string(),
            'mothers_name' => $this->string(),
            'school_name_last_attended' => $this->string(),
            'school_year_last_attended' => $this->smallInteger(6),
            'interests' => $this->text(),
            'work_experience' => $this->text(),
            'organizations' => $this->text(),
            'organization_name' => $this->string(),
            'position' => $this->string(),
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
