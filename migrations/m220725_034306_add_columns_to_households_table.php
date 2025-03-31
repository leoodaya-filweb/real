<?php

/**
 * Handles adding columns to table `{{%households}}`.
 */
class m220725_034306_add_columns_to_households_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%households}}';
    }

    public function columns()
    {
        return [
            'sitio' => $this->string(),
            'landmark' => $this->string(),
            'files' => $this->text(),
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
