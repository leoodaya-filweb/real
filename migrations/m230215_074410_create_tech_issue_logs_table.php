<?php

/**
 * Handles the creation of table `{{%tech_issue_logs}}`.
 */
class m230215_074410_create_tech_issue_logs_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%tech_issue_logs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName(), $this->attributes([
            'tech_issue_id' => $this->bigInteger(20)->notNull()->defaultValue(0),
            'status' => $this->tinyInteger(2)->notNull(),
            'remarks' => $this->text(),
            'attachments' => $this->text(),
        ]));

        $this->createIndexes($this->tableName(), [
            'tech_issue_id' => 'tech_issue_id',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName());
    }
}