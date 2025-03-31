<?php

/**
 * Handles the creation of table `{{%database}}`.
 */
class m220811_015712_create_database_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%database}}';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName(), $this->attributes([
            'system_id' => $this->bigInteger()->null(),
            'priority_sector' => $this->tinyInteger(2)->null(),
            'sector_id' => $this->string(64)->null(),
            'last_name' => $this->string(32)->notNull(),
            'first_name' => $this->string(32)->notNull(),
            'middle_name' => $this->string(32)->null(),
            'gender' => $this->string(16)->null(),
            'age' => $this->tinyInteger(3)->null(),
            'date_of_birth' => $this->date()->null(),
            'civil_status' => $this->string(32)->null(),
            'educ_attainment' => $this->string(32)->null(),
            'occupation' => $this->string()->null(32),
            'monthly_income' => $this->decimal()->null(),
            'other_source_income' => $this->string(64)->null(),
            'house_no' => $this->string(32)->null(),
            'street' => $this->string(32)->null(),
            'barangay' => $this->string(32)->null(),
            'municipality' => $this->string(32)->null(),
            'date_registered' => $this->date()->null(),
            'contact_no' => $this->string(32)->null(),
            'pensioner' => $this->string(6)->null(),
            'relation_where' => $this->string(32)->null(),
            'amount_of_pension' => $this->decimal()->null(),
            'living_with_whom' => $this->string(64)->null(),
            'relation' => $this->string(32)->null(),
            'relation_occupation' => $this->string(32)->null(),
            'relation_income' => $this->decimal()->null(),
            'status' => $this->string(16)->null(),
            'pic_path' => $this->string(128)->null(),
            'shared_pic_path' => $this->string(128)->null(),
            'encoded_by' => $this->string(64)->null(),
            'edited_by' => $this->string(64)->null(),
            'skills' => $this->string(32)->null(),
            'client_category' => $this->string(32)->null(),
            'reason1' => $this->string(64)->null(),
            'reason2' => $this->string(64)->null(),
            'reason3' => $this->string(64)->null(),
            'date_of_application' => $this->date()->null(),
            'birth_place' => $this->string(128)->null(),
            'birth_certificate' => $this->string(32)->null(),
            'ethnicity' => $this->string(32)->null(),
            'source_of_income' => $this->string(32)->null(),
            'slp_beneficiary' => $this->string(16)->null(),
            'religion' => $this->string(32)->null(),
            'mcct_beneficiary' => $this->string(16)->null(),
            'remarks' => $this->string(128)->null(),
            'type_of_disability' => $this->string(64)->null(),
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