<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\jobs\EventJob;
use app\models\ActiveRecord;
use app\models\Budget;
use app\models\CivilStatus;
use app\models\EducationalAttainment;
use app\models\EventMember;
use app\models\File;
use app\models\Household;
use app\models\PwdType;
use app\models\Queue;
use app\models\Sex;
use app\models\search\EventMemberSearch;
use app\models\search\MemberSearch;
use app\widgets\Anchor;
use app\widgets\EventStatus;
use yii\db\Expression;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "{{%events}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $beneficiaries
 * @property string|null $token
 * @property int $status
 * @property string|null $photo
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Event extends ActiveRecord
{
    const PENDING = 0;
    const ONGOING = 1;
    const COMPLETED = 2;
    const CANCELLED = 3;

    //  TYPE
    const SEMINAR = 1;
    const TRAINING = 2;
    const EVENT = 3;
    const ASSISTANCE = 4;

    //  ASSISTANCE TYPE
    const DEFAULT_TYPE = 0;
    const CASH = 1;
    const IN_KIND = 2;

    // CATEGORY_TYPE
    const DEFAULT_CATEGORY = 0;
    const UN_PLANNED_CATEGORY = 1;
    const SOCIAL_PENSION_CATEGORY = 2;

    const SCENARIO_DEFAULT = 'default-scenario';
    const SCENARIO_SOCIAL_PENSION = 'social-pension';
    const SCENARIO_UNPLANNED = 'unplanned';

    const LOCAL_FUND = 1;
    const NATIONAL_FUND = 2;

    const STEP_FORM = [
        1 => [
            'id' => 1,
            'slug' => 'general-information',
            'title' => 'General Information',
            'description' => 'Primary Event Details',
        ],
        2 => [
            'id' => 2,
            'slug' => 'documents',
            'title' => 'Documents | Photos',
            'description' => 'Document & Photos',
        ],
        3 => [
            'id' => 3,
            'slug' => 'beneficiaries',
            'title' => 'Beneficiaries',
            'description' => 'Filter Members',
        ],
        4 => [
            'id' => 4,
            'slug' => 'create-list',
            'title' => 'Create List',
            'description' => 'Manage Created List',
        ],
        5 => [
            'id' => 5,
            'slug' => 'summary',
            'title' => 'Summary',
            'description' => 'Event Summary',
        ],
    ];

    public $oneday = false;

    public $_totalMembers;
    public $_totalHouseholds;
    public $_totalBeneficiaryMember;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%events}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'event',
            'mainAttribute' => 'name',
            'paramName' => 'token',
            'relatedModels' => ['eventMembers']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['name', 'date_from', 'date_to', 'type', 'status', 'category_id', 'assistance_type'], 'required', 'on' => self::SCENARIO_DEFAULT],

            [['name', 'date_from', 'date_to', 'type', 'status', 'category_id', 'assistance_type'], 'required', 'on' => self::SCENARIO_UNPLANNED],

            [['name', 'date_from', 'date_to', 'amount', 'no_of_pensioner', 'social_pension_fund'], 'required', 'on' => self::SCENARIO_SOCIAL_PENSION],

            [['description', 'photo'], 'string'],
            [['amount'], 'number'],
            [['amount'], 'default', 'value' => 0],
            [['status', 'type', 'category_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            ['category_id', 'exist', 'targetRelation' => 'eventCategory', 'on' => [self::SCENARIO_DEFAULT, self::SCENARIO_UNPLANNED]],
            ['status', 'in', 'range' => array_keys(App::keyMapParams('event_status'))],
            ['type', 'in', 
                'range' => array_keys(App::keyMapParams('event_types'))
            ],
            [['date_from', 'date_to'], 'validateDaterange'],
            [['beneficiaries', 'files'], 'safe'],
            [
                ['assistance_type', 'amount'], 
                'required', 'when' => function($model) {
                    return $model->type == self::ASSISTANCE;
                },
            ],
            [
                'assistance_type', 'in', 
                'range' => array_keys(App::keyMapParams('event_assistance_types')),
            ],
            [['photo', 'oneday'], 'safe'],
            [
                'category_type', 'in', 
                'range' => array_keys(App::keyMapParams('event_category_types_list')),
            ],
            ['category_type', 'default', 'value' => self::DEFAULT_CATEGORY],
        ]);
    }

    public function getEventCategory()
    {
        return $this->hasOne(EventCategory::className(), ['id' => 'category_id'])
            ->onCondition(['type' => EventCategory::TYPE]);
    }

    public function getEventMembers()
    {
        return $this->hasMany(EventMember::className(), ['event_id' => 'id']);
    }

    public function getCompleted()
    {
        return $this->hasMany(EventMember::className(), ['event_id' => 'id'])
            ->onCondition([
                'status' => [
                    EventMember::CLAIMED,
                    EventMember::ATTENDED,
                ]
            ]);
    }

    public function getCanDelete()
    {
        if ($this->status == self::PENDING  || $this->status == self::CANCELLED) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'name' => 'Event Name',
            'description' => 'Description',
            'beneficiaries' => 'Barangay Beneficiaries',
            'status' => 'Status',
            'photo' => 'Photo',
            'categoryLabel' => 'Category',
            'category_id' => 'Category',
            'eventTypeLabel' => 'Type',
            'totalBeneficiaryMember' => 'Beneficiaries',
            'recordStatusBadge' => 'Record Status',
        ]);
    }

    public function getBulkActions()
    {
        $actions = parent::getBulkActions();

        unset($actions['active'], $actions['in_active']);
     
        // $actions['draft'] = [
        //     'label' => 'Set as Draft',
        //     'process' => 'draft',
        //     'icon' => 'bookmark',
        //     'function' => function($id) {
        //         self::draftAll(['id' => $id]);
        //     }
        // ];

        return $actions;
    }

    public function validateDaterange($attribute, $params)
    {
        if ($this->oneday) {
            if ($this->date_from != $this->date_to) {
                $this->addError($attribute, 'One day event, "Date From" must be less equal to "Date to".');
            }
        }
        else {
            $from = strtotime($this->date_from);
            $to = strtotime($this->date_to);

            if ($from > $to) {
                $this->addError($attribute, '"Date From" must be less than "Date to".');
            }
        }
    }

    public function getEventTypeLabel()
    {
        return App::params('event_types')[$this->type]['label'];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\EventQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\EventQuery(get_called_class());
    }

    public function getAllFiles()
    {
        if (($photos = $this->files) != null) {
            $files = File::find()
                ->where(['token' => $photos])
                ->orderBy(['id' => SORT_DESC])
                ->all();
            return $files;
        }
    }

    public function getImageFiles()
    {
        if (($photos = $this->files) != null) {
            $files = File::find()
                ->where(['token' => $photos])
                ->orderBy(['id' => SORT_DESC])
                ->all();
            return $files;
        }
    }

    public function getTotalBeneficiaryMember()
    {
        if ($this->_totalBeneficiaryMember === null) {
            $total = EventMember::find()
                ->where(['event_id' => $this->id])
                ->count();

            $this->_totalBeneficiaryMember = App::formatter('asNumber', $total);
        }

        return $this->_totalBeneficiaryMember;
    }
     
    public function gridColumns()
    {
        return [
            'name' => [
                'attribute' => 'name', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->name,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'category' => [
                'label' => 'CATEGORY',
                'attribute' => 'categoryLabel', 
                'format' => 'raw'
            ],
            'type' => [
                'label' => 'TYPE',
                'attribute' => 'type', 
                'value' => 'eventTypeLabel', 
                'format' => 'raw'
            ],
            'date_from' => ['attribute' => 'date_from', 
            'format' => 'raw',
            'value' => function($model) {
                    return date('F d, Y',strtotime($model->date_from));
                }
            
            ],
            'date_to' => ['attribute' => 'date_to', 
             'format' => 'raw',
            'value' => function($model) {
                    return date('F d, Y',strtotime($model->date_to));
                }
            
            ],
            'beneficiaries' => [
                'label' => 'BENEFICIARIES',
                'attribute' => 'totalBeneficiaryMember', 
                'format' => 'raw'
            ],
            // 'beneficiaries' => ['attribute' => 'beneficiaries', 'format' => 'raw'],
            // 'photo' => ['attribute' => 'photo', 'format' => 'raw'],
            'status' => ['attribute' => 'status', 'format' => 'raw', 'value' => 'statusBadge'],
        ];
    }

    public function getBudget($formatted = false)
    {
        return $formatted ? App::formatter('asNumber', $this->amount): $this->amount;
    }

    public function getTotalBeneficiaries()
    {
        return App::formatter('asNumber', $this->amount);
    }

    public function getDetailColumns()
    {
        $columns = parent::getDetailColumns();

        unset($columns['recordStatusHtml']);

        $columns['recordStatusBadge'] = [
            'attribute' => 'recordStatusBadge',
            'format' => 'raw'
        ];

        return $columns;
    }

    public function detailColumns()
    {
        return [
            // 'totalBeneficiaries:raw',
            'name:raw',
            'categoryLabel:raw',
            'eventTypeLabel:raw',
            'description:raw',
            'amount:number',
            'date_from:raw',
            'date_to:raw',
            'totalBeneficiaryMember:raw',
            [
                'label' => 'Photo',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::image(
                        $model->photo,
                        ['w'=>40, 'h'=>40, 'ratio'=>'false', 'quality'=>90],
                    );
                }
            ],
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['JsonBehavior']['fields'] = ['beneficiaries', 'files'];
        $behaviors['EventBehavior'] = ['class' => 'app\behaviors\EventBehavior'];

        return $behaviors;
    }

    public static function recent($limit = 3)
    {
        return self::find()
            ->where(['status' => self::ONGOING])
            ->orderBy(['id' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    public function getDateFrom()
    {
        return date('m/d/Y', strtotime($this->date_from));
    }

    public function getBeforeCanUpdate()
    {
        if ($this->_canUpdate == true) {
            return true;
        }
        $eventMembers = EventMember::findAll([
            'event_id' => $this->id,
            'status' => [
                EventMember::CLAIMED,
                EventMember::ATTENDED,
            ]
        ]);

        if ($eventMembers && $this->status == self::ONGOING) {
            return false;
        }

        if ($this->status == self::COMPLETED) {
            return false;
        }

        return parent::getCanUpdate();
    }

    public function getCanDuplicate()
    {
        if ($this->status == self::PENDING) {
            return true;
        }

        return false;
    }

    public function getDateTo()
    {
        return date('m/d/Y', strtotime($this->date_to));
    }

    public function getStatusData()
    {
        return App::params('event_status')[$this->status];
    }

    public function getStatusBadge()
    {
        return Html::tag('label', $this->statusLabel, [
            'class' => 'badge badge-' . $this->statusClass
        ]);
    }

    public function getSpanBadge()
    {
        return Html::tag('span', $this->statusLabel, [
            'class' => 'font-weight-bolder font-size-sm pr-6 text-' . $this->statusClass
        ]);
    }


    public function getCategoryBadge()
    {
        return Html::tag('span', $this->categoryLabel, [
            'class' => 'badge badge-primary'
        ]);
    }

    public function getCategoryLabel()
    {
        if (($ec = $this->eventCategory) != null) {
            return $ec->name;
        }
    }

    public function getStatusLabel()
    {
        return $this->statusData['label'];
    }

    public function getStatusClass()
    {
        return $this->statusData['class'];
    }

    public function getInitial()
    {
        preg_match_all('/(?<=\b)[a-z]/i',$this->name, $matches);
        return strtoupper(implode('', $matches[0]));
    }

    public function getDefaultGridColumns()
    {
        return [
            'serial',
            'checkbox',
            'name',
            'description',
            'date_from',
            'date_to',
            // 'created_at',
            // 'last_updated',
            'status',
        ];
    }

    public function getTotalTags()
    {
        $data = array_filter([
            (($this->totalHouseholds)? 
                "Households: <b class='text-success'>" . App::formatter('asNumber', $this->totalHouseholds) . '</b>'
                : ''
            ), 
            (($this->totalMembers)? 
                "Members: <b class='text-success'>" . App::formatter('asNumber', $this->totalMembers) . '</b>'
                : ''
            ), 
        ]);


        return Html::tag('small', implode(' | ', $data), [
            'class' => 'text-muted',
            'id' => 'total-beneficiaries'
        ]);
    }

    public static function findByToken($token)
    {
        return self::find()
            ->where(['token' => $token])
            ->one();
    }



    public function getMemberSearch()
    {
        $searchModel = new MemberSearch();
        $searchModel->setAge();
        $searchModel->barangay_ids = $this->beneficiaries['barangay_ids'] ?? [];
        $searchModel->educational_attainment = $this->beneficiaries['educational_attainment'] ?? [];
        $searchModel->head = $this->beneficiaries['head'] ?? [];
        $searchModel->sex = $this->beneficiaries['sex'] ?? [];
        $searchModel->solo_parent = $this->beneficiaries['solo_parent'] ?? [];
        $searchModel->solo_member = $this->beneficiaries['solo_member'] ?? [];
        $searchModel->civil_status = $this->beneficiaries['civil_status'] ?? [];
        $searchModel->age_from = $this->beneficiaries['age_from'] ?? $searchModel->age_from;
        $searchModel->age_to = $this->beneficiaries['age_to'] ?? $searchModel->age_to;
        $searchModel->pwd = $this->beneficiaries['pwd'] ?? $searchModel->pwd;
        $searchModel->pwd_type = $this->beneficiaries['pwd_type'] ?? $searchModel->pwd_type;
        $searchModel->purok_no = $this->beneficiaries['purok_no'] ?? $searchModel->purok_no;

        $dataProvider = $searchModel->search(['MemberSearch' => App::queryParams()]);
        $dataProvider->query->orderBy($searchModel->prioritySort);

        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];
    }

    public function getSocialPensionSearch()
    {
        $searchModel = new MemberSearch();
        $searchModel->setAge();
        $searchModel->barangay_ids = $this->beneficiaries['barangay_ids'] ?? [];
        $searchModel->educational_attainment = $this->beneficiaries['educational_attainment'] ?? [];
        $searchModel->head = $this->beneficiaries['head'] ?? [];
        $searchModel->sex = $this->beneficiaries['sex'] ?? [];
        $searchModel->solo_parent = $this->beneficiaries['solo_parent'] ?? [];
        $searchModel->solo_member = $this->beneficiaries['solo_member'] ?? [];
        $searchModel->civil_status = $this->beneficiaries['civil_status'] ?? [];
        $searchModel->age_from = $this->beneficiaries['age_from'] ?? $searchModel->age_from;
        $searchModel->age_to = $this->beneficiaries['age_to'] ?? $searchModel->age_to;
        $searchModel->pwd = $this->beneficiaries['pwd'] ?? $searchModel->pwd;
        $searchModel->pwd_type = $this->beneficiaries['pwd_type'] ?? $searchModel->pwd_type;
        $searchModel->purok_no = $this->beneficiaries['purok_no'] ?? $searchModel->purok_no;
        $searchModel->social_pension_status = SocialPension::SOCIAL_PENSIONER;

        $dataProvider = $searchModel->search(['MemberSearch' => App::queryParams()]);
        $dataProvider->query->orderBy($searchModel->prioritySort);
        
        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];
    }

    public function getIsAssistance()
    {
        return $this->type == self::ASSISTANCE;
    }

    public function populateEventMembers()
    {
        ini_set('max_execution_time', 0); //0=NOLIMIT
        ini_set('memory_limit', '-1');

        EventMember::deleteAllRow(['event_id' => $this->id]);

        $searchModel = new MemberSearch([
            'living_status' => Member::ALIVE
        ]);
        $dataProvider = $searchModel->search(['MemberSearch' => $this->beneficiaries]);
        $dataProvider->query->orderBy($searchModel->prioritySort);
        $dataProvider->pagination = false;

        if ($this->category_type == self::SOCIAL_PENSION_CATEGORY) {
            $dataProvider->query->limit($this->no_of_pensioner);
        }

        $data = [];
        if ($dataProvider->totalCount > 0) {

            foreach ($dataProvider->models as $member) {
                $status = $this->isAssistance ? EventMember::UNCLAIM: EventMember::UNATTENDED;

                $em = EventMember::find()
                    ->where([
                        'event_id' => $this->id, 
                        'member_id' => $member->id
                    ])
                    ->exists();

                if (! $em) {
                    $data[] = [
                        $this->id, 
                        $member->id, 
                        $member->fullname, 
                        $member->qr_id, 
                        $member->household_no, 
                        $member->head, 
                        $member->solo_parent,
                        $member->solo_member,
                        $member->genderName,
                        $member->civilStatusName,
                        $member->educationalAttainmentLabel,
                        $member->pwd,
                        $member->pwdTypeName,
                        $member->barangay_name,
                        $member->purok_no,
                        $member->currentAge,
                        $status, 
                        ActiveRecord::RECORD_ACTIVE,
                        App::identity('id'),
                        App::identity('id'),
                        new Expression('UTC_TIMESTAMP'), 
                        new Expression('UTC_TIMESTAMP')
                    ];
                }
            }

            if ($data) {
                $arr = array_chunk($data, 1000);
                $tableName = EventMember::tableName();

                foreach ($arr as $r) {
                    App::createCommand()
                        ->batchInsert(
                            $tableName, 
                            [
                                'event_id', 
                                'member_id', 
                                'name',
                                'qr_id',
                                'household_no',
                                'family_head',
                                'solo_parent',
                                'solo_member',
                                'gender',
                                'civil_status',
                                'educational_attainment',
                                'pwd',
                                'pwd_type',
                                'barangay',
                                'purok_no',
                                'age',
                                'status', 
                                'record_status', 
                                'created_by', 
                                'updated_by', 
                                'created_at', 
                                'updated_at'
                            ], 
                            $r
                        )
                        ->execute();
                }
            }
        }
    }

    public function removeMembers($member_ids)
    {
        $ids = array_map('intval', $member_ids);

        return EventMember::deleteAll([
            'event_id' => $this->id,
            'id' => $ids
        ]);
    }

    public function addMember($member_id)
    {
        $em = new EventMember([
            'event_id' => $this->id,
            'member_id' => $member_id,
        ]);

        if ($this->category_type == self::UN_PLANNED_CATEGORY) {
            $em->status = EventMember::ATTENDED;
        }
        else {
            $em->status = $this->isAssistance ? EventMember::UNCLAIM: EventMember::UNATTENDED;
        }

        if ($em->save()) {
            return $em;
        }
        else {
            $this->addError('eventMember', $em->errors);
        }
    }

    public function getBeneficiaryData()
    {
        $data = [];

        if (($beneficiaries = $this->beneficiaries) != null) {
            foreach ($beneficiaries as $key => $beneficiary) {
                switch ($key) {
                    case 'head':
                        $arr = App::keyMapParams('family_head');
                        $name = 'Family Head';
                        break;

                    case 'sex':
                        $arr = Sex::dropdown();
                        $name = 'Sex';
                        break;

                    case 'pensioner':
                        $arr = App::keyMapParams('pensioners');
                        $name = 'Pensioner';
                        break;

                    case 'civil_status':
                        $arr = CivilStatus::dropdown();
                        $name = 'Civil Status';
                        break;

                    case 'educational_attainment':
                        $arr = EducationalAttainment::dropdown();
                        $name = 'Educational Attainment';
                        break;

                    case 'pwd':
                        $arr = App::keyMapParams('pwd');
                        $name = 'PWD';
                        break;

                    case 'pwd_type':
                        $arr = PwdType::dropdown();
                        $name = 'PWD Type';
                        break;

                    case 'solo_parent':
                        $arr = App::keyMapParams('solo_parent');
                        $name = 'Solo Parent';
                        break;

                    case 'solo_member':
                        $arr = App::keyMapParams('solo_member');
                        $name = 'Solo Member';
                        break;

                    case 'social_pension_status':
                        $arr = App::keyMapParams('social_pension_status');
                        $name = 'Social Pensioner';
                        break;

                    case 'barangay_ids':
                        $arr = ArrayHelper::map(App::setting('address')->barangays, 'id', 'name');
                        $name = 'Barangay';
                        break;

                    case 'purok_no':
                        $arr = Household::dropdown('purok_no', 'purok_no');
                        $name = 'Purok';
                        break;
                    
                    default:
                        $arr = [];
                        $name = '';
                        break;
                }

                if (is_array($beneficiary)) {
                    foreach ($beneficiary as $b) {
                        if ($name) {
                            $data[$name][] = $arr[$b] ?? '';
                        }
                    }
                }
                else {
                    $data[ucwords(str_replace('_', ' ', $key))] = $beneficiary;
                }
            }

            return $data;
        }
    }



    public function getTotalEventMembers()
    {
        return EventMember::find()
            ->where(['event_id' => $this->id])
            ->count();
    }

    public function getGridColumns()
    {
        $columns = parent::getGridColumns();
        unset($columns['active'], $columns['record_status']);

        // $columns['record_status'] = [
        //     'attribute' => 'record_status',
        //     'label' => 'record status',
        //     'format' => 'raw', 
        //     'value' => 'recordStatusBadge'
        // ];

        return $columns;
    }

    public function beneficiaries($queryParams=[])
    {
        if (isset($queryParams['token'])) {
            unset($queryParams['token']);
        }

        $searchModel = new EventMemberSearch([
            'event_id' => $this->id,
            'status' => [
                EventMember::UNCLAIM,
                EventMember::UNATTENDED,
            ]
        ]);
        $searchModel->setAge();
        $dataProvider = $searchModel->search(['EventMemberSearch' => $queryParams]);
        $dataProvider->pagination->route = 'event/view/' . $this->{$this->paramName()};

        $params = App::queryParams();
        $params['page'] = App::get('page');
        $params['per-page'] = App::get('per-page');
        $params['tab'] = 'unclaim';
        unset($params['token']);

        $dataProvider->pagination->params = $params;

        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];
    }

    public function claimed($queryParams=[])
    {
        if (isset($queryParams['token'])) {
            unset($queryParams['token']);
        }

        $searchModel = new EventMemberSearch([
            'event_id' => $this->id,
            'status' => [
                EventMember::CLAIMED,
                EventMember::ATTENDED,
            ]
        ]);
        $searchModel->setAge();
        $dataProvider = $searchModel->search(['EventMemberSearch' => $queryParams]);
        $dataProvider->pagination->route = 'event/view/' . $this->{$this->paramName()};

        $params = App::queryParams();
        $params['page'] = App::get('page');
        $params['per-page'] = App::get('per-page');
        $params['tab'] = 'claimed';
        unset($params['token']);
        $dataProvider->pagination->params = $params;
        
        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];
    }

    public function getTotalPending()
    {
        $model = EventMember::find()
            ->select(['COUNT("*") as total'])
            ->where([
                'event_id' => $this->id,
                'status' => [
                    EventMember::UNCLAIM,
                    EventMember::UNATTENDED,
                ]
            ])
            ->asArray()
            ->one();

        return $model['total'] ?? 0;
    }

    public function getTotalCompleted()
    {
        $model = EventMember::find()
            ->select(['COUNT("*") as total'])
            ->where([
                'event_id' => $this->id,
                'status' => [
                    EventMember::CLAIMED,
                    EventMember::ATTENDED,
                ]
            ])
            ->asArray()
            ->one();

        return $model['total'] ?? 0;
    }

    public function getPendingTabName()
    {
        return $this->isAssistance? 'Unclaimed': 'UnAttended';
    }

    public function getCompletedTabName()
    {
        return $this->isAssistance? 'Claimed': 'Attended';
    }

    public function getEventStatus()
    {
        return EventStatus::widget([
            'event' => $this
        ]);
    }

    public function getCanPending()
    {
        $eventMembers = EventMember::findAll([
            'event_id' => $this->id,
            'status' => [
                EventMember::CLAIMED,
                EventMember::ATTENDED,
            ]
        ]);

        if ($eventMembers && $this->status == self::ONGOING) {
            return false;
        }

        if ($this->status == self::COMPLETED) {
            return false;
        }

        return true;
    }

    public function getNameCategory()
    {
        return implode(' ', [
            $this->name,
            "({$this->categoryLabel})",
        ]);
    }

    public static function selfDropdown()
    {
        $models = self::find()
            ->alias('e')
            ->with('eventCategory')
            ->orderBy(['name' => SORT_ASC])
            ->all();

        $models = ArrayHelper::map($models, 'id', 'nameCategory');

        return $models;
    }

    public function getEventMemberData()
    {
        $searchModel = new EventMemberSearch([
            'event_id' => $this->id
        ]);
        $searchModel->setAge();
        $dataProvider = $searchModel->search(['EventMemberSearch' => App::queryParams()]);

        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];
    }

    public function getIsTypeAssistance()
    {
        return $this->type == self::ASSISTANCE;
    }

    public function getAssistanceTypeLabel()
    {
        $assistanceType = App::keyMapParams('event_assistance_types');

        return $assistanceType[$this->assistance_type] ?? '';
    }

    public function getAssistanceValue()
    {
        return self::ASSISTANCE;
    }

    public function getDefaultTypeValue()
    {
        return self::DEFAULT_TYPE;
    }

    public function getCashValue()
    {
        return self::CASH;
    }

    public function getInkindValue()
    {
        return self::IN_KIND;
    }

    public function getAssistanceTypeOptions()
    {
        if ($this->isNewRecord) {
            return [];
        }

        $assistanceTypes = App::keyMapParams('event_assistance_types');

        if ($this->isTypeAssistance) {
            unset($assistanceTypes[self::DEFAULT_TYPE]);
        }
        else {
            unset($assistanceTypes[self::CASH], $assistanceTypes[self::IN_KIND]);
        }

        return $assistanceTypes;
    }

    public function getSummaryData()
    {
        $attributes = [
            'family_head',
            'solo_parent',
            'gender',
            'civil_status',
            'educational_attainment',
            'pwd',
            // 'pwd_type',
            'barangay',
            'purok_no',
            'age',
        ];

        $data = [];
        foreach ($attributes as $attribute) {

            switch ($attribute) {
                case 'age':
                case 'purok_no':
                    $orderBy = ["CAST({$attribute} AS unsigned)" => SORT_ASC];
                    break;
                
                default:
                    $orderBy = [$attribute => SORT_ASC];
                    break;
            }

            $total = EventMember::find()
                ->select(['COUNT("*") AS total', $attribute])
                ->where(['event_id' => $this->id])
                ->groupBY($attribute)
                ->orderBy($orderBy)
                ->asArray()
                ->all();

            $pending = EventMember::find()
                ->select(['COUNT("*") AS total', $attribute])
                ->where([
                    'event_id' => $this->id,
                    'status' => [EventMember::UNATTENDED, EventMember::UNCLAIM]
                ])
                ->groupBY($attribute)
                ->orderBy($orderBy)
                ->asArray()
                ->all();

            $completed = EventMember::find()
                ->select(['COUNT("*") AS total', $attribute])
                ->where([
                    'event_id' => $this->id,
                    'status' => [EventMember::ATTENDED, EventMember::CLAIMED]
                ])
                ->groupBY($attribute)
                ->orderBy($orderBy)
                ->asArray()
                ->all();

            $data[$attribute]['total'] = ArrayHelper::map($total, $attribute, 'total');
            $data[$attribute]['pending'] = ArrayHelper::map($pending, $attribute, 'total');
            $data[$attribute]['completed'] = ArrayHelper::map($completed, $attribute, 'total');
        }

        return $data;
    }

    public function getPhotoUrl($params=[])
    {
        return Url::image($this->photo, $params);
    }

    public static function fundsDropdown()
    {
        $data = App::keyMapParams('social_pension_funds');
        unset($data[0], $data[self::LOCAL_FUND]);

        return $data;
    }

    public function getFundLabel()
    {
        return App::keyMapParams('social_pension_funds')[$this->social_pension_fund] ?? '';
    }

    public function getConfirmBtn($member) //ForAddingMember to the event
    {
        $eventMember = EventMember::findOne([
            'event_id' => $this->id,
            'member_id' => $member->id
        ]);

        if ($this->category_type == self::UN_PLANNED_CATEGORY) {
            $confirmBtn = $eventMember ? Html::tag('label', 'Already Attended', [
                'class' => 'badge badge-success'
            ]): Html::a('Click to Attend', '#', [
                'class' => 'btn btn-outline-success font-weight-bold mb-3 btn-confirm-add-member',
            ]);
        }
        else {
            $confirmBtn = $eventMember ? Html::tag('label', 'Already beneficiary', [
                'class' => 'badge badge-success'
            ]): Html::a('Add as beneficiary', [
                'event/add-member', 
                'token' => $this->token, 
                'member_id' => $member->id,
            ], [
                'class' => 'btn btn-outline-success font-weight-bold mb-3',
                'data-confirm' => "Add {$member->name} as Beneficiary?",
                'data-method' => 'post'
            ]);
        }

        return $confirmBtn;
    }

    public function getTotalAttended()
    {
        $model = EventMember::find()
            ->select(['COUNT("*") as total', 'gender'])
            ->where([
                'event_id' => $this->id,
                'status' => EventMember::ATTENDED
            ])
            ->groupBy('gender')
            ->asArray()
            ->all();
        
        $model = ArrayHelper::map($model, 'gender', 'total');
        
        return $model;
    }

    public function getTotalMaleBeneficiary()
    {
        return EventMember::find()
            ->where([
                'event_id' => $this->id,
                'gender' => 'Male',
                'status' => [
                    EventMember::CLAIMED,
                    EventMember::ATTENDED
                ]
            ])
            ->count();
    }

    public function getTotalFemaleBeneficiary()
    {
        return EventMember::find()
            ->where([
                'event_id' => $this->id,
                'gender' => 'Female',
                'status' => [
                    EventMember::CLAIMED,
                    EventMember::ATTENDED
                ]
            ])
            ->count();
    }
}