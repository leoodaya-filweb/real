<?php

/**
 * Handles adding columns to table `{{%members}}`.
 */
class m220720_053311_add_columns_to_members_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%members}}';
    }

    public function columns()
    {
        return [
            'skills' => $this->text(),
            'fourPs' => $this->tinyInteger(2)->notNull()->defaultValue(0)
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
