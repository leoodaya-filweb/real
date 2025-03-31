<?php

use app\models\File;
use yii\db\Expression;

/**
 * Handles the creation of table `{{%files}}`.
 */
class m200913_060452_create_files_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%files}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName(), $this->attributes([
            'name' => $this->string()->notNull(),
            'extension' => $this->string(16)->notNull(),
            'size' => $this->bigInteger(20)->notNull()->defaultValue(0),
            'location' => $this->text(),
            'tag' => $this->string(),
            'token' => $this->string()->notNull()->unique(),
        ]));

        $this->seed();
    }

    public function seed()
    {
        $rows = [];
        foreach ($this->data() as $name) {
            list($name, $size) = $name;
            $rows[] = [
                'name' => $name, 
                'extension' => 'png',
                'size' => $size,
                'location' => "default/{$name}.png",
                'tag' => 'setting',
                'token' => "token-{$name}",
                'record_status' => File::RECORD_ACTIVE,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => new Expression('UTC_TIMESTAMP'),
                'updated_at' => new Expression('UTC_TIMESTAMP'),
            ];
        }
        $this->batchInsert($this->tableName(), array_keys($rows[0]), $rows);
    }

    public function data()
    {
        return [
            ['default-image_200', 16822],
            ['household-map-icon', 1947],
            ['municipal_id-template', 363109],
            ['municipality-logo', 40432],
            ['social-welfare-logo', 44782],
            ['other-logo', 15966],
            ['primary-logo', 19333],
            ['secondary-logo', 11242],
            ['brand-logo', 41527],
            ['senior-citizen-logo', 41527],
            ['solo-parent-logo', 41527],
            ['pyap-logo', 41527],
            ['doh-logo', 41527],
            ['baktom-logo', 41527],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName());
    }
}
