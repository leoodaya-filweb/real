<?php

/**
 * Handles adding columns to table `{{%event_members}}`.
 */
class m220815_003705_add_columns_to_event_members_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%event_members}}';
    }

    public function columns()
    {
        return [
            'solo_member' => $this->tinyInteger(2)->notNull()->defaultValue(2)->append('AFTER solo_parent'),
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
