<?php

/**
 * Handles adding columns to table `{{%transactions}}`.
 */
class m220714_064954_add_columns_to_transactions_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%transactions}}';
    }

    public function columns()
    {
        return [
            'claimant' => $this->string()->null(),
            'id_of_deceased' => $this->bigInteger()->notNull()->defaultValue(0),
            'name_of_deceased' => $this->string()->null(),
            'caused_of_death' => $this->text(),
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
        
        $this->createIndexes($this->tableName(), [
            'id_of_deceased' => 'id_of_deceased',
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
