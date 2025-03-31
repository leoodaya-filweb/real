<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Url;
use app\helpers\Html;
use app\widgets\Anchor;
use app\widgets\QRCode;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;
use app\widgets\MemberDetail;
use app\models\search\LogSearch;
use app\widgets\EligibleForAicsNotice;
use app\models\search\EventMemberSearch;
use app\models\search\TransactionSearch;
use app\models\form\TransferToNewHouseholdForm;
use app\models\form\TransferToExistingHouseholdForm;

/**
 * This is the model class for table "{{%members}}".
 *
 * @property int $id
 * @property int $household_id
 * @property int $no
 * @property string $last_name
 * @property string|null $middle_name
 * @property string $first_name
 * @property int $sex
 * @property string $birth_date
 * @property int $civil_status
 * @property string|null $email
 * @property string|null $contact_no
 * @property string|null $photo
 * @property string $token
 * @property string $slug
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Member extends ActiveRecord
{
    const PWD_YES = 1;
    const PWD_NO = 2;

    const VOTER_YES = 1;
    const VOTER_NO = 2;

    const SOLO_MEMBER_YES = 1;
    const SOLO_MEMBER_NO = 2;

    const SOLO_PARENT_YES = 1;
    const SOLO_PARENT_NO = 2;

    const ALIVE = 1;
    const DECEASED = 2;

    const FAMILY_HEAD_YES = 1;
    const FAMILY_HEAD_NO = 0;
    const PENSIONER = 1;
    const NOT_PENSIONER = 0;
  
    const NOT_ATTEND_SCHOOL = 1;
    const ATTEND_SCHOOL = 2;

    const NO_4PS = 0;
    const YES_4PS = 1;

    const VIEW_TABS = [
        1 => [
            'id' => 1,
            'slug' => 'overview',
            'title' => 'Overview',
            'description' => 'Statistical Data',
            'icon' => 'f-chart'
        ],
        2 => [
            'id' => 2,
            'slug' => 'household',
            'title' => 'Household Details',
            'description' => 'Household General Details',
            'icon' => 'f-home'
        ],
        3 => [
            'id' => 3,
            'slug' => 'personal-information',
            'title' => 'Personal Information',
            'description' => 'Primary Details',
            'icon' => 'f-user'
        ],
        4 => [
            'id' => 4,
            'slug' => 'family-composition',
            'title' => 'Family Composition',
            'description' => 'Family members included',
            'icon' => 'f-users'
        ],
        5 => [
            'id' => 5,
            'slug' => 'transactions',
            'title' => 'Transactions Records',
            'description' => 'Recorded transactions list',
            'icon' => 'f-hand-shake'
        ],
        // 6 => [
        //     'id' => 6,
        //     'slug' => 'certificates',
        //     'title' => 'Certificates',
        //     'description' => 'Certificates',
        //     'icon' => 'f-file'
        // ],
        // 7 => [
        //     'id' => 7,
        //     'slug' => 'social-case-study-report',
        //     'title' => 'Social Case Study Report',
        //     'description' => 'Social Case Study Report',
        //     'icon' => 'f-file'
        // ],
        8 => [
            'id' => 8,
            'slug' => 'event',
            'title' => 'Events',
            'description' => 'Claimed/Attended Events',
            'icon' => 'calendar'
        ],
        9 => [
            'id' => 9,
            'slug' => 'senior-citizen-id',
            'title' => 'Senior Citizen ID',
            'description' => 'Senior Citizen Identification Card',
            'icon' => 'fa-address-card'
        ],
        11 => [
            'id' => 11,
            'slug' => 'update-logs',
            'title' => 'Update Logs',
            'description' => 'Log of who, what, and when the profile update happened',
            'icon' => '<i class="fa fa-edit"></i>'
        ],
        12 => [
            'id' => 12,
            'slug' => 'id-cards',
            'title' => 'Identification Cards',
            'description' => 'Member Identification Cards',
            'icon' => 'f-file'
        ],
        10 => [
            'id' => 10,
            'slug' => 'documents',
            'title' => 'Other Documents',
            'description' => 'Member Documents',
            'icon' => 'f-file'
        ],
    ];

    const NOT_SOCIAL_PENSIONER = 0;
    const SOCIAL_PENSIONER = 1;

    public $household_no;
    public $barangay_name;
    public $purok_no;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%members}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'member',
            'mainAttribute' => 'name',
            'paramName' => 'qr_id',
            'relatedModels' => ['eventMembers', 'transactions']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['relation'], 'required', 'on' => 'family-composition'],
            // [['qr_id'], 'required', 'on' => ['family-composition', 'family-head']],
            [['relation', 'age'], 'integer'],
            [['age'], 'default', 'value' => 0],

            [['household_id', 'last_name', 'first_name', 'sex', 'birth_date', 'civil_status'], 'required'],

            [['head'], 'default', 'value' => self::FAMILY_HEAD_NO],
            [['household_id', 'sex', 'civil_status', 'pensioner', 'head', 'educational_attainment', 'living_status', 'solo_parent', 'pwd', 'pwd_type'], 'integer'],
            [['birth_date', 'birth_place', 'occupation', 'telephone_no', 'senior_citizen_id'], 'safe'],
            [['photo'], 'string'],
            [['last_name', 'middle_name', 'first_name', 'email', 'contact_no', 'source_of_income', 'pensioner_from','arc_no'], 'string', 'max' => 255],
            [['qr_id'], 'string', 'max' => 128],

            [['income', 'pension_amount'], 'number'],
            [['email'], 'email'],
            [['email', 'contact_no'], 'trim'],
            ['contact_no', 'match', 'pattern' => '/^(09|\+639)\d{9}$/', 'when' => function($model) {
                return $model->contact_no;
            }],
            [
                'sex', 'in', 
                'range' => array_keys(Sex::dropdown('value', 'label'))
            ],
            [
                'pwd_type', 'in', 
                'range' => array_keys(PwdType::dropdown('value', 'label')),
                'on' => ['family-composition', 'family-head'],
                'when' => function($model) {
                    return $model->pwd_type;
                }
            ],
            [
                'civil_status', 'in', 
                'range' => array_keys(CivilStatus::dropdown('value', 'label')),
                'on' => ['family-composition', 'family-head']
            ],
            [
                'pwd', 'in', 
                'range' => array_keys(App::keyMapParams('pwd')),
                'on' => ['family-composition', 'family-head']
            ],
            [
                'solo_parent', 'in', 
                'range' => array_keys(App::keyMapParams('solo_parent')),
                'on' => ['family-composition', 'family-head']
            ],
            [
                'head', 'in', 
                'range' => array_keys(App::keyMapParams('family_head'))
            ],

            ['pensioner', 'in', 'range' => array_keys(App::keyMapParams('pensioners'))],

            [
                'educational_attainment', 'in', 
                'range' => array_keys(EducationalAttainment::dropdown('value', 'label')),
                'on' => ['family-composition', 'family-head']
            ],

            [
                'living_status', 'in', 
                'range' => array_keys(App::keyMapParams('living_status')),
                'on' => ['family-composition', 'family-head']
            ],

            [
                'relation', 'in', 
                'range' => array_keys(Relation::dropdown('value', 'label')),
                'on' => ['family-composition']
            ],
            ['birth_date', 'validateBirthDate'],

            [['birth_place'], 'required', 'on' => ['family-composition', 'family-head']],
            ['household_id', 'exist', 'targetRelation' => 'household',],

            [
                ['pensioner_from', 'pension_amount'], 
                'required', 
                'when' => function($model) {
                    return $model->pensioner == self::PENSIONER;
                }, 
                'whenClient' => "function (attribute, value) {
                    return $('#member-pensioner').val() == 1;
                }",
                'on' => ['family-composition', 'family-head']
            ],
            [['documents', 'whitecard_file', 'id_cards'], 'safe'],
            [['skills'], 'safe'],
            ['fourPs', 'in', 'range' => array_keys(App::keyMapParams('fourPs'))],
            ['fourPs', 'integer'],
            ['fourPs', 'default', 'value' => self::NO_4PS],
            ['social_pension_status', 'integer'],
            ['social_pension_status', 'in', 'range' => array_keys(App::keyMapParams('social_pension_status'))],
            ['social_pension_status', 'default', 'value' => self::NOT_SOCIAL_PENSIONER],
            [['voter', 'solo_member'], 'integer'],
            [['voter'], 'default', 'value' => self::VOTER_NO],
            [['solo_member'], 'default', 'value' => self::SOLO_MEMBER_NO],
            ['voter', 'in', 'range' => array_keys(App::keyMapParams('voters'))],
            ['solo_member', 'in', 'range' => array_keys(App::keyMapParams('solo_member'))],
        ]);
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['fullname'] = 'fullname';
        $fields['widgetTags'] = 'widgetTags';
        $fields['viewUrl'] = 'viewUrl';
        $fields['createUrl'] = 'createUrl';
        $fields['updateUrl'] = 'updateUrl';
        $fields['viewUrlPersonalInformationTab'] = 'viewUrlPersonalInformationTab';
        $fields['sexLabel'] = 'sexLabel';
        $fields['address'] = 'address';

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'household_id' => 'Household Id',
            'no' => 'No',
            'last_name' => 'Last Name',
            'middle_name' => 'Middle Name',
            'first_name' => 'First Name',
            'sex' => 'Sex',
            'birth_date' => 'Birth Date',
            'birth_place' => 'Birth Place',
            'civil_status' => 'Civil Status',
            'email' => 'Email',
            'contact_no' => 'Contact No',
            'photo' => 'Photo',
            'qr_id' => 'QR Code ID',
            'sexLabel' => 'Sex',
            'civilStatusName' => 'Civil Status',
            'educationalAttainmentLabel' => 'Educational Attainment',
            'educational_attainment' => 'Educational Attainment',
            'currentAge' => 'Age',
            'pwdLabel' => 'PWD',
            'pwdTypeName' => 'PWD Type',
            'soloParentLabel' => 'Solo Parent',
            'livingStatusLabel' => 'Living Status',
            'fourPs' => '4Ps Member',
            'fourPsLabel' => '4Ps Member',
            'income' => 'Estimated Monthly Income',
            'voterLabel' => 'Registered Voter',
            'soloMemberLabel' => 'Solo Member',
            'arc_no'=>'ARK No.'
        ]);
    }

    public function getIsPwd()
    {
        return $this->pwd == self::PWD_YES;
    }

    public function getIsSoloParent()
    {
        return $this->solo_parent == self::SOLO_PARENT_YES;
    }

    public function getIsSoloMember()
    {
        return $this->solo_member == self::SOLO_MEMBER_YES;
    }
    

    public function validateBirthDate($attribute, $params)
    {
        $today = strtotime(App::formatter()->asDateToTimezone());
        $birth_date = strtotime($this->birth_date);

        if ($birth_date > $today) {
            $this->addError($attribute, 'Birth date is greater than the date today.');
        }
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\MemberQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\MemberQuery(get_called_class());
    }

    public function getGridColumns()
    {
        $columns = parent::getGridColumns();
        unset($columns['active']);

        $columns['status'] = [
            'attribute' => 'record_status',
            'label' => 'status',
            'format' => 'raw', 
            'value' => 'recordStatusHtmlWithConfirmation'
        ];

        return $columns;
    }

    public function getDetailView($withTransactionBtn = true)
    {
        return MemberDetail::widget([
            'model' => $this,
            'withTransactionBtn' => $withTransactionBtn
        ]);
    }

    public function getLivingStatusLabel()
    {
        return App::keyMapParams('living_status')[$this->living_status] ?? '';
    }
     
    public function gridColumns()
    {
        return [
            'photo' => [
                'attribute' => 'photo', 
                'format' => 'raw',
                'value' => function($model) {
                    return Html::tag('div', 
                        Html::image($model->photo, [
                            'w' => 50, 
                            'h' => 50, 
                            'ratio' => 'false', 
                            'quality' => 90]
                        ), [
                            'class' => 'symbol mr-3',
                            'style' => 'width:50px;'
                        ]
                    );
                }
            ],
            'qr_id' => [
                'attribute' => 'qr_id', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->qr_id,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'household_no' => ['attribute' => 'householdNo', 'format' => 'raw'],
            // 'qr_id' => ['attribute' => 'qr_id', 'format' => 'raw'],
            'last_name' => ['attribute' => 'last_name', 'format' => 'raw'],
            'first_name' => ['attribute' => 'first_name', 'format' => 'raw'],
            'suffix'=> ['attribute' => 'suffix', 'format' => 'raw'],
            'middle_name' => ['attribute' => 'middle_name', 'format' => 'raw'],
            'sex' => [
                'attribute' => 'sex', 
                'format' => 'raw',
                'value' => 'genderName'
            ],
            'birth_date' => ['attribute' => 'birth_date', 'format' => 'raw'],
            'age' => ['attribute' => 'age', 'format' => 'raw', 'value' => 'currentAge'],

            // 'birth_place' => ['attribute' => 'birth_place', 'format' => 'raw'],
            // 'civil_status' => [
            //     'attribute' => 'civil_status', 
            //     'value' => 'civilStatusName', 
            //     'format' => 'raw'
            // ],
            
            'barangay' => ['label' => 'Barangay', 'format' => 'raw', 'value' => 'barangayName'],
            
             'ark_no' => ['attribute' => 'arc_no', 'format' => 'raw'],
            // 'contact_no' => ['attribute' => 'contact_no', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'householdNo:raw',
            'qr_id:raw',
            'last_name:raw',
            'middleName:raw',
            'first_name:raw',
            'sexLabel:raw',
            'civilStatusName:raw',
            'educationalAttainmentLabel:raw',
            'birth_date:raw',
            'currentAge:raw',
            'birth_place:raw',
            'email:raw',
            'contact_no:raw',
            'telephone_no:raw',
            'occupation:raw',
            'monthlyIncome:raw',
            'source_of_income:raw',
            'pensionerTag:raw',
            'pensioner_from:raw',
            'monthlyPensionAmount:raw',
            'pwdLabel:raw',
            'pwdTypeName:raw',
            'skillsList:raw',
            'soloParentLabel:raw',
            'livingStatusLabel:raw',
            'fourPsLabel:raw',
        ];
    }

    public function getSexLabel()
    {
        return $this->genderName;
    }

    public function getName()
    {
        return implode(' ', array_filter([
            ucwords(strtolower($this->first_name)),
            ucwords(strtolower($this->last_name)),
        ]));
    }
    
     public function getFullnameinitial()
    {
        $fullname = implode(' ', array_filter([
            $this->first_name,
            ($this->middle_name?substr($this->middle_name, 0, 1).'.':null),
            $this->last_name,
        ]));

        return App::formatter('asClean', $fullname);
    }

    public function getFullname()
    {
        $fullname = implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ]));

        return App::formatter('asClean', $fullname);
    }

    public function getQrCode()
    {
        return QRCode::widget([
            'model' => $this
        ]);
    }

    public function getQrCodeImage($options=[])
    {
        $options = $options ?: [
            'height' => '150', 
            'width' => '150',
            'class' => 'img-thumbnail'
        ];

        return Html::img($this->qrCode, $options);
    }

    public function downloadQrCode()
    {
        App::response()->sendFile($this->qrCode, "{$this->slug}-qrcode.png");
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
      
        $behaviors['SluggableBehavior'] = [
            'class' => 'yii\behaviors\SluggableBehavior',
            'attribute' => ['first_name', 'middle_name', 'last_name'],
            'ensureUnique' => false,
        ];

        $behaviors['JsonBehavior']['fields'] = [
            'id_cards', 
            'documents', 
            'skills'
        ];

        $behaviors['MemberBehavior'] = ['class' => 'app\behaviors\MemberBehavior'];
        $behaviors['DateBehavior'] = [
            'class' => 'app\behaviors\DateBehavior',
            'attributes' => ['birth_date']
        ];

        $behaviors['AgeBehavior'] = [
            'class' => 'app\behaviors\AgeBehavior',
            'condition' => function($model) {
                return $model->isActive;
            }
        ];

        return $behaviors;
    }

    public function getDownloadQrCodeUrl($fullpath=true)
    {
        if ($this->checkLinkAccess('download-qr-code')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'download-qr-code']),
                $paramName => $this->{$paramName}
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getCurrentAge()
    {
        return ((int)$this->birth_date?App::formatter('AsAge', $this->birth_date):$this->age);  
        
        if(!$this->age){
          return ((int)$this->birth_date?App::formatter('AsAge', $this->birth_date):0);  
        }
        
        return $this->age;
        
        // return App::formatter('AsAge', $this->birth_date);
    }

    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['member_id' => 'id']);
    }

    public function getRecentTransactions($months = 6)
    {
        $today = App::formatter()->asDateToTimezone();
        $sixMonths = date('Y-m-d H:i:s', strtotime($today .' -6months'));

        return $this->hasMany(Transaction::className(), ['member_id' => 'id'])
            // ->onCondition(['transaction_type' => Transaction::EMERGENCY_WELFARE_PROGRAM])
            // ->assistance()
            ->daterange("{$sixMonths} - {$today}");
    }

    public function getAssistanceRecentTransactions($months = 6, $transaction_id='')
    {
        $today = App::formatter()->asDateToTimezone();
        $sixMonths = date('Y-m-d H:i:s', strtotime($today .' -6months'));

        return Transaction::find()
            ->where(['member_id' => $this->id])
            ->andWhere(['<>', 'id', $transaction_id])
            ->assistance()
            ->daterange("{$sixMonths} - {$today}")
            ->all();

        // return $this->hasMany(Transaction::className(), ['member_id' => 'id'])
        //     ->onCondition(['<>', 'id', $transaction_id])
        //     ->assistance()
        //     ->daterange("{$sixMonths} - {$today}");
    }

    public function getTotalRecentTransactions()
    {
        $today = App::formatter()->asDateToTimezone();
        $sixMonths = date('Y-m-d H:i:s', strtotime($today .' -6months'));

        return Transaction::find()
            ->where(['member_id' => $this->id])
            // ->assistance()
            ->daterange("{$sixMonths} - {$today}")
            ->count();
    }

    public function getTotalTransactions()
    {
        return Transaction::find()
            ->where(['member_id' => $this->id])
            // ->assistance()
            ->count();
    }

    public function getTotalTransactionsTag()
    {
        return Html::tag('div', $this->totalTransactions, [
            'class' => 'text-center font-weight-bolder'
        ]);
    }

    public function getTotalAmountTransactions()
    {
        $model = Transaction::find()
            ->select(['SUM(amount) AS total_amount'])
            ->where(['member_id' => $this->id])
            ->asArray()
            ->one();

        $total_transaction = $model['total_amount'] ?? 0;

        $model = EventMember::find()
            ->alias('em')
            ->joinWith('event e')
            ->select(['SUM(e.amount) AS total_amount'])
            ->where(['em.member_id' => $this->id])
            ->asArray()
            ->one();

        $total_event = $model['total_amount'] ?? 0;
        
        return App::formatter()->asNumber($total_transaction + $total_event);
    }

    public function getTotalAmountTransactionsTag()
    {
        return Html::tag('div', $this->totalAmountTransactions, [
            'class' => 'text-center font-weight-bolder'
        ]);
    }

    public function getHousehold()
    {
        return $this->hasOne(Household::className(), ['id' => 'household_id']);
    }

    public function getBarangay()
    {
        return $this->hasOne(Barangay::className(), ['no' => 'barangay_id'])
            ->via('household');
    }

    public function getBarangayName()
    {
        if (($barangay = $this->barangay) != null) {
            return $barangay->name;
        }
    }

    public function getFamilyHead()
    {
        return $this->hasOne(Member::className(), ['household_id' => 'household_id'])
            ->onCondition(['head' => Member::FAMILY_HEAD_YES]);
    }

    public function getIsPensioner()
    {
        return $this->pensioner == self::PENSIONER;
    }

    public function getRelationModel()
    {
        return $this->hasOne(Relation::className(), ['value' => 'relation'])
            ->onCondition(['var' => Relation::VAR]);
    }

    public function getRelationName()
    {
        if (($model = $this->relationModel) != null) {
            return $model->label;
        }
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

    public function getHeadBadge()
    {
        if ($this->living_status == self::DECEASED) {
            $class = 'danger';
        }
        elseif ($this->isHead) {
            $class = 'success';
        }
        else {
            $class = 'primary';
        }
        return Html::tag('label', $this->positionName, [
            'class' => "badge badge-{$class}"
        ]);
    }

    public function getBirthDate()
    {
        return date('m/d/Y', strtotime($this->birth_date));
    }

    public function getPensionerTag()
    {
        return $this->isPensioner ? 'Pensioner': 'Not Pensioner';
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

    public function getMonthlyIncome()
    {
        $this->income = (int) $this->income;
        return App::formatter('asNumber', $this->income);
    }

    public function getMonthlyPensionAmount()
    {
        $this->pension_amount = (int) $this->pension_amount;
        return App::formatter('asNumber', $this->pension_amount);
    }

    public function getIsHead()
    {
        return $this->head == self::FAMILY_HEAD_YES;
    }

    public function getRelationTag()
    {
        if ($this->isHead) {
            return Html::tag('label', 'Head', [
                'class' => 'badge badge-success'
            ]);
        }
        
        if ($this->relation == 0) {
            return ;
        }

        return Html::tag('label', $this->relationName , [
            'class' => 'badge badge-default'
        ]);
    }

    public function getIsMale()
    {
        return $this->sex == Sex::male();
    }

    public function getIsSingle()
    {
        return $this->civil_status == CivilStatus::single();
    }

    public function getIsMarried()
    {
        return $this->civil_status == CivilStatus::married();
    }

    public function getIsWidow()
    {
        return $this->civil_status == CivilStatus::widow();
    }

    public function getHouseholdNo()
    {
        if (($model = $this->household) != null) {
            return $model->no;
        }
    }

    public function getPositionTag()
    {
        return Html::tag('label', $this->positionName, [
            'class' => 'badge badge-' . (($this->isHead)? 'success': 'primary')
        ]);
    }

    public function getTags()
    {
        return implode(' | ', array_filter([
            (($this->isHead)? 'Head': (($this->relation == 0)? '': $this->relationName)),
            $this->currentAge . ' years old',
            $this->civilStatusName,
        ]));
    }

    public function getIsDeceased()
    {
        return $this->living_status == self::DECEASED;
    }

    public function getSubCategories()
    {
        $data = [];

        if ($this->currentAge >= 60) {
            $data[] = 'Senior Citizen';
        }

        if ($this->isDeceased) {
            $data[] = 'Deceased';
        }

        if ($this->pwd == self::PWD_YES) {

            if ($this->pwdTypeName) {
                $data[] = "PWD: {$this->pwdTypeName}";
            }
            else {
                $data[] = "PWD: Not set";
            }
        }

        if ($this->solo_parent == self::SOLO_PARENT_YES) {
            $data[] = 'Solo Parent';
        }

        return $data;
    }

    public function getSoloParentLabel()
    {
        if (isset(App::params('solo_parent')[$this->solo_parent])) {
            return App::params('solo_parent')[$this->solo_parent]['label'];
        }
    }

    public function getPwdLabel()
    {
        if (isset(App::params('pwd')[$this->pwd])) {
            return App::params('pwd')[$this->pwd]['label'];
        }
    }


    public function getPwdType()
    {
        return $this->hasOne(PwdType::className(), ['value' => 'pwd_type'])
            ->onCondition(['var' => PwdType::VAR]);
    }

    public function getPwdTypeName()
    {
        if (($pwdType = $this->pwdType) != null) {
            return $pwdType->label;
        }
    }

    public function getSubcategoriesTag()
    {
        $data = [];

        if (($subCategories = $this->subCategories) != null) {

            foreach ($subCategories as $subCategory) {
                $data[] = Html::tag('label', $subCategory, ['class' => 'badge badge-secondary']);
            }
        }

        if ($data) {
            return implode(' ', $data);
        }
    }

    public function getWidgetTags()
    {
        return implode(' | ', array_filter([
            (($this->isHead)? 'Head': ''),
            $this->genderName,
            $this->currentAge . ' years old',
            $this->civilStatusName,
        ]));
    }

    public function getProfileBtn()
    {
        return Html::a('Profile', $this->viewUrl, [
            'class' => 'btn btn-sm btn-facebook',
            'target' => '_blank'
        ]);
    }

    public function getEventMembers()
    {
        return $this->hasMany(EventMember::className(), ['member_id' => 'id']);
    }

    public function getRecentEventMembers()
    {
        $today = App::formatter()->asDateToTimezone();
        $sixMonths = date('Y-m-d H:i:s', strtotime($today .' -6months'));

        return $this->hasMany(EventMember::className(), ['member_id' => 'id'])
            ->daterange("{$sixMonths} - {$today}");
    }


    public static function findByKeywordsEvent($event_id, $keywords='', $attributes='', $limit=10)
    {
        $event = Event::findOne($event_id);
        $keywords=trim($keywords);
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit, $event) {
            return self::find()
                ->select("{$attribute} AS data")
                ->with('gender', 'civilStatus', 'relationModel')
                ->alias('m')
                ->joinWith(['household h', 'barangay b'])
                ->groupBy('data')
                ->where(['LIKE', $attribute, $keywords])
                ->andWhere(['h.barangay_id' => $event ? $event->barangay_ids: null])
                ->andWhere(['NOT IN', 'm.id', 
                    EventMember::find()
                        ->select(['member_id'])
                        ->where([ 'event_id' => $event ? $event->id: null])
                ])
                ->limit($limit)
                ->asArray()
                ->all();
        });
    }

    public static function findByKeywords($keywords='', $attributes='', $limit=10, $andFilterWhere=[])
    {
          $keywords=trim($keywords);
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit, $andFilterWhere) {
            return self::find()
                ->select("{$attribute} AS data")
                ->alias('m')
                ->joinWith(['household h', 'barangay b'])
                ->groupBy('data')
                ->where(['LIKE', $attribute, $keywords])
                ->andFilterWhere($andFilterWhere)
                ->limit($limit)
                ->asArray()
                ->all();
        });
    }

    public static function findHead($household_id='')
    {
        return self::find()
            ->where(['head' => self::FAMILY_HEAD_YES])
            ->andFilterWhere(['household_id' => $household_id])
            ->one();
    }

    public static function findAllHead($household_id='')
    {
        return self::find()
            ->where(['head' => self::FAMILY_HEAD_YES])
            ->andFilterWhere(['household_id' => $household_id])
            ->all();
    }
    
    public static function findHeadExcept($id, $household_id='')
    {
        return self::find()
            ->where(['head' => self::FAMILY_HEAD_YES])
            ->andWhere(['<>', 'id', $id])
            ->andFilterWhere(['household_id' => $household_id])
            ->one();
    }

    public static function findAllHeadExcept($id, $household_id='')
    {
        return self::find()
            ->where(['head' => self::FAMILY_HEAD_YES])
            ->andWhere(['<>', 'id', $id])
            ->andFilterWhere(['household_id' => $household_id])
            ->all();
    }

    public function getPositionName()
    {
        if ($this->living_status == self::DECEASED) {
            return 'Deceased';
        }

        if ($this->isHead) {
            return 'Head';
        }

        return 'Member';
    }

    public function getSkillsList()
    {
        $model = $this;
        return Html::if($model->skills, function() use($model) {
            $content = Value::widget([
                'label' => 'Skills',
                'content' => Html::ul($model->skills),
            ]);
            return <<< HTML
                <div class="separator separator-dashed my-7"></div>
                <section class="mt-5">
                    <p class="lead font-weight-bold">Skills</p>
                    <div class="row">
                        <div class="col">
                            {$content}
                        </div>
                    </div>
                </section>
            HTML;
        });
    }

    public function getCreateTransactionLink($type='')
    {
        if ($type) {
            return Url::to(['transaction/create', 'qr_id' => $this->qr_id, 'type' => $type], true);
        }

        return Url::to(['transaction/create', 'qr_id' => $this->qr_id], true);
    }

    public function getPhotoLink($w=100)
    {
        return Url::image($this->token, ['w' => $w]);
    }

    public function getImage($w = 50, $options=[])
    {
        $q = $w >= 100 ? 100: 90;
        return Html::image($this->photo, ['w' => $w, 'quality' => $q], $options);
    }

    public function getMyFamilyCompositions()
    {
        return $this->hasMany(Member::className(), ['household_id' => 'household_id'])
            ->onCondition(['<>', 'id', $this->id])
            ->orderBy(['birth_date' => SORT_ASC]);
    }

    public function getFamilyCompositions()
    {
        return $this->hasMany(Member::className(), ['household_id' => 'household_id'])
            ->orderBy(['birth_date' => SORT_ASC]);
    }

    public function getTotalFamilyComposition()
    {
        return Member::find()
            ->where(['household_id' => $this->household_id])
            ->count();
    }

    public function getBulkActions()
    {
        $actions = parent::getBulkActions();
        if (isset($actions['delete'])) {
            unset($actions['delete']);
        }

        $actions['draft'] = [
            'label' => 'Set as Draft',
            'process' => 'draft',
            'icon' => 'bookmark',
            'function' => function($id) {
                self::draftAll(['id' => $id]);
            }
        ];

        if (App::isLogin() && App::identity()->can('delete', $this->controllerID())) {
            $actions['delete'] = [
                'label' => 'Delete',
                'process' => 'delete',
                'icon' => 'delete',
                'function' => function($id) {
                    self::deleteAll(['id' => $id]);
                }
            ];
        }

        $actions['printqr'] = [
            'label' => 'Print QR Code',
            'process' => 'printqr',
            'icon' => 'print',
            'function' => function($id) {
                return;
            }
        ];

        $actions['print-id'] = [
            'label' => 'Print ID',
            'process' => 'print-id',
            'icon' => 'print',
            'function' => function($id) {
                return;
            }
        ];

        return $actions;
    }

    public function getInitial()
    {
        preg_match_all('/(?<=\b)[a-z]/i',$this->name, $matches);
        return strtoupper(implode('', $matches[0]));
    }

    public static function findBySlug($slug)
    {
        return self::findOne(['slug' => $slug]);
    }

    public static function findByQr($qr_id)
    {
        return self::findOne(['qr_id' => $qr_id]);
    }

    public function getMiddleName()
    {
        return $this->middle_name ?: '---';
    }

    public function getEligibleForAics()
    {
        if (($model = $this->assistanceRecentTransactions) != null) {
            return false;
        }

        return true;
    }

    public function getEligibleForAicsNotice($transaction_id='')
    {
        return EligibleForAicsNotice::widget([
            'model' => $this,
            'transaction_id' => $transaction_id
        ]);
    }

    public static function recent($limit = 6)
    {
        return self::find()
            ->with('gender', 'civilStatus')
            ->orderBy(['id' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    public function getBeneficiaryView()
    {
        return App::controller()->renderPartial('/member/_beneficiary-view', [
            'model' => $this
        ]);
    }

    public function claimed($event)
    {
        return EventMember::findOne([
            'event_id' => $event->id,
            'member_id' => $this->id
        ]);
    }

    public function getAddress()
    {
        if (($model = $this->household) != null) {
            return $model->address;
        }
    }

    public function getCertificateOfIndigencyUrl($fullpath=true)
    {
        if ($this->checkLinkAccess('print-certificate-of-indigency')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'print-certificate-of-indigency']),
                $paramName => $this->{$paramName}
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getSpouse()
    {
        $model = Member::find()
            ->where([
                'relation' => 2, // Wife/Spouse
                'household_id' => $this->household_id
            ])
            ->andWhere(['<>', 'id', $this->id])
            ->one();

        return $model;
    }

    public function relationTo($member)
    {
      
        
        if($member->new_cbms==1){
            return $this->relationNewCbms($member);
        }
        
        if($member->id==$this->id){
            return 'N/A';
        }
        

        
        if ($member->isHead) {
            switch ($this->relation) {
                case 0: // Head
                    return $this->isMale ? 'Husband': 'Wife'; //$this->relationName;
                    break;
                case 1: // Head
                    return $this->isMale ? 'Husband': 'Wife'; //$this->relationName;
                    break;
                case 2: // Wife/Spouse
                    return $member->isMale ? 'Husband': 'Wife';
                    break;
                case 3: // Son/Daughter
                    return $member->isMale ? 'Father': 'Mother';
                    break;
                case 4: // Son in law/Daughter in law
                    return $member->isMale ? 'Father in law': 'Mother in law';
                    break;
                case 5: // Grandson/Granddaughter
                    return $member->isMale ? 'Grandfather': 'Grandmother';
                    break;
                case 6: // Father/Mother
                    return $member->isMale ? 'Son': 'Daughter';
                    break;
                case 7: // Other Relatives
                    return 'Other Relatives';
                    break;
                case 8: // Housemaid/boy
                    return $member->isMale ? 'Houseboy': 'Housemaid';
                    break;
                case 9: // Other non-relatives
                    return 'Other non-relatives';
                    break;
                default:
                    return 'Other non-relatives';
                    break;
            }
        }

        if ($this->isHead) {
            switch ($member->relation) {
                case 2: // Wife/Spouse
                    return $member->isMale ? 'Husband': 'Wife';
                    break;
                case 3: // Son/Daughter
                    return $member->isMale ? 'Son': 'Daughter';
                    break;
                case 4: // Son in law/Daughter in law
                    return $member->isMale ? 'Son in law': 'Daughter in law';
                    break;
                case 5: // Grandson/Granddaughter
                    return $member->isMale ? 'Grandson': 'Granddaughter';
                    break;
                case 6: // Father/Mother
                    return $member->isMale ? 'Father': 'Mother';
                    break;
                case 7: // Other Relatives
                    return 'Other Relatives';
                    break;
                case 8: // Housemaid/boy
                    return $member->isMale ? 'Houseboy': 'Housemaid';
                    break;
                case 9: // Other non-relatives
                    return 'Other non-relatives';
                    break;
                default:
                    return 'Other non-relatives';
                    break;
            }
        }

        else {
            if ($this->relation == 2) { // Wife/Spouse 
                switch ($member->relation) {
                    case 2: // Wife/Spouse 
                        return $member->isMale ? 'Husband': 'Wife'; //'Other Relatives';
                        break;
                    case 3: // Son/Daughter
                        return $member->isMale ? 'Son': 'Daughter';
                        break;
                    case 4: // Son in law/Daughter in law
                        return $member->isMale ? 'Son in law': 'Daughter in law';
                        break;
                    case 5: // Grandson/Granddaughter
                        return $member->isMale ? 'Grandson': 'Granddaughter';
                        break;
                    case 6: // Father/Mother
                        return $member->isMale ? 'Father': 'Mother';
                        break;
                    case 7: // Other Relatives
                        return 'Other Relatives';
                        break;
                    case 8: // Housemaid/boy
                        return $member->isMale ? 'Houseboy': 'Housemaid';
                        break;
                    case 9: // Other non-relatives
                        return 'Other non-relatives';
                        break;
                    default:
                        // code...
                        break;
                }
            }

            if ($this->relation == 3) {  // Son/Daughter
                switch ($member->relation) {
                    case 2: // Wife/Spouse
                        return $member->isMale ? 'Father': 'Mother';
                        break;
                    case 3: // Son/Daughter
                        return $member->isMale ? 'Brother': 'Sister';
                        break;
                    case 4: // Son in law/Daughter in law
                        return $member->isMale ? 'brother in law': 'sister in law';
                        break;
                    case 5: // Grandson/Granddaughter
                        return $member->isMale ? 'nephew': 'niece';
                        break;
                    case 6: // Father/Mother
                        return $member->isMale ? 'Grandfather': 'Grandmother';
                        break;
                    case 7: // Other Relatives
                        return 'Other Relatives';
                        break;
                    case 8: // Housemaid/boy
                        return $member->isMale ? 'Houseboy': 'Housemaid';
                        break;
                    case 9: // Other non-relatives
                        return 'Other non-relatives';
                        break;
                    default:
                        // code...
                        break;
                }
            }

            if ($this->relation == 4) {  // Son in law/Daughter in law
                switch ($member->relation) {
                    case 2: // Wife/Spouse
                        return $member->isMale ? 'Father in law': 'Mother in law';
                        break;
                    case 3: // Son/Daughter
                        return $member->isMale ? 'Brother in law': 'Daughter in law';
                        break;
                    case 4: // Son in law/Daughter in law
                        return $member->isMale ? 'Brother': 'Sister';
                        break;
                    case 5: // Grandson/Granddaughter
                        return $member->isMale ? 'nephew in law': 'niece in law';
                        break;
                    case 6: // Father/Mother
                        return $member->isMale ? 'Grandfather in law': 'Grandmother in law';
                        break;
                    case 7: // Other Relatives
                        return 'Other Relatives';
                        break;
                    case 8: // Housemaid/boy
                        return $member->isMale ? 'Houseboy': 'Housemaid';
                        break;
                    case 9: // Other non-relatives
                        return 'Other non-relatives';
                        break;
                    default:
                        // code...
                        break;
                }
            }

            if ($this->relation == 5) {  // Grandson/Granddaughter
                switch ($member->relation) {
                    case 2: // Wife/Spouse
                        return $member->isMale ? 'Grandfather in law': 'Grandmother in law';
                        break;
                    case 3: // Son/Daughter
                        return $member->isMale ? 'Father': 'Mother';
                        break;
                    case 4: // Son in law/Daughter in law
                        return $member->isMale ? 'Father in law': 'Mother in law';
                        break;
                    case 5: // Grandson/Granddaughter
                        return $member->isMale ? 'Brother': 'Sister';
                        break;
                    case 6: // Father/Mother
                        return $member->isMale ? 'Grandgrandfather': 'Grandgrandmother';
                        break;
                    case 7: // Other Relatives
                        return 'Other Relatives';
                        break;
                    case 8: // Housemaid/boy
                        return $member->isMale ? 'Houseboy': 'Housemaid';
                        break;
                    case 9: // Other non-relatives
                        return 'Other non-relatives';
                        break;
                    default:
                        // code...
                        break;
                }
            }

            if ($this->relation == 6) {  // Father/Mother
                switch ($member->relation) {
                    case 2: // Wife/Spouse
                        return $member->isMale ? 'Son': 'Daughter';
                        break;
                    case 3: // Son/Daughter
                        return $member->isMale ? 'Grandson': 'Granddaughter';
                        break;
                    case 4: // Son in law/Daughter in law
                        return $member->isMale ? 'Grandson in law': 'Granddaughter in law';
                        break;
                    case 5: // Grandson/Granddaughter
                        return $member->isMale ? 'Grandgrandson': 'Grandgranddaughter';
                        break;
                    case 7: // Other Relatives
                        return 'Other Relatives';
                        break;
                    case 8: // Housemaid/boy
                        return $member->isMale ? 'Houseboy': 'Housemaid';
                        break;
                    case 9: // Other non-relatives
                        return 'Other non-relatives';
                        break;
                    default:
                        // code...
                        break;
                }
            }

            if ($this->relation == 7) { // Other Relatives
                return 'Other Relatives';
            }

            if ($this->relation == 8) { // Housemaid/boy
                return 'Boss';
            }

            if ($this->relation == 9) { // Other non-relatives
                return 'Other non-relatives';
            }
        }
    }
    
    
    
    
    
    
    
    
    public function relationNewCbms($member)
    {
        if($member->id==$this->id){
            return 'N/A';
        }
        
        
        if ($member->isHead) {
            switch ($this->relation) {
                case 0: // Head
                    return $this->isMale ? 'Husband': 'Wife'; //$this->relationName;
                    break;
                case 1: // Head
                    return $this->isMale ? 'Husband': 'Wife'; //$this->relationName;
                    break;
                case 2: // Wife/Spouse
                    return $member->isMale ? 'Husband': 'Wife';
                    break;
                case 3: // Son/Daughter
                    return $member->isMale ? 'Father': 'Mother';
                    break;
                case 4: // Son in law/Daughter in law
                    return $member->isMale ? 'Father': 'Mother';
                    break;
                case 5: // Grandson/Granddaughter
                    return  $member->isMale ? 'Stepfather': 'Stepmother'; //$member->isMale ? 'Grandson': 'Granddaughter';
                    break;
                case 6: // Father/Mother
                    return  $member->isMale ? 'Stepfather': 'Stepmother';  //$member->isMale ? 'Father': 'Mother';
                    break;
                case 7: // Other non-relatives
                    return  $member->isMale ? 'Father-in-law': 'Mother-in-law';
                    break;
                case 8: // Other non-relatives
                    return  $member->isMale ? 'Father-in-law': 'Mother-in-law';
                    break;
                 case 9: // Other non-relatives
                     return  $member->isMale ? 'Grandfather': 'Grandmother';
                    break;
                case 10: // Other non-relatives
                    return  $member->isMale ? 'Grandfather': 'Grandmother';
                    break;
                 case 11: // Son/Daughter
                    return $member->isMale ? 'Son': 'Daughter';
                    break;
                case 12: // Son in law/Daughter in law
                    return $member->isMale ? 'Son': 'Daughter'; //$member->isMale ? 'Son in law': 'Daughter in law';
                    break;
                case 13: // Son/Daughter
                    return $member->isMale ? 'Son-in-law': 'Daughter-in-law';
                    break;
                case 14: // Son in law/Daughter in law
                    return $member->isMale ? 'Son-in-law': 'Daughter-in-law'; //$member->isMale ? 'Son in law': 'Daughter in law';
                    break;
                case 15: // Son/Daughter
                    return $member->isMale ? 'Brother': 'Sister';
                    break;
                case 16: // Son in law/Daughter in law
                    return $member->isMale ? 'Brother': 'Sister';
                    break;
                 case 17: // Son/Daughter
                    return $member->isMale ? 'Brother-in-law': 'Sister-in-law';
                    break;
                case 18: // Son in law/Daughter in law
                    return $member->isMale ? 'Brother-in-law': 'Sister-in-law';
                    break;
               case 19: // Son/Daughter
                    return $member->isMale ? 'Nephew': 'Niece';
                    break;
                case 20: // Son in law/Daughter in law
                    return $member->isMale ? 'Nephew': 'Niece';
                    break;
                case 21: // Son/Daughter
                    return $member->isMale ? 'Uncle': 'Aunt';
                    break;
                case 22: // Son in law/Daughter in law
                    return $member->isMale ? 'Uncle': 'Aunt';
                    break;
                 case 23: // Son in law/Daughter in law
                    return $member->isMale ? 'Landlord ': 'Landlady';
                    break;
                case 24: // Housemaid/boy
                    return $member->isMale ? 'Boss': 'Boss';
                    break;
                case 9: // Other non-relatives
                    return 'Other non-relatives';
                    break;
                default:
                    return 'Other non-relatives';
                    break;
            }
        }

        if ($this->isHead || $this->relation == 2) {
            switch ($member->relation) {
                case 2: // Wife/Spouse
                    return $member->isMale ? 'Husband': 'Wife';
                    break;
                case 3: // Son/Daughter
                    return $member->isMale ? 'Son': 'Daughter';
                    break;
                case 4: // Son in law/Daughter in law
                    return $member->isMale ? 'Son': 'Daughter'; //$member->isMale ? 'Son in law': 'Daughter in law';
                    break;
                case 5: // Grandson/Granddaughter
                    return ($this->head==1?'Stepson':'Son');
                    break;
                case 6: // Father/Mother
                    return ($this->head==1?'Stepdaughter':'Daughter');
                    break;
                case 7: // Other Relatives
                    return 'Son-in-law'; //'Other Relatives';
                    break;
                case 8: // Housemaid/boy
                    return 'Daughter-in-law'; //$member->isMale ? 'Houseboy': 'Housemaid';
                    break;
                case 9: // Other non-relatives
                    return 'Grandson'; //'Other non-relatives';
                    break;
                case 10: // Other non-relatives
                    return 'Granddaughter'; //'Other non-relatives';
                    break;
                case 11: // Other non-relatives
                    return 'Father'; //'Other non-relatives';
                    break;
                case 12: // Other non-relatives
                    return 'Mother'; //'Other non-relatives';
                    break;
                case 13: // Other non-relatives
                    return 'Father-in-law'; //'Other non-relatives';
                    break;
                case 14: // Other non-relatives
                    return 'Mother-in-law'; //'Other non-relatives';
                    break;
                case 15: // Other non-relatives
                    return 'Brother'; //'Other non-relatives';
                    break;
               case 16: // Other non-relatives
                    return 'Sister'; //'Other non-relatives';
                    break;
                case 17: // Other non-relatives
                    return $this->relation == 1?'Brother-in-law':'Brother'; //'Other non-relatives';
                    break;
                case 18: // Other non-relatives
                    return $this->relation == 1?'Sister-in-law':'Sister'; //'Other non-relatives';
                    break;
                case 19: // Other non-relatives
                    return 'Uncle'; //'Other non-relatives';
                    break;
                case 20: // Other non-relatives
                    return 'Aunt'; //'Other non-relatives';
                    break;
                 case 21: // Other non-relatives
                    return 'Nephew'; //'Other non-relatives';
                    break;
                case 22: // Other non-relatives
                    return 'Niece'; //'Other non-relatives';
                    break;
                case 23: // Other non-relatives
                    return 'Boarder'; //'Other non-relatives';
                    break;
                case 24: // Other non-relatives
                    return 'Domestic Helper'; //'Other non-relatives';
                    break;
                case 25: // Other non-relatives
                    return 'Other relative'; //'Other non-relatives';
                    break;
                default:
                    return 'Non-relative';
                    break;
            }
        }

        else {

            if ($this->relation == 3 || $this->relation == 4) {  // Son/Daughter
                switch ($member->relation) {
                  case 1: // Wife/Spouse
                    return $member->isMale ? 'Father': 'Mother';
                    break;   
                 case 2: // Wife/Spouse
                    return $member->isMale ? 'Father': 'Mother';
                    break;
                 case 3: // Son/Daughter
                    return  'Brother';
                    break;
                case 4: // Son in law/Daughter in law
                    return 'Sister'; //$member->isMale ? 'Son in law': 'Daughter in law';
                    break;
                case 5: // Grandson/Granddaughter
                    return  'Stepbrother'; //$member->isMale ? 'Grandson': 'Granddaughter';
                    break;
                case 6: // Father/Mother
                    return 'Stepsister'; //$member->isMale ? 'Father': 'Mother';
                    break;
                case 7: // Other Relatives
                    return 'Brother-in-law'; //'Other Relatives';
                    break;
                case 8: // Housemaid/boy
                    return 'Sister-in-law'; //$member->isMale ? 'Houseboy': 'Housemaid';
                    break;
                case 9: // Other non-relatives
                    return 'Son'; //'Other non-relatives';
                    break;
                case 10: // Other non-relatives
                    return 'Daughter'; //'Other non-relatives';
                    break;
                case 11: // Other non-relatives
                    return 'Grandfather'; //'Other non-relatives';
                    break;
                case 12: // Other non-relatives
                    return 'Grandmother'; //'Other non-relatives';
                    break;
                case 13: // Other non-relatives
                    return 'Father-in-law'; //'Other non-relatives';
                    break;
                case 14: // Other non-relatives
                    return 'Mother-in-law'; //'Other non-relatives';
                    break;
                case 15: // Other non-relatives
                    return 'Uncle'; //'Other non-relatives';
                    break;
               case 16: // Other non-relatives
                    return 'Aunt'; //'Other non-relatives';
                    break;
                case 17: // Other non-relatives
                    return 'Brother-in-law'; //'Other non-relatives';
                    break;
                case 18: // Other non-relatives
                    return 'Sister-in-law'; //'Other non-relatives';
                    break;
                case 19: // Other non-relatives
                    return 'Uncle'; //'Other non-relatives';
                    break;
                case 20: // Other non-relatives
                    return 'Aunt'; //'Other non-relatives';
                    break;
                 case 21: // Other non-relatives
                    return 'Cousin';//'Nephew'; //'Other non-relatives';
                    break;
                case 22: // Other non-relatives
                    return 'Cousin';//'Niece'; //'Other non-relatives';
                    break;
                case 23: // Other non-relatives
                    return 'Boarder'; //'Other non-relatives';
                    break;
                case 24: // Other non-relatives
                    return 'Domestic Helper'; //'Other non-relatives';
                    break;
                case 25: // Other non-relatives
                    return 'Other relative'; //'Other non-relatives';
                    break;
                default:
                    return 'Non-relative';
                    break;
                }
            }
            
            
             if ($this->relation == 5 || $this->relation == 6) {  // Son/Daughter
                switch ($member->relation) {
                  case 1: // Wife/Spouse
                    return $member->isMale?($member->head==1?'Stepfather':'Father') : ($member->head==1?'Stepmother':'Mother');
                    break;   
                 case 2: // Wife/Spouse
                     return $member->isMale?($member->head==1?'Stepfather':'Father') : ($member->head==1?'Stepmother':'Mother');
                    break;
                 case 3: // Son/Daughter
                    return  'Stepbrother';
                    break;
                case 4: // Son in law/Daughter in law
                    return 'Stepsister'; //$member->isMale ? 'Son in law': 'Daughter in law';
                    break;
                case 5: // Grandson/Granddaughter
                    return  'Brother'; //$member->isMale ? 'Grandson': 'Granddaughter';
                    break;
                case 6: // Father/Mother
                    return 'Sister'; //$member->isMale ? 'Father': 'Mother';
                    break;
                case 7: // Other Relatives
                    return 'Brother-in-law'; //'Other Relatives';
                    break;
                case 8: // Housemaid/boy
                    return 'Sister-in-law'; //$member->isMale ? 'Houseboy': 'Housemaid';
                    break;
                case 9: // Other non-relatives
                    return 'Nephew'; //'Other non-relatives';
                    break;
                case 10: // Other non-relatives
                    return 'Niece'; //'Other non-relatives';
                    break;
                case 11: // Other non-relatives
                    return 'Grandfather'; //'Other non-relatives';
                    break;
                case 12: // Other non-relatives
                    return 'Grandmother'; //'Other non-relatives';
                    break;
            
                case 15: // Other non-relatives
                    return 'Uncle'; //'Other non-relatives';
                    break;
               case 16: // Other non-relatives
                    return 'Aunt'; //'Other non-relatives';
                    break;
                case 17: // Other non-relatives
                    return 'Brother-in-law'; //'Other non-relatives';
                    break;
                case 18: // Other non-relatives
                    return 'Sister-in-law'; //'Other non-relatives';
                    break;
                case 19: // Other non-relatives
                    return 'Uncle'; //'Other non-relatives';
                    break;
                case 20: // Other non-relatives
                    return 'Aunt'; //'Other non-relatives';
                    break;
                 case 21: // Other non-relatives
                    return 'Cousin';//'Nephew'; //'Other non-relatives';
                    break;
                case 22: // Other non-relatives
                    return 'Cousin';//'Niece'; //'Other non-relatives';
                    break;
                case 23: // Other non-relatives
                    return 'Boarder'; //'Other non-relatives';
                    break;
                case 24: // Other non-relatives
                    return 'Domestic Helper'; //'Other non-relatives';
                    break;
                case 25: // Other non-relatives
                    return 'Other relative'; //'Other non-relatives';
                    break;
                default:
                    return 'Non-relative';
                    break;
                }
            }
            
            
             if ($this->relation == 7 || $this->relation == 8) {  // Son/Daughter
                switch ($member->relation) {
                  case 1: // Wife/Spouse
                    return $member->isMale?'Father-in-law': 'Mother-in-law';
                    break;   
                 case 2: // Wife/Spouse
                     return $member->isMale?'Father-in-law': 'Mother-in-law';
                    break;
                 case 3: // Son/Daughter
                    return  $this->last_name==$member->last_name?'Husband':'Brother-in-law';
                    break;
                case 4: // Son in law/Daughter in law
                    return  $this->last_name==$member->last_name?'Wife':'Sister-in-law';
                    break;
                case 5: // Grandson/Granddaughter
                    return  'Stepbrother'; //$member->isMale ? 'Grandson': 'Granddaughter';
                    break;
                case 6: // Father/Mother
                    return 'Stepsister'; //$member->isMale ? 'Father': 'Mother';
                    break;
                case 7: // Other Relatives
                    return 'Brother-in-law'; //'Other Relatives';
                    break;
                case 8: // Housemaid/boy
                    return 'Sister-in-law'; //$member->isMale ? 'Houseboy': 'Housemaid';
                    break;
                case 9: // Other non-relatives
                    return 'Nephew'; //'Other non-relatives';
                    break;
                case 10: // Other non-relatives
                    return 'Niece'; //'Other non-relatives';
                    break;
                case 11: // Other non-relatives
                    return 'Grandfather'; //'Other non-relatives';
                    break;
                case 12: // Other non-relatives
                    return 'Grandmother'; //'Other non-relatives';
                    break;
            
                case 15: // Other non-relatives
                    return 'Uncle'; //'Other non-relatives';
                    break;
               case 16: // Other non-relatives
                    return 'Aunt'; //'Other non-relatives';
                    break;
                case 17: // Other non-relatives
                    return 'Brother-in-law'; //'Other non-relatives';
                    break;
                case 18: // Other non-relatives
                    return 'Sister-in-law'; //'Other non-relatives';
                    break;
                case 19: // Other non-relatives
                    return 'Uncle'; //'Other non-relatives';
                    break;
                case 20: // Other non-relatives
                    return 'Aunt'; //'Other non-relatives';
                    break;
                 case 21: // Other non-relatives
                    return 'Cousin';//'Nephew'; //'Other non-relatives';
                    break;
                case 22: // Other non-relatives
                    return 'Cousin';//'Niece'; //'Other non-relatives';
                    break;
                case 23: // Other non-relatives
                    return 'Boarder'; //'Other non-relatives';
                    break;
                case 24: // Other non-relatives
                    return 'Domestic Helper'; //'Other non-relatives';
                    break;
                case 25: // Other non-relatives
                    return 'Other relative'; //'Other non-relatives';
                    break;
                default:
                    return 'Non-relative';
                    break;
                }
            }
            
            
            
              if ($this->relation == 9 || $this->relation == 10) {  // Son/Daughter
                switch ($member->relation) {
                  case 1: // Wife/Spouse
                    return $member->isMale?'Grandfather': 'Grandmother';
                    break;   
                 case 2: // Wife/Spouse
                     return $member->isMale?'Grandfather': 'Grandmother';
                    break;
                 case 3: // Son/Daughter
                    return  $member->isMale?'Father':'Mother';
                    break;
                case 4: // Son in law/Daughter in law
                   return  $member->isMale?'Father':'Mother';
                    break;
                case 5: // Grandson/Granddaughter
                    return  $member->isMale? 'Uncle': 'Aunt';
                    break;
                case 6: // Father/Mother
                    return  $member->isMale? 'Uncle': 'Aunt';
                    break;
                case 7: // Other Relatives
                     return  $member->isMale?'Father':'Mother';
                    break;
                case 8: // Housemaid/boy
                    return  $member->isMale?'Father':'Mother';
                    break;
                case 9: // Other non-relatives
                    return 'Brother'; //'Other non-relatives';
                    break;
                case 10: // Other non-relatives
                    return 'Sister'; //'Other non-relatives';
                    break;
                case 11: // Other non-relatives
                    return 'Great Grandfather'; //'Other non-relatives';
                    break;
                case 12: // Other non-relatives
                    return 'Great Grandmother'; //'Other non-relatives';
                    break;
            
                case 15: // Other non-relatives
                    return 'Grandfather'; //'Other non-relatives';
                    break;
               case 16: // Other non-relatives
                    return 'Grandmother'; //'Other non-relatives';
                    break;
                case 17: // Other non-relatives
                    return 'Grandfather'; //'Other non-relatives';
                    break;
                case 18: // Other non-relatives
                    return 'Grandmother'; //'Other non-relatives';
                    break;
                case 19: // Other non-relatives
                    return 'Great Grandfather'; //'Other non-relatives';
                    break;
                case 20: // Other non-relatives
                    return 'Great Grandmother'; //'Other non-relatives';
                    break;
                 case 21: // Other non-relatives
                    return 'Uncle';//'Nephew'; //'Other non-relatives';
                    break;
                case 22: // Other non-relatives
                    return 'Aunt';//'Niece'; //'Other non-relatives';
                    break;
                case 23: // Other non-relatives
                    return 'Boarder'; //'Other non-relatives';
                    break;
                case 24: // Other non-relatives
                    return 'Domestic Helper'; //'Other non-relatives';
                    break;
                case 25: // Other non-relatives
                    return 'Other relative'; //'Other non-relatives';
                    break;
                default:
                    return 'Non-relative';
                    break;
                }
            }
            

            if ($this->relation == 21 || $this->relation == 22) {  // Son in law/Daughter in law
                switch ($member->relation) {
                    case 2: // Wife/Spouse
                        return $member->isMale ? 'Uncle': 'Aunt';
                        break;
                    case 3: // Son/Daughter
                        return $member->isMale ? 'Cousin': 'Cousin';
                        break;
                    case 4: // Son in law/Daughter in law
                        return $member->isMale ? 'Cousin': 'Cousin';
                        break;
                    case 9: // Other non-relatives
                    return 'Nephew'; //'Other non-relatives';
                    break;
                    case 10: // Other non-relatives
                    return 'Niece'; //'Other non-relatives';
                    break;
                    default:
                         return 'Non-relative';
                        break;
                }
            }

            if ($this->relation == 5) {  // Grandson/Granddaughter
                switch ($member->relation) {
                    case 2: // Wife/Spouse
                        return $member->isMale ? 'Grandfather in law': 'Grandmother in law';
                        break;
                    case 3: // Son/Daughter
                        return $member->isMale ? 'Father': 'Mother';
                        break;
                    case 4: // Son in law/Daughter in law
                        return $member->isMale ? 'Father in law': 'Mother in law';
                        break;
                    case 5: // Grandson/Granddaughter
                        return $member->isMale ? 'Brother': 'Sister';
                        break;
                    case 6: // Father/Mother
                        return $member->isMale ? 'Grandgrandfather': 'Grandgrandmother';
                        break;
                    case 7: // Other Relatives
                        return 'Other Relatives';
                        break;
                    case 8: // Housemaid/boy
                        return $member->isMale ? 'Houseboy': 'Housemaid';
                        break;
                    case 9: // Other non-relatives
                        return 'Other non-relatives';
                        break;
                    default:
                        // code...
                        break;
                }
            }

            if ($this->relation == 6) {  // Father/Mother
                switch ($member->relation) {
                    case 2: // Wife/Spouse
                        return $member->isMale ? 'Son': 'Daughter';
                        break;
                    case 3: // Son/Daughter
                        return $member->isMale ? 'Grandson': 'Granddaughter';
                        break;
                    case 4: // Son in law/Daughter in law
                        return $member->isMale ? 'Grandson in law': 'Granddaughter in law';
                        break;
                    case 5: // Grandson/Granddaughter
                        return $member->isMale ? 'Grandgrandson': 'Grandgranddaughter';
                        break;
                    case 7: // Other Relatives
                        return 'Other Relatives';
                        break;
                    case 8: // Housemaid/boy
                        return $member->isMale ? 'Houseboy': 'Housemaid';
                        break;
                    case 9: // Other non-relatives
                        return 'Other non-relatives';
                        break;
                    default:
                        // code...
                        break;
                }
            }

            if ($this->relation == 7) { // Other Relatives
                return 'Other Relatives';
            }

            if ($this->relation == 8) { // Housemaid/boy
                return 'Boss';
            }

            if ($this->relation == 9) { // Other non-relatives
                return 'Other non-relatives';
            }
        }
    }
    
    
    
    
    
    

    public function getSocialCaseStudyReports()
    {
        return $this->hasMany(Transaction::className(), ['member_id' => 'id'])
            ->onCondition(['transaction_type' => Transaction::SOCIAL_CASE_STUDY_REPORT]);
    }

    public function getOccupationName()
    {
        return $this->occupation?ucwords(strtolower($this->occupation)): 'None';
    }

    public function certification($params = [])
    {
        $searchModel = new TransactionSearch();
        $searchModel->member_id = $this->id;
        $searchModel->pagination = 10;
        $dataProvider = $searchModel->search(['TransactionSearch' => $params]);
        $dataProvider->query->certificate();
        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];
    }

    public function eventMember($params = [])
    {
        $searchModel = new EventMemberSearch();
        $searchModel->member_id = $this->id;
        $searchModel->pagination = 10;
        $dataProvider = $searchModel->search(['EventMemberSearch' => $params]);
        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];
    }

    public function socialCaseStudyReport($params = [])
    {
        $searchModel = new TransactionSearch();
        $searchModel->member_id = $this->id;
        $searchModel->pagination = 10;
        $searchModel->transaction_type = Transaction::SOCIAL_CASE_STUDY_REPORT;
        $dataProvider = $searchModel->search(['TransactionSearch' => $params]);

        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];
    }

    public function getUpdateProfileUrlSocialPension($fullpath=true)
    {
        if ($this->checkLinkAccess('update-profile', 'transaction')) {
            $url = [
                'transaction/update-profile',
                'qr_id' => $this->qr_id,
                'transaction_type' => 'social-pension'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getViewUrlHouseholdTab($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'view']),
                $paramName => $this->{$paramName},
                'tab' => 'household'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getViewUrlFamilyCompositionTab($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'view']),
                $paramName => $this->{$paramName},
                'tab' => 'family-composition'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getViewUrlPersonalInformationTab($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'view']),
                $paramName => $this->{$paramName},
                'tab' => 'personal-information'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getTransferToNewHouseholdModel()
    {
        return new TransferToNewHouseholdForm(['member_id' => $this->id]);
    }

    public function getTransferToExistingHouseholdModel()
    {
        return new TransferToExistingHouseholdForm(['member_id' => $this->id]);
    }

    public function getHouseholdMembers()
    {
        return $this->hasMany(HouseholdMember::className(), ['household_id' => 'household_id']);
    }

    public function getInactiveFamilyCompositions()
    {
        return $this->hasMany(Member::className(), ['id' => 'member_id'])
            ->via('householdMembers');
    }

    public static function oldestAge()
    {
        $data = self::find()
            ->where(['living_status' => self::ALIVE])
            ->orderBy(['age' => SORT_DESC])
            ->asArray()
            ->one();

        return $data['age'] ?? 0;
    }

    public static function youngestAge()
    {
        $data = self::find()
            ->where(['living_status' => self::ALIVE])
            ->orderBy(['age' => SORT_ASC])
            ->asArray()
            ->one();

        return $data['age'] ?? 0;
    }

    public static function ageDropdown()
    {
        $models = self::find()
            ->where(['living_status' => self::ALIVE])
            ->orderBy(['age' => SORT_ASC])
            ->groupBy('age')
            ->asArray()
            ->all();

        return ArrayHelper::map($models, 'age', 'age');
    }

    public function getCleanName()
    {
        return App::formatter('asClean', $this->name);
    }

    public function getViewUrlSeniorCitizenId()
    {
        return Url::to(['file/viewer', 'token' => $this->senior_citizen_id]);
    }

    public function getDownloadSeniorCitizenIdUrl($fullpath=true)
    {
        if ($this->checkLinkAccess('download-senior-citizen-id')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'download-senior-citizen-id']),
                $paramName => $this->{$paramName},
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getSeniorCitizenIdFile()
    {
        $token = $this->senior_citizen_id ?: App::setting('image')->image_holder;

        $file = File::findByToken($token);

        return $file;
    }

    public function getIsSeniorAge()
    {
        return $this->currentAge >= 60;
    }

    public function getViewTabs()
    {
        $tabs = self::VIEW_TABS;

        if ($this->currentAge < 60) {
            unset($tabs[9]);
        }

        return $tabs;
    }

    public function getSeniorCitizenBagdeStatus()
    {
        return (new Transaction())->getSeniorCitizenIdBadgeStatus($this->senior_citizen_id);
    }

    public function getCanTransferToExistingHousehold()
    {
        return App::identity()->can('transfer-to-existing-household', 'member');
    }

    public function getCanTransferToNewHousehold()
    {
        return App::identity()->can('transfer-to-new-household', 'member');
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

    public function getIs4Ps()
    {
        return $this->fourPs == self::YES_4PS;
    }

    public function getfourPsLabel()
    {
        return App::keyMapParams('fourPs')[$this->fourPs] ?? '';
    }

    public function getVoterLabel()
    {
        return App::keyMapParams('voters')[$this->voter] ?? '';
    }

    public function getSoloMemberLabel()
    {
        return App::keyMapParams('solo_member')[$this->solo_member] ?? '';
    }

    public function getLastUpdatedMessage($log)
    {
        $message = "This member was last updated on {$log->updatedAt} <span class='font-weight-bolder'>({$log->ago})</span>";

        if (($updatedByName = $log->updatedByName) != null) {
            $message .= " by {$log->updatedByName}";
        }

        return $message;
    }

    public function getViewUrlUpdateLogsTab($fullpath=true)
    {
        if ($this->checkLinkAccess('view')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'view']),
                $paramName => $this->{$paramName},
                'tab' => 'update-logs'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getRecentLog()
    {
        return Log::find()
            ->where([
                'model_id' => $this->id,
                'model_name' => 'Member',
                // 'action' => 'update'
            ])
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }

    public function getUpdateLogMessage()
    {
        if (($log = $this->recentLog) != null) {
            return implode(' ', [
                $this->getLastUpdatedMessage($log),
                Html::a('View Update Logs', $this->viewUrlUpdateLogsTab, [
                    'class' => 'btn btn-light-info btn-sm font-weight-bolder'
                ])
            ]);
        }

        return 'There are no recent updates on this member.';
    }

    public function getUpdateLogsData()
    {
        $searchModel = new LogSearch([
            'model_id' => $this->id,
            'model_name' => 'Member',
            // 'action' => 'update'
        ]);
        $dataProvider = $searchModel->search(['LogSearch' => App::queryParams()]);

        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];
    }

    public function getUpdateLogsGridColumns()
    {
        return [
            'serial' => ['class' => 'yii\grid\SerialColumn'],
            'updated_by' => [
                'attribute' => 'updated_by', 
                'format' => 'raw', 
                'value' => 'updatedByName'
            ],
            'ip' => ['attribute' => 'ip', 'format' => 'raw'],
            'browser' => ['attribute' => 'browser', 'format' => 'raw'],
            'os' => ['attribute' => 'os', 'format' => 'raw'],
            'device' => ['attribute' => 'device', 'format' => 'raw'],
            'last_updated' => [
                'attribute' => 'updated_at',
                'label' => 'last updated',
                'format' => 'ago',
            ],
            // 'change_attribute' => [
            //     'attribute' => 'change_attribute', 
            //     'label' => 'changes',
            //     'format' => 'raw',
            //     'value' => function($model) {
            //         if ($model->change_attribute) {
            //             $html = '<ul>';
            //             foreach ($model->change_attribute as $key => $change_attribute) {
            //                 $label = $model->getAttributeLabel($key) ?: $key;
            //                 $modelInstance = $model->modelInstance;
            //                 $value = is_array($modelInstance->{$key})? json_encode($modelInstance->{$key}): $modelInstance->{$key};
            //                 $html .= "<li>{$label}: {$value}</li>";
            //             }
            //             $html .='</ul>';
            //             return $html;
            //         }
            //     }
            // ],
        ];
    }

    public function getTransactionFilter($attribute)
    {
        $transactions = Transaction::find()
            ->where(['member_id' => $this->id])
            ->groupBy($attribute)
            ->asArray()
            ->all();

        return ArrayHelper::map($transactions, $attribute, $attribute);
    }
    public function processFilter($arr1, $arr2)
    {
        foreach ($arr1 as $key => $arr_1) {
            $arr1[$key] = $arr2[$key] ?? '';
        }

        return array_filter($arr1);
    }
   
    public function getTransactionTypeFilter()
    {
        return $this->processFilter(
            $this->getTransactionFilter('transaction_type'),
            App::keyMapParams('transaction_types')
        );
    }

    public function getStatusFilter()
    {
        return $this->processFilter(
            $this->getTransactionFilter('status'),
            App::keyMapParams('transaction_status')
        );
    }

    public function getIsSocialPensioner()
    {
        return $this->social_pension_status == self::SOCIAL_PENSIONER;
    }

    public function getDatabase()
    {
        return Database::findOne([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'date_of_birth' => date('Y-m-d', strtotime($this->birth_date)),
        ]);
    }
}