<?php

/**
 * Handles the creation of table `{{%post_activity_reports}}`.
 */
class m230630_005528_create_post_activity_reports_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%post_activity_reports}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName(), $this->attributes([
            'date' => $this->date()->notNull(),
            'for' => $this->string()->notNull(),
            'subject' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'location' => $this->text(),
            'date_of_activity' => $this->date()->notNull(),
            'concerned_office' => $this->string()->notNull(),
            'highlights_of_activity' => $this->text(),
            'description' => 'mediumtext',
            'photos' => $this->text(),
            'prepared_by' => $this->string()->notNull(),
            'noted_by' => $this->string()->notNull(),
            'token' => $this->string(),
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName());
    }
}