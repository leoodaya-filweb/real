<?php

/**
 * Handles the creation of table `{{%tech_issues}}`.
 */
class m230215_032030_create_tech_issues_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%tech_issues}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName(), $this->attributes([
            'user_id' => $this->bigInteger(20)->notNull()->defaultValue(0),
            'type' => $this->tinyInteger(2)->notNull()->defaultValue(0),
            'steps' => $this->text(),
            'description' => $this->text(),
            'photos' => $this->text(),
            'status' => $this->tinyInteger(2)->notNull()->defaultValue(0),
            'ip' => $this->string(32)->notNull(),
            'browser' => $this->string(128)->notNull(),
            'os' => $this->string(128)->notNull(),
            'device' => $this->string(128)->notNull(),
            'token' => $this->string(),
        ]));

        $this->createIndexes($this->tableName(), [
            'user_id' => 'user_id',
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