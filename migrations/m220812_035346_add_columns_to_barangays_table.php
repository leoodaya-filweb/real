<?php

/**
 * Handles adding columns to table `{{%barangays}}`.
 */
class m220812_035346_add_columns_to_barangays_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%barangays}}';
    }

    public function columns()
    {
        return [
            'priority_score' => $this->decimal(11, 2)->notNull()->defaultValue(0),
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
