<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\models\CivilStatus;
use app\models\Sex;
use app\widgets\Anchor;
use app\widgets\PriorityScoreBadge;
use app\widgets\SocialPensionerDetail;

/**
 * This is the model class for table "{{%social_pensioners}}".
 *
 * @property int $id
 * @property string|null $qr_id
 * @property string $last_name
 * @property string|null $middle_name
 * @property string $first_name
 * @property string $name_suffix
 * @property int $sex
 * @property int $age
 * @property string|null $birth_date
 * @property string|null $birth_place
 * @property int $civil_status
 * @property string|null $email
 * @property string|null $contact_no
 * @property string|null $house_no
 * @property string|null $street
 * @property string|null $barangay
 * @property string|null $sitio
 * @property string|null $purok
 * @property string|null $educational_attainment
 * @property string|null $occupation
 * @property float|null $income
 * @property string|null $source_of_income
 * @property string $date_registered
 * @property string|null $photo
 * @property string|null $documents
 * @property float $pwd_score
 * @property float $senior_score
 * @property float $solo_parent_score
 * @property float $solo_member_score
 * @property float $accessibility_score
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class SocialPensioner extends ActiveRecord
{
    const PENDING = 0;
    const ADDED = 1;

    const PWD_SCORE = 0.2;
    const SENIOR_SCORE = 0.2;
    const SOLO_PARENT_SCORE = 0.2;
    const SOLO_MEMBER_SCORE = 0.25;
    const ACCESSIBILITY_SCORE = 0.15;

    public $priority_score;

    public $is_pwd = false;
    public $is_senior = false;
    public $is_solo_parent = false;
    public $is_solo_member = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%social_pensioners}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'social-pensioner',
            'mainAttribute' => 'fullname',
            'paramName' => 'slug',
            'dateAttribute' => 'date_registered'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['last_name', 'first_name', 'sex', 'date_registered', 'civil_status', 'birth_date', 'birth_place', 'barangay'], 'required'],
            [['sex', 'age', 'civil_status', 'status'], 'integer'],
            [['birth_date', 'date_registered', 'is_pwd', 'is_senior', 'is_solo_parent', 'is_solo_member', 'documents'], 'safe'],
            [['birth_place', 'photo'], 'string'],
            [['income', 'pwd_score', 'senior_score', 'solo_parent_score', 'solo_member_score', 'accessibility_score'], 'number'],
            [['qr_id'], 'string', 'max' => 128],
            [['last_name', 'middle_name', 'first_name', 'email', 'contact_no', 'other_contact_no', 'educational_attainment', 'occupation', 'source_of_income'], 'string', 'max' => 255],
            [['name_suffix'], 'string', 'max' => 16],
            [['house_no', 'barangay', 'sitio', 'purok'], 'string', 'max' => 32],
            [['street'], 'string', 'max' => 64],
            [['email'], 'trim'],
            [['email'], 'email'],
            [
                'sex', 'in', 
                'range' => array_keys(Sex::dropdown('value', 'label'))
            ],
            [
                'civil_status', 'in', 
                'range' => array_keys(CivilStatus::dropdown('value', 'label')),
            ],
            [
                'status', 'in', 
                'range' => array_keys(App::keyMapParams('masterlist_status')),
            ],
            [['first_name', 'last_name', 'middle_name', 'birth_date'], 'validateExistense'],
            [['qr_id'], 'validateQr'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'qr_id' => 'QR ID',
            'last_name' => 'Last Name',
            'middle_name' => 'Middle Name',
            'first_name' => 'First Name',
            'name_suffix' => 'Name Suffix',
            'sex' => 'Sex',
            'age' => 'Age',
            'birth_date' => 'Birth Date',
            'birth_place' => 'Birth Place',
            'civil_status' => 'Civil Status',
            'email' => 'Email',
            'contact_no' => 'Contact No',
            'house_no' => 'House No',
            'street' => 'Street',
            'barangay' => 'Barangay',
            'sitio' => 'Sitio',
            'purok' => 'Purok',
            'educational_attainment' => 'Educational Attainment',
            'occupation' => 'Occupation',
            'income' => 'Income',
            'source_of_income' => 'Source Of Income',
            'date_registered' => 'Date Registered',
            'photo' => 'Photo',
            'documents' => 'Documents',
            'pwd_score' => 'PWD Score',
            'senior_score' => 'Senior Citizen Score',
            'solo_parent_score' => 'Solo Parent Score',
            'solo_member_score' => 'Solo Member Score',
            'accessibility_score' => 'Accessibility Score',
            'is_pwd' => 'PWD',
            'is_senior' => 'Senior',
            'is_solo_parent' => 'Solo Parent',
            'is_solo_member' => 'Solo Member',
            'is_accessibility' => 'Proximity & Accessibility',
            'statusBadge' => 'Status',
            'priorityScore' => 'Total Priority Score',
            'educationalAttainmentLabel' => 'Educational Attainment',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\SocialPensionerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\SocialPensionerQuery(get_called_class());
    }

    public function validateQr($attribute, $params)
    {
        if ($this->isNewRecord) {
            $model = self::findOne(['qr_id' => $this->qr_id]);

            if ($model) {
                $this->addError($attribute, 'QR Id exist.');
            }
        }
        else {
            $model = self::find()
                ->where(['qr_id' => $this->qr_id])
                ->andWhere(['<>', 'id', $this->id])
                ->one();

            if ($model) {
                $this->addError($attribute, 'QR Id exist.');
            }
        }
    }

    public function validateExistense($attribute, $params)
    {
        if ($this->isNewRecord) {
            $model = self::findOne([
                'first_name' => $this->first_name, 
                'last_name' => $this->last_name, 
                'middle_name' => $this->middle_name, 
                'birth_date' => $this->birth_date, 
            ]);

            if ($model) {
                $this->addError($attribute, 'Social Pensioner exist.');
            }
        }
        else {
            $model = self::find()
                ->where([
                    'first_name' => $this->first_name, 
                    'last_name' => $this->last_name, 
                    'middle_name' => $this->middle_name, 
                    'birth_date' => $this->birth_date, 
                ])
                ->andWhere(['<>', 'id', $this->id])
                ->one();

            if ($model) {
                $this->addError($attribute, 'Social Pensioner exist.');
            }
        }
    }

    public function getFullname()
    {
        return implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->name_suffix,
        ]));
    }

    public function getFooterGridColumns()
    {
        $columns = parent::getFooterGridColumns();
        unset($columns['created_at']);
        return $columns;
    }

    public function getStatusBadge()
    {
        $status = App::params('masterlist_status')[$this->status];

        return Html::tag('label', $status['label'], [
            'class' => 'badge badge-' . $status['class']
        ]);
    }

    public function getStatusLabel()
    {
        return App::keyMapParams('masterlist_status')[$this->status] ?? '';
    }

    public function exportColumns()
    {
        return [
            'fullname' => [
                'label' => 'FULLNAME',
                'attribute' => 'first_name', 
                'format' => 'raw',
                'value' => 'fullname'
            ],
            'sex' => ['attribute' => 'sex', 'format' => 'raw', 'value' => 'genderName'],
            'age' => ['attribute' => 'age', 'format' => 'raw'],
            'birth_date' => ['attribute' => 'birth_date', 'format' => 'raw'],
            'birth_place' => ['attribute' => 'birth_place', 'format' => 'raw'],
            'civil_status' => ['attribute' => 'civil_status', 'format' => 'raw', 'value' => 'civilStatusName'],
            'email' => ['attribute' => 'email', 'format' => 'raw'],
            'contact_no' => ['attribute' => 'contact_no', 'format' => 'raw'],
            'house_no' => ['attribute' => 'house_no', 'format' => 'raw'],
            'street' => ['attribute' => 'street', 'format' => 'raw'],
            'barangay' => ['attribute' => 'barangay', 'format' => 'raw'],
            'sitio' => ['attribute' => 'sitio', 'format' => 'raw'],
            'purok' => ['attribute' => 'purok', 'format' => 'raw'],
            'educational_attainment' => ['attribute' => 'educational_attainment', 'format' => 'raw'],
            'occupation' => ['attribute' => 'occupation', 'format' => 'raw'],
            'income' => ['attribute' => 'income', 'format' => 'raw'],
            'source_of_income' => ['attribute' => 'source_of_income', 'format' => 'raw'],
            'date_registered' => ['attribute' => 'date_registered', 'format' => 'raw'],
            // 'photo' => ['attribute' => 'photo', 'format' => 'raw'],
            // 'documents' => ['attribute' => 'documents', 'format' => 'raw'],
            'pwd_score' => ['attribute' => 'pwd_score', 'format' => 'number'],
            'senior_score' => ['attribute' => 'senior_score', 'format' => 'number'],
            'solo_parent_score' => ['attribute' => 'solo_parent_score', 'format' => 'number'],
            'solo_member_score' => ['attribute' => 'solo_member_score', 'format' => 'number'],
            'accessibility_score' => ['attribute' => 'accessibility_score', 'format' => 'number'],
            'priority_score' => ['attribute' => 'priorityScore', 'format' => 'number', 'value' => 'priorityScore'],
            'status' => ['attribute' => 'status', 'format' => 'raw', 'value' => 'statusLabel'],
        ];
    }

    public function gridColumns()
    {
        return [
            'fullname' => [
                'attribute' => 'first_name', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->fullname,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            // 'last_name' => ['attribute' => 'last_name', 'format' => 'raw'],
            // 'middle_name' => ['attribute' => 'middle_name', 'format' => 'raw'],
            // 'first_name' => ['attribute' => 'first_name', 'format' => 'raw'],
            // 'name_suffix' => ['attribute' => 'name_suffix', 'format' => 'raw'],
            'sex' => ['attribute' => 'sex', 'format' => 'raw', 'value' => 'genderName'],
            'age' => ['attribute' => 'age', 'format' => 'raw'],
            // 'birth_date' => ['attribute' => 'birth_date', 'format' => 'raw'],
            // 'birth_place' => ['attribute' => 'birth_place', 'format' => 'raw'],
            'civil_status' => ['attribute' => 'civil_status', 'format' => 'raw', 'value' => 'civilStatusName'],
            // 'email' => ['attribute' => 'email', 'format' => 'raw'],
            // 'contact_no' => ['attribute' => 'contact_no', 'format' => 'raw'],
            // 'house_no' => ['attribute' => 'house_no', 'format' => 'raw'],
            // 'street' => ['attribute' => 'street', 'format' => 'raw'],
            'barangay' => ['attribute' => 'barangay', 'format' => 'raw'],
            // 'sitio' => ['attribute' => 'sitio', 'format' => 'raw'],
            'purok' => ['attribute' => 'purok', 'format' => 'raw'],
            'priority_score' => ['attribute' => 'priority_score', 'format' => 'raw', 'value' => 'priorityScoreBadge'],
            // 'educational_attainment' => ['attribute' => 'educational_attainment', 'format' => 'raw'],
            // 'occupation' => ['attribute' => 'occupation', 'format' => 'raw'],
            // 'income' => ['attribute' => 'income', 'format' => 'raw'],
            // 'source_of_income' => ['attribute' => 'source_of_income', 'format' => 'raw'],
            'date_registered' => ['attribute' => 'date_registered', 'format' => 'raw'],
            'status' => ['attribute' => 'status', 'format' => 'raw', 'value' => 'statusBadge'],
            // 'photo' => ['attribute' => 'photo', 'format' => 'raw'],
            // 'documents' => ['attribute' => 'documents', 'format' => 'raw'],
            // 'pwd_score' => ['attribute' => 'pwd_score', 'format' => 'raw'],
            // 'senior_score' => ['attribute' => 'senior_score', 'format' => 'raw'],
            // 'solo_parent_score' => ['attribute' => 'solo_parent_score', 'format' => 'raw'],
            // 'solo_member_score' => ['attribute' => 'solo_member_score', 'format' => 'raw'],
            // 'accessibility_score' => ['attribute' => 'accessibility_score', 'format' => 'raw'],
        ];
    }

    public function getDetailView()
    {
        return SocialPensionerDetail::widget([
            'model' => $this
        ]);
    }

    public function detailColumns()
    {
        return [
            'statusBadge:raw',
            'profilePhoto:raw',
            'qr_id:raw',
            'last_name:raw',
            'middle_name:raw',
            'first_name:raw',
            'name_suffix:raw',
            'genderName:raw',
            'age:raw',
            'birth_date:raw',
            'birth_place:raw',
            'civilStatusName:raw',
            'email:raw',
            'contact_no:raw',
            'house_no:raw',
            'street:raw',
            'barangay:raw',
            'sitio:raw',
            'purok:raw',
            'educationalAttainmentLabel:raw',
            'occupation:raw',
            'income:raw',
            'source_of_income:raw',
            'date_registered:raw',
            'documentViews:raw',
            'pwd_score:number',
            'senior_score:number',
            'solo_parent_score:number',
            'solo_member_score:number',
            'accessibility_score:number',
            'priorityScore:number',
        ];
    }

    public function getPriorityScoreBadge()
    {
        return PriorityScoreBadge::widget([
            'model' => $this
        ]);
    }

    public function getPwdScore()
    {
        return App::formatter('asNumber', $this->pwd_score);
    }

    public function getSeniorScore()
    {
        return App::formatter('asNumber', $this->senior_score);
    }

    public function getSoloParentScore()
    {
        return App::formatter('asNumber', $this->solo_parent_score);
    }

    public function getSoloMemberScore()
    {
        return App::formatter('asNumber', $this->solo_member_score);
    }

    public function getAccessibilityScore()
    {
        return App::formatter('asNumber', $this->accessibility_score);
    }

    public function getPriorityScore()
    {
        return array_sum([
            $this->pwd_score,
            $this->senior_score,
            $this->solo_parent_score,
            $this->solo_member_score,
            $this->accessibility_score,
        ]);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['JsonBehavior']['fields'] = [
            'documents', 
        ];


        $behaviors['DateBehavior'] = [
            'class' => 'app\behaviors\DateBehavior',
            'attributes' => [
                'birth_date',
                'date_registered'
            ]
        ];

        $behaviors['SocialPensionerBehavior'] = [
            'class' => 'app\behaviors\SocialPensionerBehavior',
        ];

        $behaviors['AgeBehavior'] = [
            'class' => 'app\behaviors\AgeBehavior',
            'condition' => function($model) {
                return $model->isActive;
            }
        ];

        $behaviors['SluggableBehavior'] = [
            'class' => 'yii\behaviors\SluggableBehavior',
            'attribute' => ['first_name', 'last_name'],
            'ensureUnique' => true,
        ];

        return $behaviors;
    }


    public function getDocumentFiles()
    {
        if (($token = $this->documents) != null) {
            $files = File::find()
                ->where(['token' => $token])
                ->orderBy(['id' => SORT_DESC])
                ->all();

            return $files;
        }
    }

    public function getBarangayModel()
    {
        return $this->hasOne(Barangay::className(), ['name' => 'barangay']);
    }

    public function getBarangayScore()
    {
        if (($barangay = $this->barangayModel) != null) {
            return $barangay->priority_score;
        }
    }

    public function computeAccessibilityScore()
    {
        if (($barangayScore = $this->barangayScore) != null) {
            $maxScore = Barangay::find()
                ->max('priority_score');

            $score = ($barangayScore * self::ACCESSIBILITY_SCORE) / $maxScore;
            
            return round($score, 4);
        }

        return 0;
    }

    public function getGender()
    {
        return $this->hasOne(Sex::className(), ['value' => 'sex'])
            ->onCondition(['var' => Sex::VAR]);
    }

    public function getGenderName()
    {
        if (($model = $this->gender) != null) {
            return $model->label;
        }
    }

    public function getCivilStatus()
    {
        return $this->hasOne(CivilStatus::className(), ['value' => 'civil_status'])
            ->onCondition(['var' => CivilStatus::VAR]);
    }

    public function getCivilStatusName()
    {
        if (($model = $this->civilStatus) != null) {
            return $model->label;
        }
    }

    public function getEducationalAttainment()
    {
        return $this->hasOne(EducationalAttainment::className(), ['value' => 'educational_attainment'])
            ->onCondition(['var' => EducationalAttainment::VAR]);
    }

    public function getEducationalAttainmentLabel()
    {
        if (($model = $this->educationalAttainment) != null) {
            return $model->label;
        }
    }

    public function getBulkActions()
    {
        $columns = [];

        $columns['add-to-masterlist'] = [
            'label' => 'Add to Masterlist',
            'process' => 'add-to-masterlist',
            'icon' => 'plus',
            'function' => function($id) {
                self::updateAll(['status' => self::ADDED], ['id' => $id]);
            }
        ];

        if (App::isLogin() && App::identity()->can('delete', $this->controllerID())) {
            $columns['delete'] = [
                'label' => 'Delete',
                'process' => 'delete',
                'icon' => 'delete',
                'function' => function($id) {
                    self::deleteAll(['id' => $id]);
                }
            ];
        }
        
        return $columns;
    }


    public function getProfilePhoto()
    {
        return Html::image($this->photo, ['w' => 100], [
            'class' => 'img-fluid img-thumbnail'
        ]);
    }

    public function getDocumentViews()
    {
        if (($tokens = $this->documents) != null) {
            return Html::foreach($tokens, function($token) {
                return Html::image($token, ['w' => 100], [
                    'class' => 'img-fluid'
                ]);
            });
        }
    }

    public function getStartDate($from_database = false)
    {
        if ($this->date_range && $from_database == false) {
            $date = App::formatter()->asDaterangeToSingle($this->date_range, 'start');
            return date('F d, Y', strtotime($date));
        }
        else {
            if ($this->_startDate === null) {
                $this->_startDate = self::find()
                    ->where(['status' => self::PENDING])
                    ->visible()
                    ->min($this->dateAttribute);
            }
            $date = $this->_startDate ?: 'today';
        }

        return App::formatter()->asDateToTimezone($date, 'F d, Y');
    }

    public function getEndDate($from_database = false)
    {
        if ($this->date_range && $from_database == false) {
            $date = App::formatter()->asDaterangeToSingle($this->date_range, 'end');
            return date('F d, Y', strtotime($date));
        }
        else {
            if ($this->_endDate === null) {
                $this->_endDate = self::find()
                    ->where(['status' => self::PENDING])
                    ->visible()
                    ->max($this->dateAttribute);
            }
            $date = ($this->_endDate)? $this->_endDate: 'today';
        }

        return App::formatter()->asDateToTimezone($date, 'F d, Y');
    }


    public static function findByKeywords($keywords='', $attributes, $limit=10, $andFilterWhere=[])
    {
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit, $andFilterWhere) {
            return static::find()
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

    public function addToMasterlist()
    {
        $this->status = self::ADDED;
        return $this->save();
    }
}