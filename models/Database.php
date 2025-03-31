<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\form\setting\PrioritySectorSettingsForm;
use app\widgets\Anchor;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%database}}".
 *
 * @property int $id
 * @property int|null $system_id
 * @property string|null $priority_sector
 * @property string|null $sector_id
 * @property string $last_name
 * @property string $first_name
 * @property string|null $middle_name
 * @property string|null $gender
 * @property int|null $age
 * @property string|null $date_of_birth
 * @property string|null $civil_status
 * @property string|null $educ_attainment
 * @property string|null $occupation
 * @property float|null $monthly_income
 * @property string|null $other_source_income
 * @property string|null $house_no
 * @property string|null $street
 * @property string|null $barangay
 * @property string|null $municipality
 * @property string|null $date_registered
 * @property string|null $contact_no
 * @property string|null $pensioner
 * @property string $relation_where
 * @property float|null $amount_of_pension
 * @property string|null $living_with_whom
 * @property string|null $relation
 * @property string|null $relation_occupation
 * @property float|null $relation_income
 * @property string|null $status
 * @property string|null $pic_path
 * @property string|null $shared_pic_path
 * @property string|null $created_at
 * @property string|null $encoded_by
 * @property string|null $edited_by
 * @property string|null $updated_at
 * @property string|null $skills
 * @property string|null $client_category
 * @property string|null $reason1
 * @property string|null $reason2
 * @property string|null $reason3
 * @property string|null $date_of_application
 * @property string|null $birth_place
 * @property string|null $birth_certificate
 * @property string|null $ethnicity
 * @property string|null $source_of_income
 * @property string|null $slp_beneficiary
 * @property string|null $religion
 * @property string|null $mcct_beneficiary
 * @property string|null $remarks
 * @property string|null $type_of_disability
 * @property int $record_status
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class Database extends ActiveRecord
{
    public $total_count;
    public $active;
    public $inactive;
    public $male;
    public $male_active;
    public $male_inactive;
    public $female;
    public $female_active;
    public $female_inactive;

    public $active_female;
    public $active_male;

    public $unique_name;

    const SAMPLE_IMPORT_FILE = 'default/tests/database-sample-import-file.csv';

    // PRIORITY_SECTOR
    const SC = 'SC';
    const SLP = 'SLP';
    const IP = 'IP';
    const SP = 'SP';
    const PWD = 'PWD';
    const KALIPI = 'KALIPI';
    const PYAP = 'PYAP';
    const BAKTOM = 'BAKTOM';

    const SC_ID = 1;
    const SLP_ID = 2;
    const IP_ID = 3;
    const SP_ID = 4;
    const PWD_ID = 5;
    const KALIPI_ID = 6;
    const PYAP_ID = 7;
    const BAKTOM_ID = 8;

    const SENIOR_NO = 0;
    const SENIOR_YES = 1;

    /**
     * {@inheritdoc}
     */
	 
	 //public $active, $inactive, $male,$male_active,$male_inactive,$female, $female_active, $female_inactivel, $total_count;
    public static function tableName()
    {
        return '{{%database}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'database',
            'mainAttribute' => 'fullname',
            'paramName' => 'id',
            'dateAttribute' => 'date_of_application'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $currentYear = App::formatter()->asDateToTimezone('', 'Y');
        return $this->setRules([
            [['system_id', 'priority_sector'], 'integer'],
            [['age'], 'integer', 'max' => 999],
            [['age'], 'default', 'value' => 0],
            
            [['application_status'], 'default', 'value' => 1],
            

            [['last_name', 'first_name', 'sector_id', 'gender', 'barangay'], 'required', 'on' => $this->possibleScenario],

            [['date_of_birth', 'date_registered', 'date_of_application', 'skills', 'landmark', 'reasons', 'id_cards', 'documents', 'client_category', 'benefit_code', 'incase_emergency', 'photo', 'family_composition', 'interests', 'work_experience', 'organizations', 'org_office_address', 'cause_of_disability', 'pwd_type_of_disability', 'is_senior', 'signature', 'age','rrn','arc_no'], 'safe'],

            [['monthly_income', 'amount_of_pension', 'relation_income', 'other_income_source_amount'], 'number'],
            
            [['last_name', 'first_name', 'middle_name', 'civil_status', 'educ_attainment', 'occupation', 'house_no', 'street', 'barangay', 'municipality', 'contact_no', 'relation_where', 'relation', 'relation_occupation', 'birth_certificate', 'ethnicity', 'source_of_income', 'religion', 'pwd_type', 'status_of_employment', 'types_of_employment', 'category_of_employment', 'accomplished_by'], 'string', 'max' => 32],

            [['sector_id', 'other_source_income', 'living_with_whom', 'encoded_by', 'edited_by', 'reason1', 'reason2', 'reason3', 'type_of_disability', 'org_tel_no','vawc_case','perpetrator','perpetrator_relation'], 'string', 'max' => 64],
            [['gender', 'status', 'slp_beneficiary', 'mcct_beneficiary', 'name_suffix'], 'string', 'max' => 16],
            [['pensioner'], 'string', 'max' => 6],
            [['pic_path', 'shared_pic_path', 'birth_place', 'remarks', 'org_contact_person', 'sss_no', 'gsis_no', 'pagibig_no', 'psn_no', 'philhealth_no'], 'string', 'max' => 128],
            
            [['purok','sitio', 'other_contact_no', 'sogie', 'email', 'fathers_name', 'mothers_name', 'school_name_last_attended', 'organization_name', 'position', 'org_affiliated', 'father_lastname', 'father_firstname', 'father_middlename', 'mother_lastname', 'mother_firstname', 'mother_middlename', 'guardian_lastname', 'guardian_firstname', 'guardian_middlename', 'representative_lastname', 'representative_firstname', 'representative_middlename', 'certifying_physician_lastname', 'certifying_physician_firstname', 'certifying_physician_middlename', 'license_no', 'processing_officer_lastname', 'processing_officer_firstname', 'processing_officer_middlename', 'approving_officer_lastname', 'approving_officer_firstname', 'approving_officer_middlename', 'encoder_lastname', 'encoder_firstname', 'encoder_middlename', 'reporting_unit', 'control_no', 'preferred_name'], 'string', 'max' => 255],

            ['priority_sector', 'validatePrioritySector'],
            ['email', 'email'],
            ['email', 'trim'],


            [['date_of_birth', 'birth_place', 'civil_status', 'date_registered', 'barangay', 'municipality', 'gender', 'age'], 'required', 'on' => [self::SC, self::SP]],

            [['date_of_birth', 'birth_place', 'civil_status', 'date_of_application', 'barangay', 'municipality', 'gender', 'age'], 'required', 'on' => [self::SLP, self::IP]],
            
            [['birth_certificate', 'ethnicity', 'religion'], 'required', 'on' => self::IP],

            [['date_of_application', 'gender', 'date_of_birth', 'birth_place', 'barangay', 'municipality', 'religion', 'civil_status', 'age', 'fathers_name', 'mothers_name', 'organization_name', 'position'], 'required', 'on' => self::PYAP],
            ['school_year_last_attended', 'integer', 'max' => $currentYear, 'on' => self::PYAP],

            [['pwd_type', 'date_of_application', 'date_of_birth', 'gender', 'civil_status', 'barangay', 'municipality'], 'required', 'on' => self::PWD],

            [['preferred_name', 'date_of_birth', 'sogie', 'occupation'], 'required', 'on' => self::BAKTOM],
        ]);
    }

    public function getPossibleScenario($attribute='code')
    {
        return array_keys(self::mapPrioritySector($attribute));
    }

    public function setTheScenario()
    {
        if (in_array($this->priority_sector, $this->getPossibleScenario('id'))) {
            $priority_sectors = self::mapPrioritySector('id', 'code');

            $this->scenario = $priority_sectors[$this->priority_sector];
        }
    }

    public function getFullname()
    {
        return implode(' ', array_filter([
            $this->first_name,
            $this->last_name,
        ]));
    }
    
     public function getFullnamelast()
    {
        return implode(' ', array_filter([
            $this->last_name.',',
            $this->first_name, 
            $this->middle_name,
        ]));
    }
    
     public function getFullnamefirst()
    {
        return implode(' ', array_filter([
            $this->first_name, 
            $this->middle_name,
            $this->last_name,
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'system_id' => 'System ID',
            'priority_sector' => 'Priority Sector',
            'sector_id' => 'ID No.',
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'gender' => 'Sex at Birth',
            'age' => 'Age',
            'date_of_birth' => 'Date of Birth',
            'civil_status' => 'Civil Status',
            'educ_attainment' => 'Educational Attainment',
            'occupation' => 'Occupation',
            'monthly_income' => 'Monthly Income',
            'other_source_income' => 'Other Source of Income',
            'house_no' => 'House No.',
            'street' => 'Street',
            'barangay' => 'Barangay',
            'municipality' => 'Municipality',
            'date_registered' => 'Date Registered',
            'contact_no' => 'Contact No',
            'pensioner' => 'Pensioner',
            'relation_where' => 'Pensioner Where',
            'amount_of_pension' => 'Amount of Pension',
            'living_with_whom' => 'Living With Whom',
            'relation' => 'Relation',
            'relation_occupation' => 'Relation Occupation',
            'relation_income' => 'Relation Income',
            'status' => 'Status',
            'pic_path' => 'Pic Path',
            'shared_pic_path' => 'Shared Pic Path',
            'encoded_by' => 'Encoded By',
            'edited_by' => 'Edited By',
            'skills' => 'Skills',
            'client_category' => 'Client Category',
            'reason1' => 'Reason 1',
            'reason2' => 'Reason 2',
            'reason3' => 'Reason 3',
            'date_of_application' => 'Date of Application',
            'birth_place' => 'Birth Place',
            'birth_certificate' => 'Birth Certificate',
            'ethnicity' => 'Ethnicity',
            'source_of_income' => 'Source of Income',
            'slp_beneficiary' => 'SLP Beneficiary',
            'religion' => 'Religion',
            'mcct_beneficiary' => 'MCCT Beneficiary',
            'remarks' => 'Remarks',
            'type_of_disability' => 'Type of Disability',
            'reasons' => 'Reasons',
            'other_income_source_amount' => 'Other Source of Income Amount',
            'sogie' => 'Preferred Name',
            'prioritySectorLabel' => 'Priority Sector',
            'pwd_type' => 'Type',
            'email' => 'E-mail Address',
            'org_affiliated' => 'Organization Affiliated',
            'org_contact_person' => 'Contact Person',
            'org_office_address' => 'Office Address',
            'org_tel_no' => 'Tel Nos.',
            'sss_no' => 'SSS NO.',
            'gsis_no' => 'GSIS NO.',
            'pagibig_no' => 'PAG-IBIG NO.',
            'psn_no' => 'PSN NO.',
            'philhealth_no' => 'PhilHealth NO.',
            'representative_lastname' => 'Last Name',
            'representative_firstname' => 'First Name',
            'representative_middlename' => 'Middle Name',
            'arc_no'=>'ARK No.'
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\DatabaseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\DatabaseQuery(get_called_class());
    }

    public function init()
    {
        parent::init();
        $this->municipality = App::setting('address')->municipalityName;
    }

    public function validatePrioritySector($attribute, $params)
    {
        $sector_ids = array_keys(self::priorityReIndex());

        if (! in_array($this->priority_sector, $sector_ids)) {
            $this->addError($attribute, 'Priority Sector invalid');
        }
    }
     
    public function gridColumns()
    {
        $print = Yii::$app->request->get('print');

        switch ($this->priority_sector) {
            case '11':
                $columns = [
            /*        
            'priority_sector' => [
			'attribute' => 'priority_sector',
			'format' => 'raw',
			'value'=> function ($model, $index){ 
                return Anchor::widget([
                        'title' => $model->prioritySectorLabel,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
			 },
			],
			*/
			'date_registered' => ['attribute' => 'date_registered', 'format' => 'raw', 'label' => 'Date', 'enableSorting' =>($print==1?false:true)],
            'Case_ID_No' => ['attribute' => 'sector_id', 'format' => 'raw', 'label' => 'Case ID No.', 'enableSorting' =>($print==1?false:true)],
            
            'full_name' => [
                //'attribute' => 'first_name', 
                'format' => 'raw',
                'label' => 'Name',
                'value'=> function ($model, $index){ 
				    return $model->Fullnamefirst; //$model->Fullnamelast;
                    
                },
                'enableSorting' =>($print==1?false:true)
			  ],
			  'gender' => ['attribute' => 'gender', 'format' => 'raw', 'label' => 'Sex', 'enableSorting' =>($print==1?false:true)],
			  'age' => ['attribute' => 'age', 'format' => 'raw', 'enableSorting' =>($print==1?false:true)],
			  'address' => ['attribute' => 'address', 'format' => 'raw', 'label' => 'Address', 'enableSorting' =>($print==1?false:true)],
			  'vawc_case' => ['attribute' => 'vawc_case', 'format' => 'raw', 'label' => 'Case', 'enableSorting' =>($print==1?false:true)],
			  'perpetrator' => ['attribute' => 'perpetrator', 'format' => 'raw', 'label' => 'Perpetrator', 'enableSorting' =>($print==1?false:true)],
			  'perpetrator_relation' => ['attribute' => 'perpetrator_relation', 'format' => 'raw', 'label' => 'Relationship', 'enableSorting' =>($print==1?false:true)],
			  'remarks' => ['attribute' => 'remarks', 'format' => 'raw', 'label' => 'Remarks', 'enableSorting' =>($print==1?false:true)],
			  
			  
                ];
                break;
             
              default:
                
               $columns = [
		   /*
            'system_id' => [
                'attribute' => 'system_id', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->system_id,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                },
				'visible'=>false
            ],
			*/
            'priority_sector' => [
			'attribute' => 'priority_sector',
			'format' => 'raw',
			'value'=> function ($model, $index){ 
                return Anchor::widget([
                        'title' => $model->prioritySectorLabel,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
			 },
			],
            'sector_id' => ['attribute' => 'sector_id', 'format' => 'raw'],
            'last_name' => ['attribute' => 'last_name', 'format' => 'raw'],
            'first_name' => ['attribute' => 'first_name', 
			'value'=> function ($model, $index){ 
				return $model->first_name;
			},

			'format' => 'raw'
			],
            'middle_name' => ['attribute' => 'middle_name', 'format' => 'raw'],
 
            'gender' => ['attribute' => 'gender', 'format' => 'raw'],
            'age' => ['attribute' => 'age', 'format' => 'raw'],

            'date_of_birth' => ['attribute' => 'date_of_birth', 'format' => 'raw'],
            
			'status' => [
			  'attribute' => 'status', 
			  'format' => 'raw',
			  'value'=> 'recordStatusHtmlWithConfirmation',
			],
			'barangay' => [
			'attribute' => 'barangay', 
			//'label' => 'Address', 
			'format' => 'raw',
			'value'=> function ($model, $index){ 
				return $model->barangay;
			   },
			],
			'rrn' => ['attribute' => 'rrn', 'format' => 'raw'],
			'ark_no' => ['attribute' => 'arc_no', 'format' => 'raw'],
            // 'date_of_application' => ['attribute' => 'date_of_application', 'format' => 'raw'],
            // 'date_registered' => ['attribute' => 'date_registered', 'format' => 'raw'],
			/*
            'civil_status' => ['attribute' => 'civil_status', 'format' => 'raw'],
            'educ_attainment' => ['attribute' => 'educ_attainment', 'format' => 'raw'],
            'occupation' => ['attribute' => 'occupation', 'format' => 'raw'],
            'monthly_income' => ['attribute' => 'monthly_income', 'format' => 'raw'],
            'other_source_income' => ['attribute' => 'other_source_income', 'format' => 'raw'],
            'house_no' => ['attribute' => 'house_no', 'format' => 'raw'],
            'street' => ['attribute' => 'street', 'format' => 'raw'],
            
            'municipality' => ['attribute' => 'municipality', 'format' => 'raw'],
            'contact_no' => ['attribute' => 'contact_no', 'format' => 'raw'],
            'pensioner' => ['attribute' => 'pensioner', 'format' => 'raw'],
            'relation_where' => ['attribute' => 'relation_where', 'format' => 'raw'],
            'amount_of_pension' => ['attribute' => 'amount_of_pension', 'format' => 'raw'],
            'living_with_whom' => ['attribute' => 'living_with_whom', 'format' => 'raw'],
            'relation' => ['attribute' => 'relation', 'format' => 'raw'],
            'relation_occupation' => ['attribute' => 'relation_occupation', 'format' => 'raw'],
            'relation_income' => ['attribute' => 'relation_income', 'format' => 'raw'],
            'pic_path' => ['attribute' => 'pic_path', 'format' => 'raw'],
            'shared_pic_path' => ['attribute' => 'shared_pic_path', 'format' => 'raw'],
            'encoded_by' => ['attribute' => 'encoded_by', 'format' => 'raw'],
            'edited_by' => ['attribute' => 'edited_by', 'format' => 'raw'],
            'skills' => ['attribute' => 'skills', 'format' => 'raw'],
            'client_category' => ['attribute' => 'client_category', 'format' => 'raw'],
            'reason1' => ['attribute' => 'reason1', 'format' => 'raw'],
            'reason2' => ['attribute' => 'reason2', 'format' => 'raw'],
            'reason3' => ['attribute' => 'reason3', 'format' => 'raw'],
            'birth_place' => ['attribute' => 'birth_place', 'format' => 'raw'],
            'birth_certificate' => ['attribute' => 'birth_certificate', 'format' => 'raw'],
            'ethnicity' => ['attribute' => 'ethnicity', 'format' => 'raw'],
            'source_of_income' => ['attribute' => 'source_of_income', 'format' => 'raw'],
            'slp_beneficiary' => ['attribute' => 'slp_beneficiary', 'format' => 'raw'],
            'religion' => ['attribute' => 'religion', 'format' => 'raw'],
            'mcct_beneficiary' => ['attribute' => 'mcct_beneficiary', 'format' => 'raw'],
            'remarks' => ['attribute' => 'remarks', 'format' => 'raw'],
            'type_of_disability' => ['attribute' => 'type_of_disability', 'format' => 'raw'],
			*/
        ];
          break;
        
        }

        
        return $columns;
    }
	
	
    public function getFormattedFamilyComposition()
    {
        $data = [];

        foreach ($this->family_composition as $family_composition) {
            if ($family_composition['name']) {
                $data[] = $family_composition;
            }
        }

        $data = $data ?: $this->family_composition[0];
        return $data;
    }
	
	
	public function getExportColumns()
    {
        $columnNames = self::getTableSchema()->getColumnNames();
        $columns = [];

        foreach ($columnNames as $name) {
            $columns[$name] = [
                'attribute' => $name,
                'label' => strtoupper($name),
                'format' => 'raw',
                'enableSorting' => false,
            ];

        }

        $unsets = [
            'id',
            'record_status',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            'is_senior',
            'signature',
            'pic_path',
            'shared_pic_path',
            'reasons',
            'id_cards',
            'documents',
            'reason1',
            'reason2',
            'reason3',
            'photo',
            'family_composition',
            'skills',
            'client_category',
            'interests',
            'work_experience',
            'organizations',
            'pwd_type_of_disability',
            'cause_of_disability',
        ];

        foreach ($unsets as $unset) {
            unset($columns[$unset]);
        }



        switch ($this->priority_sector) {
            case '11':
                $columns = [
                    
                     [ 'class' => 'yii\grid\SerialColumn', 'header' => 'No.'],
                    'priority_sector' => [
			'attribute' => 'priority_sector',
			'format' => 'raw',
			'value'=> function ($model, $index){ 
                return $model->prioritySectorLabel;
			 },
			],
			'date_registered' => ['attribute' => 'date_registered', 'format' => 'raw', 'label' => 'Date'],
            'sector_id' => ['attribute' => 'sector_id', 'format' => 'raw', 'label' => 'Case ID No.'],
            
            'full_name' => [
                //'attribute' => 'first_name', 
                'format' => 'raw',
                'label' => 'Name',
                'value'=> function ($model, $index){ 
				    return $model->Fullnamelast;
                    
                },
			  ],
			  'gender' => ['attribute' => 'gender', 'format' => 'raw', 'label' => 'Sex'],
			  'age' => ['attribute' => 'age', 'format' => 'raw'],
			  
			  'address' => ['attribute' => 'address', 'format' => 'raw', 'label' => 'Address'],
			  'vawc_case' => ['attribute' => 'vawc_case', 'format' => 'raw', 'label' => 'Case'],
			  'perpetrator' => ['attribute' => 'perpetrator', 'format' => 'raw', 'label' => 'Perpetrator'],
			  'perpetrator_relation' => ['attribute' => 'perpetrator_relation', 'format' => 'raw', 'label' => 'Relationship'],
			  'remarks' => ['attribute' => 'remarks', 'format' => 'raw', 'label' => 'Remarks'],
			  
			  
                ];
                break;
             
              default:
                   $columns = $columns;
                break;
                  
          }


        return $columns;
        

        $columns = [
            'priority_sector' => [
    			'attribute' => 'prioritySectorCode',
    			'format' => 'raw',
                'enableSorting' => false,
    			'value'=> 'prioritySectorCode',
			],
            'sector_id' => [
                'attribute' => 'sector_id', 
                'enableSorting' => false, 
                'format' => 'raw'
            ],
            'last_name' => [
                'attribute' => 'last_name', 
                'format' => 'raw'
            ],
            'first_name' => [
                'attribute' => 'first_name', 
                'format' => 'raw'
			],
            'middle_name' => ['attribute' => 'middle_name', 'format' => 'raw'],
            'gender' => ['attribute' => 'gender', 'format' => 'raw'],
			
            'age' => ['attribute' => 'age', 'format' => 'raw'],
            'date_of_birth' => ['attribute' => 'date_of_birth', 'format' => 'raw'],
            'civil_status' => ['attribute' => 'civil_status', 'format' => 'raw'],
            'educ_attainment' => ['attribute' => 'educ_attainment', 'format' => 'raw'],
            'occupation' => ['attribute' => 'occupation', 'format' => 'raw'],
            'monthly_income' => ['attribute' => 'monthly_income', 'format' => 'raw'],
            'other_source_income' => ['attribute' => 'other_source_income', 'format' => 'raw'],
            'house_no' => ['attribute' => 'house_no', 'format' => 'raw'],
            'street' => ['attribute' => 'street', 'format' => 'raw'],
            'barangay' => ['attribute' => 'barangay', 'format' => 'raw'],
            'municipality' => ['attribute' => 'municipality', 'format' => 'raw'],
            'date_registered' => ['attribute' => 'date_registered', 'format' => 'raw'],
            'contact_no' => ['attribute' => 'contact_no', 'format' => 'raw'],
            'pensioner' => ['attribute' => 'pensioner', 'format' => 'raw'],
            'relation_where' => ['attribute' => 'relation_where', 'format' => 'raw'],
            'amount_of_pension' => ['attribute' => 'amount_of_pension', 'format' => 'raw'],
            'living_with_whom' => ['attribute' => 'living_with_whom', 'format' => 'raw'],
            'relation' => ['attribute' => 'relation', 'format' => 'raw'],
            'relation_occupation' => ['attribute' => 'relation_occupation', 'format' => 'raw'],
            'relation_income' => ['attribute' => 'relation_income', 'format' => 'raw'],
            'pic_path' => ['attribute' => 'pic_path', 'format' => 'raw'],
            'shared_pic_path' => ['attribute' => 'shared_pic_path', 'format' => 'raw'],
            'encoded_by' => ['attribute' => 'encoded_by', 'format' => 'raw'],
            'edited_by' => ['attribute' => 'edited_by', 'format' => 'raw'],
            'skills' => ['attribute' => 'skills', 'format' => 'encode'],
            'client_category' => ['attribute' => 'client_category', 'format' => 'encode'],
            // 'reason1' => ['attribute' => 'reason1', 'format' => 'raw'],
            // 'reason2' => ['attribute' => 'reason2', 'format' => 'raw'],
            // 'reason3' => ['attribute' => 'reason3', 'format' => 'raw'],
            'date_of_application' => ['attribute' => 'date_of_application', 'format' => 'raw'],
            'birth_place' => ['attribute' => 'birth_place', 'format' => 'raw'],
            'birth_certificate' => ['attribute' => 'birth_certificate', 'format' => 'raw'],
            'ethnicity' => ['attribute' => 'ethnicity', 'format' => 'raw'],
            'source_of_income' => ['attribute' => 'source_of_income', 'format' => 'raw'],
            'slp_beneficiary' => ['attribute' => 'slp_beneficiary', 'format' => 'raw'],
            'religion' => ['attribute' => 'religion', 'format' => 'raw'],
            'mcct_beneficiary' => ['attribute' => 'mcct_beneficiary', 'format' => 'raw'],
            'remarks' => ['attribute' => 'remarks', 'format' => 'raw'],
            'type_of_disability' => ['attribute' => 'type_of_disability', 'format' => 'raw'],
            'reasons' => ['attribute' => 'reasons', 'format' => 'encode'],
            'interests' => ['attribute' => 'interests', 'format' => 'encode'],
            'family_composition' => ['attribute' => 'family_composition', 'format' => 'encode'],
            'work_experience' => ['attribute' => 'work_experience', 'format' => 'encode'],
            'organizations' => ['attribute' => 'organizations', 'format' => 'encode'],
            'pwd_type_of_disability' => ['attribute' => 'pwd_type_of_disability', 'format' => 'encode'],
            'cause_of_disability' => ['attribute' => 'cause_of_disability', 'format' => 'encode'],
        ];

        foreach ($columns as $key => &$column) {
            $column['enableSorting'] = false;
            $column['label'] = strtoupper($column['attribute']);

            if ($key == 'family_composition') {
                $column['value'] = function($model) {
                    $fc = [];
                    foreach ($model->family_composition as $value) {
                        if ($value['name']) {
                            $fc = $value;
                        }
                    }

                    return $fc;
                };
            }
        }

        return $columns;
    }
	
	
	
	 public function getFooterGridColumns()
    {
        $columns = [
            // 'created_at' => ['attribute' => 'created_at', 'format' => 'fulldate', 'visible'=>false],
            // 'created_by' => ['attribute' => 'created_by', 'format' => 'raw', 'value' => 'createdByName'],
    //         'last_updated' => [
    //             'attribute' => 'updated_at',
    //             'label' => 'last updated',
    //             'format' => 'ago',
				// 'visible'=>false
    //         ],
            // 'updated_by' => ['attribute' => 'updated_by', 'format' => 'raw', 'value' => 'updatedByName'],
        ];

        if (App::isLogin() && App::identity()->can('in-active-data', $this->controllerID())) {
            // $columns['active'] = [
            //     'attribute' => 'record_status',
            //     'label' => 'active',
            //     'format' => 'raw', 
            //     'value' => 'recordStatusHtml'
            // ];
        }
        
        return $columns;
    }

    public function getDetailPhoto()
    {
        return Html::image($this->photo, [
            'w' => 70, 
            'quality' => 90
        ]);
    }

    public function getMainInformationColumns()
    {
        switch ($this->priority_sector) {
            case self::SC_ID:
                $columns = [
                    'status' => 'status:raw',
                    'priority_sector' => 'prioritySectorLabel:raw',
                    'sector_id' => 'sector_id:raw',
                    'date_registered' => 'date_registered:raw',
                ];
                break;

            case self::SP_ID:
            case self::KALIPI_ID:
                $columns = [
                    'status' => 'status:raw',
                    'priority_sector' => 'prioritySectorLabel:raw',
                    'sector_id' => 'sector_id:raw',
                    'date_registered' => 'date_registered:raw',
                    'date_of_application' => 'date_of_application:raw',
                ];
                break;

            case self::SLP_ID:
            case self::IP_ID:
                $columns = [
                    'status' => 'status:raw',
                    'priority_sector' => 'prioritySectorLabel:raw',
                    'sector_id' => 'sector_id:raw',
                    'date_of_application' => 'date_of_application:raw',
                ];
                break;

            case self::PWD_ID:
                $columns = [
                    'status' => 'status:raw',
                    'priority_sector' => 'prioritySectorLabel:raw',
                    'pwd_type' => 'pwd_type:raw',
                    'sector_id' => 'sector_id:raw',
                    'date_of_application' => 'date_of_application:raw',
                ];
                break;

            case self::BAKTOM_ID:
                $columns = [
                    'status' => 'status:raw',
                    'priority_sector' => 'prioritySectorLabel:raw',
                    'sector_id' => 'sector_id:raw',
                    'date_of_application' => 'date_of_application:raw',
                ];
                break;
                
           case '11': // vawc
                $columns = [
                    'status' => 'status:raw',
                    'priority_sector' => 'prioritySectorLabel:raw',
                    'sector_id' =>  ['attribute' => 'sector_id', 'format' => 'raw', 'label'=>'Case ID No.'],    // 'sector_id:raw',
                    'date_registered' => 'date_registered:raw',
                    'date_of_application' => 'date_of_application:raw',
                    'fullnamelast'=>['value' => $this->Fullnamefirst, 'format' => 'raw', 'label'=>'Name'],    
                    'vawc_case' =>  ['attribute' => 'vawc_case', 'format' => 'raw', 'label'=>'Case'],           //'vawc_case:raw',
                    'perpetrator' =>  ['attribute' => 'perpetrator', 'format' => 'raw', 'label'=>'Perpetrator'], 
                    'perpetrator_relation' =>  ['attribute' => 'perpetrator_relation', 'format' => 'raw', 'label'=>'Relationship'], 
                    'remarks' =>  ['attribute' => 'remarks', 'format' => 'raw', 'label'=>'Remarks'], 
                   
                ];
                break;
            
            default:
                $columns = [
                    'status' => 'status:raw',
                    'priority_sector' => 'prioritySectorLabel:raw',
                    'pwd_type' => 'pwd_type:raw',
                    'sector_id' => 'sector_id:raw',
                    'date_registered' => 'date_registered:raw',
                    'date_of_application' => 'date_of_application:raw',
                    'organization_name' => 'organization_name:raw',
                    'position' => 'position:raw',
                ];
                break;
        }

        return $columns;
    }

    public function getPrimaryInformationColumns()
    {
        switch ($this->priority_sector) {
            case self::SC_ID:
            case self::SP_ID:
            case self::SLP_ID:
                $columns = [
                    'photo' => 'detailPhoto:raw',
                    'last_name' => 'last_name:raw',
                    'first_name' => 'first_name:raw',
                    'middle_name' => 'middle_name:raw',
                    'gender' => 'gender:raw',
                    'civil_status' => 'civil_status:raw',
                    'religion' => 'religion:raw',
                    'date_of_birth' => 'date_of_birth:raw',
                    'age' => 'age:raw',
                    'birth_place' => 'birth_place:raw',
                    'arc_no' => 'arc_no:raw',
                ];
                break;

            case self::PYAP_ID:
                $columns = [
                    'photo' => 'detailPhoto:raw',
                    'last_name' => 'last_name:raw',
                    'first_name' => 'first_name:raw',
                    'middle_name' => 'middle_name:raw',
                    'gender' => 'gender:raw',
                    'civil_status' => 'civil_status:raw',
                    'religion' => 'religion:raw',
                    'date_of_birth' => 'date_of_birth:raw',
                    'age' => 'age:raw',
                    'birth_place' => 'birth_place:raw',
                    'fathers_name' => 'fathers_name:raw',
                    'mothers_name' => 'mothers_name:raw',
                    'arc_no' => 'arc_no:raw',
                ];
                break;

            case self::IP_ID:
                $columns = [
                    'photo' => 'detailPhoto:raw',
                    'last_name' => 'last_name:raw',
                    'first_name' => 'first_name:raw',
                    'middle_name' => 'middle_name:raw',
                    'gender' => 'gender:raw',
                    'civil_status' => 'civil_status:raw',
                    'religion' => 'religion:raw',
                    'date_of_birth' => 'date_of_birth:raw',
                    'age' => 'age:raw',
                    'birth_place' => 'birth_place:raw',
                     'arc_no' => 'arc_no:raw',
                ];
                break;

            case self::PWD_ID:
                $columns = [
                    'photo' => 'detailPhoto:raw',
                    'last_name' => 'last_name:raw',
                    'first_name' => 'first_name:raw',
                    'middle_name' => 'middle_name:raw',
                    'name_suffix' => 'name_suffix:raw',
                    'date_of_birth' => 'date_of_birth:raw',
                    'gender' => 'gender:raw',
                    'civil_status' => 'civil_status:raw',
                     'arc_no' => 'arc_no:raw',
                ];
                break;

            default:
                $columns = [
                    'photo' => 'detailPhoto:raw',
                    'last_name' => 'last_name:raw',
                    'first_name' => 'first_name:raw',
                    'middle_name' => 'middle_name:raw',
                    'gender' => 'gender:raw',
                    'civil_status' => 'civil_status:raw',
                    'religion' => 'religion:raw',
                    'date_of_birth' => 'date_of_birth:raw',
                    'age' => 'age:raw',
                    'birth_place' => 'birth_place:raw',
                    'birth_certificate' => 'birth_certificate:raw',
                    'ethnicity' => 'ethnicity:raw',
                    'type_of_disability' => 'type_of_disability:raw',
                    'fathers_name' => 'fathers_name:raw',
                    'mothers_name' => 'mothers_name:raw',
                    'arc_no' => 'arc_no:raw',
                ];
                break;
        }

        return $columns;
    }

    public function getAddressColumns()
    {
        return [
            'house_no' => 'house_no:raw',
            'street' => 'street:raw',
            'sitio' => 'sitio:raw',
            'purok' => 'purok:raw',
            'barangay' => 'barangay:raw',
            'municipality' => 'municipality:raw',
            'landmark' => 'landmark:raw',
        ];
    }


    public function getDisabilityColumns()
    {
        $columns = [
            'pwd_type_of_disability' => 'pwd_type_of_disability:ul',
            'cause_of_disability' => 'cause_of_disability:jsonEditor',
        ];

        return $columns;
    }


    public function getEmploymentColumns()
    {
        $columns = [
            'status_of_employment' => 'status_of_employment:raw',
            'types_of_employment' => 'types_of_employment:raw',
            'category_of_employment' => 'category_of_employment:raw',
        ];

        return $columns;
    }

    public function getOrganizationColumns()
    {
        $columns = [
            'org_affiliated' => 'org_affiliated:raw',
            'org_contact_person' => 'org_contact_person:raw',
            'org_office_address' => 'org_office_address:raw',
            'org_tel_no' => 'org_tel_no:raw',
        ];

        return $columns;
    }

    public function getReferenceNoColumns()
    {
        $columns = [
            'sss_no' => 'sss_no:raw',
            'gsis_no' => 'gsis_no:raw',
            'pagibig_no' => 'pagibig_no:raw',
            'psn_no' => 'psn_no:raw',
            'philhealth_no' => 'philhealth_no:raw',
        ];

        return $columns;
    }

    public function getFamilyBackgroundColumns()
    {
        $columns = [
            'father_lastname' => 'father_lastname:raw',
            'father_firstname' => 'father_firstname:raw',
            'father_middlename' => 'father_middlename:raw',

            'mother_lastname' => 'mother_lastname:raw',
            'mother_firstname' => 'mother_firstname:raw',
            'mother_middlename' => 'mother_middlename:raw',

            'guardian_lastname' => 'guardian_lastname:raw',
            'guardian_firstname' => 'guardian_firstname:raw',
            'guardian_middlename' => 'guardian_middlename:raw',
        ];

        return $columns;
    }

    public function getRepresentativeColumns()
    {
        $columns = [
            'representative_lastname' => 'representative_lastname:raw',
            'representative_firstname' => 'representative_firstname:raw',
            'representative_middlename' => 'representative_middlename:raw',
        ];

        return $columns;
    }


    public function getContactInformationColumns()
    {
        switch ($this->priority_sector) {
            case self::PWD_ID:
                $columns = [
                    'contact_no' => [
                        'label' => 'Mobile No.',
                        'value' => 'contact_no',
                        'format' => 'raw'
                    ],
                    'other_contact_no' => [
                        'label' => 'Landline No.',
                        'value' => 'other_contact_no',
                        'format' => 'raw'
                    ],
                    'email' => 'email:raw',
                ];
                break;

            default:
                $columns = [
                    'contact_no' => 'contact_no:raw',
                    'other_contact_no' => 'other_contact_no:raw',
                    'email' => 'email:raw',
                ];
                break;
        }
        return $columns;
    }

    public function getEducationAndSourceOfIncomeColumns()
    {
        switch ($this->priority_sector) {
            case self::SC_ID:
                $columns = [
                    'educ_attainment' => 'educ_attainment:raw',
                    'occupation' => 'occupation:raw',
                    'source_of_income' => 'source_of_income:raw',
                    'monthly_income' => 'monthly_income:number',
                    'other_source_income' => 'other_source_income:raw',
                    'other_income_source_amount' => 'other_income_source_amount:number',
                ];
                break;
            case self::SP_ID:
            case self::SLP_ID:
                $columns = [
                    'educ_attainment' => 'educ_attainment:raw',
                    'occupation' => 'occupation:raw',
                    'source_of_income' => 'source_of_income:raw',
                    'monthly_income' => 'monthly_income:number',
                    'other_source_income' => 'other_source_income:raw',
                    'other_income_source_amount' => 'other_income_source_amount:number',
                    'skills' => 'skills:ul',
                ];
                break;

            case self::PYAP_ID:
                $columns = [
                    'school_name_last_attended' => 'school_name_last_attended:raw',
                    'school_year_last_attended' => 'school_year_last_attended:raw',
                    'educ_attainment' => 'educ_attainment:raw',
                ];
                break;

            case self::PWD_ID:
                $columns = [
                    'educ_attainment' => 'educ_attainment:raw',
                    'occupation' => 'occupation:raw',
                ];
                break;
            
            default:
                $columns = [
                    'school_name_last_attended' => 'school_name_last_attended:raw',
                    'school_year_last_attended' => 'school_year_last_attended:raw',
                    'educ_attainment' => 'educ_attainment:raw',
                    'occupation' => 'occupation:raw',
                    'source_of_income' => 'source_of_income:raw',
                    'monthly_income' => 'monthly_income:number',
                    'other_source_income' => 'other_source_income:raw',
                    'other_income_source_amount' => 'other_income_source_amount:number',
                    'skills' => 'skills:ul',
                ];
                break;
        }

        return $columns;
    }

    public function getPensionColumns()
    {
        return [
            'pensioner' => 'pensioner:raw',
            'relation_where' => 'relation_where:raw',
            'amount_of_pension' => 'amount_of_pension:number',
        ];
    }


    public function getRelationColumns()
    {
        return [
            'living_with_whom' => 'living_with_whom:raw',
            'relation' => 'relation:raw',
            'relation_occupation' => 'relation_occupation:raw',
            'relation_income' => 'relation_income:number',
        ];
    }

    public function getBeneficiaryColumns()
    {
        return [
            'slp_beneficiary' => 'slp_beneficiary:raw',
            'mcct_beneficiary' => 'mcct_beneficiary:raw',
        ];
    }

    public function getOthersColumns()
    {
        switch ($this->priority_sector) {
            case self::SC_ID:
            case self::IP_ID:
                $columns = [
                    'remarks' => 'remarks:raw',
                ];
                break;
            case self::KALIPI_ID:
                $columns = [
                    'client_category' => 'client_category:ul',
                    'remarks' => 'remarks:raw',
                ];
                break;
            case self::SP_ID:
            case self::SLP_ID:
                $columns = [
                    'client_category' => 'client_category:ul',
                    'remarks' => 'remarks:raw',
                    'reasons' => 'reasons:ul',
                ];
                break;

            case self::PWD_ID:
                $columns = [
                    'accomplished_by' => 'accomplished_by:raw',

                    'certifying_physician_lastname' => 'certifying_physician_lastname:raw' ,
                    'certifying_physician_firstname' => 'certifying_physician_firstname:raw' ,
                    'certifying_physician_middlename' => 'certifying_physician_middlename:raw' ,
                    'license_no' => 'license_no:raw' ,

                    'processing_officer_lastname' => 'processing_officer_lastname:raw' ,
                    'processing_officer_firstname' => 'processing_officer_firstname:raw' ,
                    'processing_officer_middlename' => 'processing_officer_middlename:raw' ,

                    'approving_officer_lastname' => 'approving_officer_lastname:raw' ,
                    'approving_officer_firstname' => 'approving_officer_firstname:raw' ,
                    'approving_officer_middlename' => 'approving_officer_middlename:raw' ,

                    'encoder_lastname' => 'encoder_lastname:raw' ,
                    'encoder_firstname' => 'encoder_firstname:raw' ,
                    'encoder_middlename' => 'encoder_middlename:raw' ,

                    'reporting_unit' => 'reporting_unit:raw' ,
                    'control_no' => 'control_no:raw' ,
                    'remarks' => 'remarks:raw',
                ];
                break;

            default:
                $columns = [
                    'client_category' => 'client_category:ul',
                    'remarks' => 'remarks:raw',
                    'reasons' => 'reasons:ul',
                ];
                break;
        }

        return $columns;
    }


    public function detailColumns()
    {
        return array_merge(
            $this->getMainInformationColumns(),
            $this->getPrimaryInformationColumns(),
            $this->getAddressColumns(),
            $this->getContactInformationColumns(),
            $this->getEducationAndSourceOfIncomeColumns(),
            $this->getPensionColumns(),
            $this->getRelationColumns(),
            $this->getBeneficiaryColumns(),
            $this->getOthersColumns()
        );
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['JsonBehavior']['fields'] = [
            'client_category', 
            'skills',
            'reasons',
            'id_cards',
            'documents',
            'interests',
            'family_composition',
            'work_experience',
            'organizations',
            'pwd_type_of_disability',
            'cause_of_disability',
            'benefit_code',
            'incase_emergency',
        ];

        $behaviors['DatabaseBehavior'] = ['class' => 'app\behaviors\DatabaseBehavior'];
        $behaviors['DateBehavior'] = [
            'class' => 'app\behaviors\DateBehavior',
            'attributes' => [
                'date_of_application',
                'date_registered',
                'date_of_birth',
            ]
        ];

        $behaviors['AgeBehavior'] = [
            'class' => 'app\behaviors\AgeBehavior',
            'dateAttribute' => 'date_of_birth',
            'condition' => function($model) {
                return $model->isActive;
            }
        ];

        return $behaviors;
    }

    public static function baktomId()
    {
        $model = new PrioritySectorSettingsForm();
        $data = ArrayHelper::index($model->data, 'id');

        $baktom_id = '';
        foreach ($data as $key => $value) {
            if (trim(strtoupper($value['code'])) == 'BAKTOM') {
                $baktom_id = $value['id'];
            }
        }

        return $baktom_id;
    }
	
	public static function priorityReIndex($withBaktom=true)
    {
        $model = new PrioritySectorSettingsForm();
        $data = ArrayHelper::index($model->data, 'id');

        if ($withBaktom == false) {
            $baktom_id = self::baktomId();
            if (isset($data[$baktom_id])) {
                unset($data[$baktom_id]);
            }
        }

        return $data;
    }

    public static function mapPrioritySector($key='id', $value='label', $withBaktom=true)
    {
        return ArrayHelper::map(self::priorityReIndex($withBaktom), $key, $value);
    }

    public function getPrioritySectorCode($priority_sector='')
    {
        $priority_sector = $priority_sector ?: $this->priority_sector;
        $data = Database::mapPrioritySector('id', 'code');

        return $data[$priority_sector] ?? '';
    }

    public function getPrioritySectorLabel($priority_sector='')
    {
        $priority_sector = $priority_sector ?: $this->priority_sector;
        $data = Database::mapPrioritySector();

        return $data[$priority_sector] ?? '';
    }

    public static function lastUpdated($priority_sector, $format = 'F d, Y')
    {
        $model = Database::find()
            ->where([
                'priority_sector' => $priority_sector,
                'status' => 'Active'
            ])
            ->max('updated_at');
        if ($model) {
            if ((int) $model == 0) {
                return;
            }
            return date($format, strtotime($model));
        }
    }

    public function getFormTemplate($priority_sector='')
    {
        $priority_sector = $priority_sector ?: $this->priority_sector;
        $templates = [
            1 => '_form_senior',
            2 => '_form_slp',
            3 => '_form_ip',
            4 => '_form_sp',
            5 => '_form_pwd',
            6 => '_form_kalipi',
            7 => '_form_pyap',
            8 => '_form_baktom',
            11 => '_form_vawc',
        ];

        return $templates[$priority_sector] ?? '_form';
    }

    public function getIdentificationCards()
    {
        if (($token = $this->id_cards) != null) {
            $files = File::find()
                ->where(['token' => $token])
                ->orderBy(['id' => SORT_DESC])
                ->all();

            return $files;
        }
    }

    public function getDocumentFiles()
    {
        return $this->imageFiles;
    }

    public function getImageFiles()
    {
        if (($token = $this->documents) != null) {
            $files = File::find()
                ->where(['token' => $token])
                ->orderBy(['id' => SORT_DESC])
                ->all();

            return $files;
        }
    }

    public function getHeaderCreateButton()
    {
        $links = Html::foreach(self::priorityReIndex(), function($p, $id) {
            return Html::a($p['label'], ['database/create', 'priority_sector' => $id], [
                'class' => 'dropdown-item'
            ]);
        });

        return <<< HTML
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle font-weight-bolder font-size-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="-138">
                    Create Database Entry
                </button>
                <div class="dropdown-menu" style="">
                    {$links}
                </div>
            </div>
        HTML;
    }

    public function getPrioritySectorIndexUrl($fullpath=true)
    {
        if ($this->checkLinkAccess('member')) {
            $paramName = 'priority_sector';
            $url = [
                implode('/', [$this->controllerID(), 'member']),
                $paramName => $this->{$paramName}
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getViewTemplate($priority_sector='')
    {
        $priority_sector = $priority_sector ?: $this->priority_sector;
        $templates = [
            self::SC_ID     => 'senior-citizen',
            self::SLP_ID    => 'slp',
            self::IP_ID     => 'ip',
            self::SP_ID     => 'solo-parent',
            self::PWD_ID    => 'pwd',
            self::KALIPI_ID => 'kalipi',
            self::PYAP_ID   => 'pyap',
            self::BAKTOM_ID => 'baktom',
            11 => 'vawc',
        ];

        return $templates[$priority_sector] ?? 'default';
    }

    public function getAddress()
    {
        $address = [];

        
        if ($this->street) {
            $address[] = $this->street;
        }

        if ($this->purok) {
            $address[] = $this->purok;
        }

        if ($this->sitio) {
            $address[] = $this->sitio;
        }

        if ($this->barangay) {
            $address[] = 'Brgy. ' . $this->barangay;
        }
        if ($this->municipality) {
            $address[] = $this->municipality;
        }

        $address[] = App::setting('address')->provinceName;

        return ucwords(strtolower(implode(', ', $address)));
    }

    public function getIsPensioner()
    {
        return strtoupper($this->pensioner) == 'YES';
    }

    public function getIsMale()
    {
        return strtoupper($this->gender) == 'MALE';
    }

    public function getTypeOfDisabilityDropdown()
    {
        $arr0 = App::params('pwd_form')['type_of_disability'][0];
        $arr1 = App::params('pwd_form')['type_of_disability'][1];

        $arr2 = array_merge($arr0, $arr1);

        return array_combine($arr2, $arr2);
    }

    public function getEducationalAttainmentDropdown()
    {
        $arr0 = App::params('pwd_form')['educational_attainment'][0];
        $arr1 = App::params('pwd_form')['educational_attainment'][1];

        $arr2 = array_merge($arr0, $arr1);

        return array_combine($arr2, $arr2);
    }

    public function getMember()
    {
        return Member::findOne([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'birth_date' => date('Y-m-d', strtotime($this->date_of_birth)),
        ]);
    }

    public function getUnregisteredSeniorColumns()
    {
        $columns = parent::getGridColumns();
        $columns['date_of_birth'] = [
            'attribute' => 'date_of_birth',
            'format' => 'raw'
        ];
        $columns['age'] = [
            'attribute' => 'age',
            'format' => 'raw'
        ];
        $columns['custom_action'] = [
            'headerOptions' => ['width' => 170],
            'attribute' => 'id',
            'format' => 'raw',
            'label' => 'ACTION',
            'value' => function($model) {
                $qr_id = $model->member ? $model->member->qr_id : '';
                return Html::a('Create Transaction', 
                    ['transaction/update-profile', 'qr_id' => $qr_id, 'transaction_type' => 'senior-citizen-id-application'],
                    ['class' => 'btn btn-sm btn-outline-primary font-weight-bold']
                );
            }
        ];

        return $columns;
    }


    public static function findByKeywords($keywords='', $attributes, $limit=10, $andFilterWhere=[])
    {
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit, $andFilterWhere) {
            return self::find()
                ->select("{$attribute} AS data")
                ->alias('m')
                ->groupBy('data')
                ->where(['LIKE', $attribute, $keywords])
                ->andFilterWhere($andFilterWhere)
                ->limit($limit)
                ->asArray()
                ->all();
        });
    }
}