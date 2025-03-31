<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Url;
use app\widgets\Anchor;
use app\widgets\Label;

/**
 * This is the model class for table "{{%scholarships}}".
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string|null $name_suffix
 * @property string|null $birth_date
 * @property int|null $age
 * @property string|null $course
 * @property int $barangay_id
 * @property string|null $street_address
 * @property string|null $email
 * @property string|null $alternate_email
 * @property string|null $contact_no
 * @property string|null $alternate_contact_no
 * @property string|null $house_no
 * @property string|null $guardian
 * @property int|null $first_enrollment
 * @property int|null $expected_graduation
 * @property int|null $current_year_level
 * @property string|null $school_name
 * @property string|null $subjects
 * @property string|null $units
 * @property string|null $documents
 * @property string|null $photo
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Scholarship extends ActiveRecord
{
    const MALE = 0;
    const FEMALE = 1;

    const PENDING_DRAFT = 0;
    const FOR_INTERVIEW = 1;
    const REJECTED = 2;
    const APPROVED = 3;

    const STEP_FORM = [
        1 => [
            'id' => 1,
            'slug' => 'general-information',
            'title' => 'General Information',
            'description' => 'Personal Details',
        ],
        2 => [
            'id' => 2,
            'slug' => 'educations',
            'title' => 'Educations',
            'description' => 'Year / Course',
        ],
        3 => [
            'id' => 3,
            'slug' => 'documents',
            'title' => 'Requirements | Photos',
            'description' => 'Requirements & Photos',
        ],
        4 => [
            'id' => 4,
            'slug' => 'review',
            'title' => 'Summary',
            'description' => 'Review & Submit',
        ],
    ];

    const SCENARIO_INTERVIEW = 'interview';

    public function fields()
    {
        $fields = parent::fields();
        $fields['year_level'] = fn ($model) => App::formatter('asOrdinal', $model->current_year_level);

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%scholarships}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'scholarship',
            'mainAttribute' => 'fullname',
            'paramName' => 'token',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['first_name', 'last_name', 'barangay_id', 'birth_date', 'sex'], 'required'],
            [['educations', 'documents', 'notes', 'interview_date', 'interview_attachments', 'interviewer'], 'safe'],

            [['interview_date', 'interviewer'], 'required', 'on' => self::SCENARIO_INTERVIEW],

            [['age', 'barangay_id', 'first_enrollment', 'expected_graduation', 'current_year_level', 'sex', 'status'], 'integer'],
            [['subjects', 'units'], 'string'],
            [['first_name', 'middle_name', 'last_name', 'course', 'street_address', 'email', 'alternate_email', 'contact_no', 'alternate_contact_no', 'house_no', 'guardian', 'school_name', 'photo', 'interview_date', 'interviewer'], 'string', 'max' => 255],
            [['name_suffix'], 'string', 'max' => 16],
            ['barangay_id', 'exist', 'targetRelation' => 'barangay', 'when' => fn($model) => $model->barangay_id],
            ['sex', 'in', 'range' => [
                self::MALE,
                self::FEMALE,
            ]],
            ['age', 'integer', 'min' => 0],
            ['birth_date', 'validateBirthDate'],
            [['email', 'alternate_email'], 'email'],
            [['email', 'alternate_email', 'contact_no', 'alternate_contact_no'], 'trim'],
            ['status', 'in', 'range' => [
                self::PENDING_DRAFT,
                self::FOR_INTERVIEW,
                self::REJECTED,
                self::APPROVED,
            ]]
        ]);
    }

    public function beforeValidate()
    {
        if (! parent::beforeValidate()) {
            return false;
        }

        $this->age = App::formatter()->asAge($this->birth_date);

        if ($this->educations && is_array($this->educations)) {
            $educations = $this->educations;
            $education = end($educations);
            $this->course = $education['course'] ?? '';
            $this->current_year_level = $education['year_level'] ?? '';
            $this->school_name = $education['school_name'] ?? '';
            $this->school_year = $education['school_year'] ?? '';
        }

        return true;
    }

    public function beforeSave($insert) 
    {
        if (! parent::beforeSave($insert)) {
            return false;
        }

        if ($this->educations) {
        }

        return true;
    }

    public function validateBirthDate($attribute, $params)
    {
        $current_date = strtotime(App::formatter()->asDateToTimezone('', 'Y-m-d'));
        $birth_date = strtotime($this->birth_date);

        if ($birth_date > $current_date) {
            $this->addError('birth_date', 'Birthdate is greater than current date');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'name_suffix' => 'Name Suffix',
            'birth_date' => 'Birth Date',
            'age' => 'Age',
            'course' => 'Course',
            'barangay_id' => 'Barangay',
            'street_address' => 'Street Address',
            'email' => 'Email',
            'alternate_email' => 'Alternate Email',
            'contact_no' => 'Contact No',
            'alternate_contact_no' => 'Alternate Contact No',
            'house_no' => 'House No',
            'guardian' => 'Guardian',
            'first_enrollment' => 'First Enrollment',
            'expected_graduation' => 'Expected Graduation',
            'current_year_level' => 'Current Year Level',
            'school_name' => 'School Name',
            'subjects' => 'Subjects',
            'units' => 'Units',
            'documents' => 'Documents',
            'photo' => 'Photo',
            'barangayName' => 'Barangay',
            'fullname' => 'Full Name'
        ]);
    }

    public function getBarangay()
    {
        //return $this->hasOne(HazardMap::class, ['id' => 'barangay_id'])->onCondition(['type' => HazardMap::BARANGAY]);
        
         return $this->hasOne(Barangay::class, ['id' => 'barangay_id']);
    }

    public function getBarangayName()
    {
        return App::if($this->barangay, fn ($barangay) => $barangay->name);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\ScholarshipQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ScholarshipQuery(get_called_class());
    }

    public function getDefaultGridColumns()
    {
        return [
            'serial',
            'checkbox',
            'fullname',
            'birth_date',
            // 'age',
            'email',
            'contact_no',
            // 'house_no',
            // 'current_year_level',
            'school_name',
            'course',
            'barangay_name',
            'active',
            'created_at',
            'last_updated'
        ];
    }

    public function getName()
    {
        return App::formatter()->asUcWords(implode(' ', array_filter([
            $this->first_name,
            ($this->middle_name ? substr($this->middle_name, 0, 1) . '.': ''),
            $this->last_name,
            $this->name_suffix,
        ])));
    }

    public function getFullname()
    {
        return App::formatter()->asUcWords(implode(' ', [
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->name_suffix,
        ]));
    }

    public function getFooterGridColumns()
    {
        $columns = parent::getFooterGridColumns();

        if (App::isLogin() && App::identity()->can('in-active-data', $this->controllerID())) {
            $columns['active'] = [
                'attribute' => 'record_status',
                'label' => 'active',
                'format' => 'raw', 
                'value' => 'recordStatusBadge'
            ];
        }
        
        return $columns;
    }
     
    public function gridColumns()
    {
        return [
            'fullname' => [
                'attribute' => 'fullname', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->fullname,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
          
            'birth_date' => ['attribute' => 'birth_date', 'format' => 'raw'],
            'age' => ['attribute' => 'age', 'format' => 'raw'],
            'course' => ['attribute' => 'course', 'format' => 'raw'],
            
            'street_address' => ['attribute' => 'street_address', 'format' => 'raw'],
            'email' => ['attribute' => 'email', 'format' => 'raw'],
            'alternate_email' => ['attribute' => 'alternate_email', 'format' => 'raw'],
            'contact_no' => ['attribute' => 'contact_no', 'format' => 'raw'],
            'alternate_contact_no' => ['attribute' => 'alternate_contact_no', 'format' => 'raw'],
            'house_no' => ['attribute' => 'house_no', 'format' => 'raw'],
            'guardian' => ['attribute' => 'guardian', 'format' => 'raw'],
            'first_enrollment' => ['attribute' => 'first_enrollment', 'format' => 'raw'],
            'expected_graduation' => ['attribute' => 'expected_graduation', 'format' => 'raw'],
            'current_year_level' => ['attribute' => 'current_year_level', 'format' => 'raw'],
            'school_name' => ['attribute' => 'school_name', 'format' => 'raw'],
            'barangay_name' => [
                'attribute' => 'barangayName', 
                'format' => 'raw'
            ],
            'status' => [
                'attribute' => 'status', 
                'value' => 'statusBadge',
                'format' => 'raw'
            ],
            // 'subjects' => ['attribute' => 'subjects', 'format' => 'raw'],
            // 'units' => ['attribute' => 'units', 'format' => 'raw'],
            // 'documents' => ['attribute' => 'documents', 'format' => 'raw'],
            // 'photo' => ['attribute' => 'photo', 'format' => 'raw'],
        ];
    }

    public function getIsPending()
    {
        return $this->status == self::PENDING_DRAFT;
    }

    public function getStatusLabel()
    {
        $data = App::params('scholarship_status')[$this->status];

        return $data['label'] ?? '';
    }

    public function getStatusBadge($addClass='')
    {
        $options = App::params('scholarship_status')[$this->status];
        $options['class'] .= ' ' . $addClass;

        return Label::widget(['options' => $options]);
    }

    public function detailColumns()
    {
        return [
            'first_name:raw',
            'middle_name:raw',
            'last_name:raw',
            'name_suffix:raw',
            'birth_date:raw',
            'age:raw',
            'course:raw',
            'barangay_id:raw',
            'street_address:raw',
            'email:raw',
            'alternate_email:raw',
            'contact_no:raw',
            'alternate_contact_no:raw',
            'house_no:raw',
            'guardian:raw',
            'first_enrollment:raw',
            'expected_graduation:raw',
            'current_year_level:raw',
            'school_name:raw',
            // 'subjects:raw',
            // 'units:raw',
            // 'documents:raw',
            // 'photo:raw',
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['JsonBehavior']['fields'] = [
            'educations', 
            'documents',
            'notes',
            'interview_attachments'
        ];

        $behaviors['DateBehavior'] = [
            'class' => 'app\behaviors\DateBehavior',
            'inFormat' => 'Y-m-d',
            'outFormat' => 'm/d/Y',
            'attributes' => [
                'birth_date',
            ]
        ];

        return $behaviors;
    }

    public function getImageFiles()
    {
        return File::findAll(['token' => $this->documents]);
    }

    public function getSexLabel()
    {
        return $this->sex == self::MALE ? 'Male': 'Female';
    }

    public function getAllowances()
    {
        return $this->hasMany(Allowance::class, ['scholarship_id' => 'id']);
    }

    public function getRecentAllowances($limit=7)
    {
        return Allowance::find()
            ->where([
                'scholarship_id' => $this->id
            ])
            ->limit($limit)
            ->all();
    }

    public function getTotalAllowance()
    {
        $data = Allowance::find()
            ->select(['SUM(amount) AS total'])
            ->where(['scholarship_id' => $this->id])
            ->asArray()
            ->one();

        return $data['total'] ?? 0;
    }

    public function getSaveNotesUrl()
    {
        return Url::toRoute(['scholarship/save-notes', 'token' => $this->token]);
    }

    public function getInterviewAttachmentFiles()
    {
        return File::findAll([
            'token' => $this->interview_attachments
        ]);
    }
}