<?php

/**
 * Handles the creation of table `{{%medicines}}`.
 */
class m220718_010721_create_medicines_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%medicines}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName(), $this->attributes([
            'transaction_id' => $this->bigInteger(20)->notNull()->defaultValue(0),
            'name' => $this->string()->notNull(),
            'price' => $this->decimal(11, 2)->defaultValue(0),
        ]));

        $this->createIndexes($this->tableName(), [
            'transaction_id' => 'transaction_id',
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