<?php

/**
 * Handles the creation of table `{{%specialsurvey}}`.
 */
class m220907_032659_create_specialsurvey_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%specialsurvey}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName(), $this->attributes([
            'survey_name' => $this->string(),
            'household_no' => $this->string(),
            'last_name' => $this->string()->notNull(),
            'first_name' => $this->string()->notNull(),
            'middle_name' => $this->string(),
            'gender' => $this->string(16),
            'age' => $this->tinyInteger(3),
            'date_of_birth' => $this->date(),
            'civil_status' => $this->string(32),
            'house_no' => $this->string(32),
            'sitio' => $this->string(32),
            'purok' => $this->string(32),
            'barangay' => $this->string(32),
            'municipality' => $this->string(32),
            'province' => $this->string(32),
            'religion' => $this->string(32),
            'criteria1_color_id' => $this->tinyInteger(2),
            'criteria2_color_id' => $this->tinyInteger(2),
            'criteria3_color_id' => $this->tinyInteger(2),
            'criteria4_color_id' => $this->tinyInteger(2),
            'criteria5_color_id' => $this->tinyInteger(2),
            'date_survey' => $this->date(),
            'remarks' => $this->string(128),
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