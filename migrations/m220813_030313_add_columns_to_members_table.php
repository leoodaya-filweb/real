<?php

/**
 * Handles adding columns to table `{{%members}}`.
 */
class m220813_030313_add_columns_to_members_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%members}}';
    }

    public function columns()
    {
        return [
            'solo_member' => $this->tinyInteger(2)->notNull()->defaultValue(2),
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
