<?php

/**
 * Handles adding columns to table `{{%database}}`.
 */
class m220824_013542_add_columns_to_database_table extends \app\migrations\Migration
{
    public function tableName()
    {
        return '{{%database}}';
    }

    public function columns()
    {
        return [
            'pwd_type' => $this->string(32),
            'pwd_type_of_disability' => $this->text(),
            'cause_of_disability' => $this->text(),
            'status_of_employment' => $this->string(32),
            'types_of_employment' => $this->string(32),
            'category_of_employment' => $this->string(32),

            'org_affiliated' => $this->string(),
            'org_contact_person' => $this->string(128),
            'org_office_address' => $this->text(),
            'org_tel_no' => $this->string(64),

            'sss_no' => $this->string(128),
            'gsis_no' => $this->string(128),
            'pagibig_no' => $this->string(128),
            'psn_no' => $this->string(128),
            'philhealth_no' => $this->string(128),

            'father_lastname' => $this->string(),
            'father_firstname' => $this->string(),
            'father_middlename' => $this->string(),

            'mother_lastname' => $this->string(),
            'mother_firstname' => $this->string(),
            'mother_middlename' => $this->string(),

            'guardian_lastname' => $this->string(),
            'guardian_firstname' => $this->string(),
            'guardian_middlename' => $this->string(),

            'accomplished_by' => $this->string(32),
            'representative_lastname' => $this->string(),
            'representative_firstname' => $this->string(),
            'representative_middlename' => $this->string(),

            'certifying_physician_lastname' => $this->string(),
            'certifying_physician_firstname' => $this->string(),
            'certifying_physician_middlename' => $this->string(),

            'license_no' => $this->string(),

            'processing_officer_lastname' => $this->string(),
            'processing_officer_firstname' => $this->string(),
            'processing_officer_middlename' => $this->string(),

            'approving_officer_lastname' => $this->string(),
            'approving_officer_firstname' => $this->string(),
            'approving_officer_middlename' => $this->string(),

            'encoder_lastname' => $this->string(),
            'encoder_firstname' => $this->string(),
            'encoder_middlename' => $this->string(),
            
            'reporting_unit' => $this->string(),
            'control_no' => $this->string(),
            'name_suffix' => $this->string(16),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumns($this->tableName(), $this->columns());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumns($this->tableName(), $this->columns());
    }
}
