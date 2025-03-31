<?php

/**
 * Handles adding columns to table `{{%events}}`.
 */
class m220804_053942_add_columns_to_events_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%events}}';
    }

    public function columns()
    {
        return [
            'no_of_pensioner' => $this->integer()->notNull()->defaultValue(0),
            'social_pension_fund' => $this->tinyInteger(2)->notNull()->defaultValue(0),
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
