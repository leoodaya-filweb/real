<?php

/**
 * Handles adding columns to table `{{%event_members}}`.
 */
class m220902_020350_add_columns_to_event_members_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%event_members}}';
    }

    public function columns()
    {
        return [
            'pwd_score' => $this->decimal(11, 4)->notNull()->defaultValue(0),
            'senior_score' => $this->decimal(11, 4)->notNull()->defaultValue(0),
            'solo_parent_score' => $this->decimal(11, 4)->notNull()->defaultValue(0),
            'solo_member_score' => $this->decimal(11, 4)->notNull()->defaultValue(0),
            'accessibility_score' => $this->decimal(11, 4)->notNull()->defaultValue(0),
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
