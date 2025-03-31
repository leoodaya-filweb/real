<?php

/**
 * Handles adding columns to table `{{%event_members}}`.
 */
class m220901_081451_add_columns_to_event_members_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%event_members}}';
    }

    public function columns()
    {
        return [
            'priority_score' => $this->decimal(11, 4)->notNull()->defaultValue(0),
            'social_pensioner_id' => $this->bigInteger(20)->notNull()->defaultValue(0),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumns($this->tableName(), $this->columns());
        
        $this->createIndexes($this->tableName(), [
            'social_pensioner_id' => 'social_pensioner_id',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumns($this->tableName(), $this->columns());
    }
}
