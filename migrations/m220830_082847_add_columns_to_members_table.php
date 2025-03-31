<?php

/**
 * Handles adding columns to table `{{%members}}`.
 */
class m220830_082847_add_columns_to_members_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%members}}';
    }

    public function columns()
    {
        return [
            'age' => $this->integer()->notNull()->defaultValue(0),
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
