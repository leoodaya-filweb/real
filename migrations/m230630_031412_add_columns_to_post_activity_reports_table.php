<?php

/**
 * Handles adding columns to table `{{%post_activity_reports}}`.
 */
class m230630_031412_add_columns_to_post_activity_reports_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%post_activity_reports}}';
    }

    public function columns()
    {
        return [
            'prepared_by_position' => $this->string()->notNull(),
            'noted_by_position' => $this->string()->notNull(),
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
        
        // $this->createIndexes($this->tableName(), [
        //     'created_by' => 'created_by',
        //     'updated_by' => 'updated_by',
        // ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumns($this->tableName(), $this->columns());
    }
}
