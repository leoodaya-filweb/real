<?php

/**
 * Handles the creation of table `{{%social_pensioners}}`.
 */
class m220831_054120_create_social_pensioners_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%social_pensioners}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName(), $this->attributes([
            'qr_id' => $this->string(128),

            'last_name' => $this->string()->notNull(),
            'middle_name' => $this->string(),
            'first_name' => $this->string()->notNull(),
            'name_suffix' => $this->string(16),

            'sex' => $this->tinyInteger(2)->notNull(),
            'age' => $this->integer()->notNull()->defaultValue(0),
            'birth_date' => $this->date(),
            'birth_place' => $this->text(),
            'civil_status' => $this->tinyInteger(2)->notNull()->defaultValue(0),
            'email' => $this->string(),
            'contact_no' => $this->string(),
            'other_contact_no' => $this->string(),

            'house_no' => $this->string(32)->null(),
            'street' => $this->string(64)->null(),
            'barangay' => $this->string(32)->null(),
            'sitio' => $this->string(32)->null(),
            'purok' => $this->string(32)->null(),

            'educational_attainment' => $this->string(),
            'occupation' => $this->string(),
            'income' => $this->decimal(11, 2)->notNull()->defaultValue(0),
            'source_of_income' => $this->string(),

            'date_registered' => $this->date()->notNull(),
            'photo' => $this->text(),
            'documents' => $this->text(),


            'pwd_score' => $this->decimal(11, 4)->notNull()->defaultValue(0),
            'senior_score' => $this->decimal(11, 4)->notNull()->defaultValue(0),
            'solo_parent_score' => $this->decimal(11, 4)->notNull()->defaultValue(0),
            'solo_member_score' => $this->decimal(11, 4)->notNull()->defaultValue(0),
            'accessibility_score' => $this->decimal(11, 4)->notNull()->defaultValue(0),

            'status' => $this->tinyInteger(2)->notNull()->defaultValue(0),

            'slug' => $this->string()->notNull(),
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