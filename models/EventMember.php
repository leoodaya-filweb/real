<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\widgets\Anchor;
use app\widgets\PriorityScoreBadge;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%event_members}}".
 *
 * @property int $id
 * @property int $event_id
 * @property int $member_id
 * @property int $status
 * @property string|null $photo
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class EventMember extends ActiveRecord
{
    const UNCLAIM = 0;
    const CLAIMED = 1;
    const ATTENDED = 2;
    const UNATTENDED = 3;

    public $eventStatus;
    // public $eventType;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%event_members}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'event-member',
            'mainAttribute' => 'id',
            'paramName' => 'id',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['event_id', 'status'], 'required'],
            [['event_id', 'member_id', 'status', 'social_pensioner_id'], 'integer'],
            [['photo'], 'string'],
            ['priority_score', 'number'],
            ['event_id', 'exist', 'targetRelation' => 'event'],
            ['member_id', 'validateMemberId'],
            ['social_pensioner_id', 'SocialPensionerId'],
            ['status', 'in', 'range' => array_keys(App::params('event_member_status'))],
            [['event_id', 'member_id', 'social_pensioner_id'], 'validateExistense'],
            [['gender', 'civil_status', 'educational_attainment', 'pwd_type', 'barangay', 'purok_no', 'age', 'name', 'qr_id', 'household_no'], 'safe'],
            [['family_head', 'solo_parent', 'pwd', 'solo_member'], 'integer'],
            ['pwd', 'in', 'range' => array_keys(App::keyMapParams('pwd'))],
            ['solo_parent', 'in', 'range' => array_keys(App::keyMapParams('solo_parent'))],
            ['solo_member', 'in', 'range' => array_keys(App::keyMapParams('solo_member'))],
            ['family_head', 'in', 'range' => array_keys(App::keyMapParams('family_head'))],
            [['pwd_score', 'senior_score', 'solo_parent_score', 'solo_member_score', 'accessibility_score'], 'number'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'event_id' => 'Event ID',
            'member_id' => 'Member ID',
            'status' => 'Status',
            'photo' => 'Photo',
            'civilStatusName' => 'Civil Status',
            'educationalAttainmentLabel' => 'Education',
            'barangayName' => 'Barangay'
        ]);
    }

    public function validateMemberId($attribute, $params)
    {
        if ($this->member_id) {
            if (($member = Member::findOne($this->member_id)) != null) {
                if ($member->record_status == self::RECORD_DRAFT) {
                    $this->addError($attribute, 'Member status is DRAFT');
                }
            }
            else {
                $this->addError($attribute, 'Member Id invalid');
            }
        }
    }

    public function SocialPensionerId($attribute, $params)
    {
        if ($this->social_pensioner_id) {
            if (($member = Masterlist::findOne($this->social_pensioner_id)) == null) {
                $this->addError($attribute, 'Social Pensioner Id invalid');
            }
        }
    }

    public function validateExistense($attribute, $params)
    {
        if ($this->isNewRecord) {
            if ($this->member_id) {
                $model = self::findOne([
                    'event_id' => $this->event_id,
                    'member_id' => $this->member_id,
                ]);

                if ($model) {
                    $this->addError($attribute, 'Member already exist, claimed or attended.');
                }
            }

            if ($this->social_pensioner_id) {
                $model = self::findOne([
                    'event_id' => $this->event_id,
                    'social_pensioner_id' => $this->social_pensioner_id,
                ]);

                if ($model) {
                    $this->addError($attribute, 'Member already exist, claimed or attended.');
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\EventMemberQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\EventMemberQuery(get_called_class());
    }

    public function getTheAddress()
    {
        return implode(', ', [
            $this->barangay,
            "Purok {$this->purok_no}",
        ]);
    }

    public function getAddress()
    {
        return $this->theAddress;
    }

    public function getHeadBadge()
    {
        if ($this->family_head == Member::FAMILY_HEAD_YES) {
            return Html::tag('label', 'Head', [
                'class' => "badge badge-success"
            ]);
        }
        // else {
        //     $class = 'primary';
        //     $name = 'Member';
        // }
        // return Html::tag('label', $name, [
        //     'class' => "badge badge-{$class}"
        // ]);
    }

    public function getHouseholdTag()
    {
        return implode('<br> ', [
            "<span class='font-weight-bold'>NO:</span> {$this->household_no}",
            "<span class='font-weight-bold'>QR:</span> {$this->qr_id}",
        ]);
    }

    public function getEventType()
    {
        if (($event = $this->event) != null) {
            return $event->type;
        }
    }

    public function getClaimText()
    {
        $text = 'Claim';

        switch ($this->eventType) {
            case Event::SEMINAR:
            case Event::TRAINING:
            case Event::EVENT:
                if ($this->isClaimOrAttended) {
                    $text = 'Attended';
                }
                else {
                    $text = 'Attend';
                }
                break;
            case Event::ASSISTANCE:
                if ($this->isClaimOrAttended) {
                    $text = 'Claimed';
                }
                else {
                    $text = 'Claim';
                }
                break;
            
            default:
                $text = 'Claim';
                break;
        }

        return $text;
    }

    public function getGridColumns()
    {
        return $this->gridColumnsView;
    }

    public function getSocialPensionGridColumnsView()
    {
        $columns = $this->socialPensionerColumns;
        unset($columns['checkbox'], $columns['action']);

        $columns['actions'] = [
            'class' => 'yii\grid\ActionColumn',
            'header' => '<span style="color:#3699FF">Action</span>',
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center', 'width' => '70'],
            'template' => '{view}',
            'buttons' => [
                'view' => function($url, $eventMember, $index) {
                    if (($member = $eventMember->member) != null) {
                        return Html::a('Profile', $member->viewUrl, [
                            'class' => 'btn btn-sm btn-outline-info font-weight-bold',
                            'target' => '_blank'
                        ]);
                    }
                    if (($masterlist = $eventMember->masterlist) != null) {
                        return Html::a('Profile', '#', [
                            'data-slug' => $eventMember->socialPensionerSlug,
                            'class' => 'btn btn-sm btn-outline-info font-weight-bold btn-profile',
                        ]);
                    }
                    
                },
            ]
        ];

        return $columns;
    }

    public function getGridColumnsView()
    {
        $columns = $this->gridColumnsCreate;
        unset($columns['checkbox'], $columns['action']);

        $columns['actions'] = [
            'class' => 'yii\grid\ActionColumn',
            'header' => '<span style="color:#3699FF">Action</span>',
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center', 'width' => '70'],
            'template' => '{claim}',
            'buttons' => [
                'claim' => function($url, $eventMember, $index) {
                    if ($eventMember->isClaimOrAttended) {
                        return Anchor::widget([
                            'title' => 'Details',
                            'link' =>  '#!',
                            'options' => [
                                'class' => 'btn btn-success btn-sm btn-event-member-detail',
                                'title' => 'Details',
                                'data-qr_id' => $eventMember->qrId
                            ]
                        ]);
                    }

                    $class = $eventMember->eventStatus == Event::ONGOING ? 'btn btn-primary btn-sm btn-claim': 'badge badge-primary';
                    return Anchor::widget([
                        'title' => $eventMember->claimText,
                        'link' =>  '#!',
                        'options' => [
                            'class' => $class,
                            'title' => $eventMember->claimText,
                            'data-qr_id' => $eventMember->qrId
                        ]
                    ]);
                },
            ]
        ];

        return $columns;
    }

    public function getHeadLabel()
    {
        if ($this->family_head == Member::FAMILY_HEAD_YES) {
            return 'Head';
        }

        return 'Member';
    }

    public function getSoloParentLabel()
    {
        // if(($member = $this->member) != null) {
        //     if (isset(App::params('solo_parent')[$member->solo_parent])) {
        //         return App::params('solo_parent')[$member->solo_parent]['label'];
        //     }
        // }

        return App::keyMapParams('solo_parent')[$this->solo_parent] ?? '';
    }

    public function getSoloMemberLabel()
    {
        return App::keyMapParams('solo_member')[$this->solo_member] ?? '';
    }

    public function getPwdLabel()
    {
        return App::keyMapParams('pwd')[$this->pwd] ?? '';
        // if(($member = $this->member) != null) {
        //     if (isset(App::params('pwd')[$member->pwd])) {
        //         return App::params('pwd')[$member->pwd]['label'];
        //     }
        // }
    }

    public function getExportColumns()
    {
        $columns = [
            'serial' => ['class' => 'yii\grid\SerialColumn'],
            // 'qrId' => [
            //     'label' => 'qr Id',
            //     'format' => 'raw',
            //     'value' => 'qrId',
            // ],
            
            'name' => [
                'label' => 'MEMBER',
                'format' => 'text',
                'value' => 'name',
            ],

            'solo_parent' => [
                'label' => 'SOLO PARENT',
                'format' => 'text',
                'value' => 'soloParentLabel',
            ],

            'solo_member' => [
                'label' => 'SOLO MEMBER',
                'format' => 'text',
                'value' => 'soloMemberLabel',
            ],
            'pwd' => [
                'label' => 'PWD',
                'format' => 'text',
                'value' => 'pwdLabel',
            ],
          
            'headBadge' => [
                'label' => 'HEAD', 
                'format' => 'text',
                'value' => 'headLabel',
            ],
            
            'gender' => [
                'label' => 'GENDER',
                'value' => 'gender',
                'format' => 'text',
            ],
          
            'civil_status' => [
                'label' => 'CIVIL STATUS',
                'value' => 'civil_status',
                'format' => 'raw',
            ],
            'educational_attainment' => [
                'label' => 'EDUCATION',
                'value' => 'educational_attainment',
                'format' => 'raw',
            ],
            'address' => [
                'label' => 'ADDRESS',
                'value' => 'address', 
                'format' => 'raw',
            ],
            'purok_no' => [
                'label' => 'PUROK',
                'value' => 'purok_no', 
                'format' => 'raw',
            ],
            'age' => [
                'label' => 'AGE',
                'value' => 'age', 
                'format' => 'raw',
            ],
        ];

        if ($this->event->category_type == Event::SOCIAL_PENSION_CATEGORY) {
            unset(
                $columns['solo_parent'],
                $columns['solo_member'],
                $columns['pwd'],
                $columns['headBadge'],
            );
            $columns['priority_score'] = [
                'attribute' => 'priority_score', 
                'format' => 'raw',
                'value' => function($model) {
                    return App::formatter('asNumber', $model->priorityScore);
                }
            ];
        }

        return $columns;
    }

    public function getSoloParentBadge()
    {
        if ($this->solo_parent == Member::SOLO_PARENT_YES) {
            return Html::tag('label', 'Solo Parent', [
                'class' => 'badge badge-primary'
            ]);
        }
    }

    public function getSoloMemberBadge()
    {
        if ($this->solo_member == Member::SOLO_MEMBER_YES) {
            return Html::tag('label', 'Solo Member', [
                'class' => 'badge badge-secondary'
            ]);
        }
    }

    public function getPwdBadge()
    {
        if ($this->pwd == Member::PWD_YES) {
            return Html::tag('label', 'PWD', [
                'class' => 'badge badge-secondary'
            ]);
        }
    }

    public function getPwdTypeBadge()
    {
        if ($this->pwd_type) {
            return Html::tag('label', $this->pwd_type, [
                'class' => 'badge badge-secondary'
            ]);
        }
    }

    public function getSocialPensionerColumns()
    {
        $columns = $this->gridColumnsCreate;
        unset($columns['household'], $columns['action']);

        $columns['name']['value'] = function($model) {
            return implode(' ', [
                Html::tag('span', $model->name, [
                    'data-slug' => $model->socialPensionerSlug,
                    'class' => 'btn-profile'
                ]),
            ]);
        };

        $columns['priority_score'] = [
            'attribute' => 'priority_score', 
            'format' => 'raw',
            'value' => function($model) {
                return PriorityScoreBadge::widget([
                    'model' => $model
                ]);
            }
        ];

        $columns['action'] = [
            'label' => '',
            'format' => 'raw',
            'headerOptions' => ['class' => 'text-center', 'width' => 70],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function($model) {
                return Html::a('<i class="fa fa-trash"></i>', $model->deleteUrl, [
                    'class' => 'btn btn-light-danger btn-icon btn-sm',
                    'data-confirm' => "Remove {$model->memberName}?",
                    'data-method' => 'post'
                ]);
            }
        ];


        return $columns;
    }
    
    public function getGridColumnsCreate()
    {
        return [
            'serial' => ['class' => 'yii\grid\SerialColumn'],
            'checkbox' => ['class' => 'app\widgets\CheckboxColumn'],
            'name' => [
                'attribute' => 'name', 
                'format' => 'raw',
                'value' => function($model) {

                    return implode(' ', [
                        Html::tag('span', $model->name, [
                            'data-qr_id' => $model->qrId,
                            'class' => 'btn-profile'
                        ]),
                        '<br>',
                        $model->headBadge,
                        $model->soloParentBadge,
                        $model->soloMemberBadge,
                        $model->pwdBadge,
                        $model->pwdTypeBadge,
                    ]);
                },
            ],
            'household' => [
                'label' => 'household',
                'attribute' => 'household_no',
                'format' => 'raw',
                'value' => 'householdTag',
            ],
            
            'gender' => [
                'label' => 'gender',
                'attribute' => 'gender', 
                'format' => 'raw',
            ],
          
            'civil_status' => [
                'label' => 'civil status',
                'attribute' => 'civil_status', 
                'format' => 'raw',
            ],
            'education' => [
                'label' => 'education',
                'attribute' => 'educational_attainment', 
                'format' => 'raw',
            ],
            'address' => [
                'label' => 'address',
                'attribute' => 'barangay', 
                'value' => 'address',
                'format' => 'raw',
            ],
            'age' => [
                'label' => 'age',
                'attribute' => 'age', 
                'format' => 'raw',
            ],
            'action' => [
                'label' => '',
                'format' => 'raw',
                'headerOptions' => ['class' => 'text-center', 'width' => 70],
                'contentOptions' => ['class' => 'text-center'],
                'value' => function($model) {
                    return Html::a('<i class="fa fa-trash"></i>', $model->deleteUrl, [
                        'class' => 'btn btn-light-danger btn-icon btn-sm',
                        'data-confirm' => "Remove {$model->memberName}?",
                        'data-method' => 'post'
                    ]);
                }
            ]
        ];
    }


    public function getHead()
    {
        if (($member = $this->member) != null) {
           return $member->isHead ? 'Head': 'Member';
        }
    }
     
    public function gridColumns()
    {
        return [
            'event_id' => [
                'attribute' => 'event_id', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->event_id,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'member_id' => ['attribute' => 'member_id', 'format' => 'raw'],
            // 'photo' => ['attribute' => 'photo', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'event_id:raw',
            'member_id:raw',
            'photo:raw',
        ];
    }

    public function getBarangay()
    {
        return $this->hasOne(Barangay::className(), ['no' => 'barangay_id'])
            ->via('household');
    }

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }

    public function getHousehold()
    {
        return $this->hasOne(Household::className(), ['id' => 'household_id'])
            ->via('member');
    }

    public function getHouseholdNo()
    {
        if (($household = $this->household) != null) {
            return $household->no;
        }
    }

    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    public function getEventName()
    {
        if (($event = $this->event) != null) {
            return $event->name;
        }
    }

    public function getEventAmount()
    {
        if (($event = $this->event) != null) {
            return $event->amount;
        }
    }

    public function getDate()
    {
        return App::formatter()
            ->asDateToTimezone($this->created_at, 'm/d/Y');
    }

    // public static function findByKeywords($keywords='', $attributes, $limit=10, $andFilterWhere=[])
    // {
    //     return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit, $andFilterWhere) {
    //         return self::find()
    //             ->select("{$attribute} AS data")
    //             ->alias('em')
    //             ->joinWith(['member m', 'event e', 'household h', 'barangay b'])
    //             ->with(['gender', 'civilStatus', 'educationalAttainment'])
    //             ->groupBy('data')
    //             ->where(['LIKE', $attribute, $keywords])
    //             ->andFilterWhere($andFilterWhere)
    //             ->limit($limit)
    //             ->asArray()
    //             ->all();
    //     });
    // }


    public function getMemberAddress()
    {
        if (($model = $this->member) != null) {
            return $model->address;
        }
    }

    public function getMemberName()
    {
        if (($model = $this->member) != null) {
            return $model->name;
        }
    }

    public function getMemberQrId()
    {
        if (($model = $this->member) != null) {
            return $model->qr_id;
        }
    }

    public function getMemberFullname()
    {
        if (($model = $this->member) != null) {
            return $model->fullname;
        }
    }

    public function getBeneficiaryView()
    {
        if (($model = $this->member) != null) {
            return $model->beneficiaryView;
        }
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['EventMemberBehavior'] = ['class' => 'app\behaviors\EventMemberBehavior'];

        return $behaviors;
    }

    public function getStatusBadge()
    {
        $s = App::params('event_member_status')[$this->status] ?? [];

        return Html::tag('label', $s['label'] ?? '', [
            'class' => 'badge badge-' . ($s['class'] ?? '')
        ]);
    }

    public function getQrId()
    {
        if (($model = $this->member) != null) {
            return $model->qr_id;
        }
    }

    // public function getAddress()
    // {
    //     if (($model = $this->member) != null) {
    //         return $model->address;
    //     }
    // }

    public function getFulldate()
    {
        return App::formatter()->asFulldate($this->created_at);
    }


    public static function oldestAge($condition=[])
    {
        $data = self::find()
            ->orderBy(['age' => SORT_DESC])
            ->andFilterWhere($condition)
            ->asArray()
            ->one();

        return $data['age'] ?? 0;
    }

    public static function youngestAge($condition=[])
    {
        $data = self::find()
            ->orderBy(['age' => SORT_ASC])
            ->andFilterWhere($condition)
            ->asArray()
            ->one();

        return $data['age'] ?? 0;
    }

    public static function ageDropdown()
    {
        $models = self::find()
            ->orderBy(['age' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($models, 'age', 'age');
    }

    public function getEventDetailView()
    {
        if (($event = $this->event) != null) {
            return $event->detailView;
        }
    }

    public function getEventViewUrl()
    {
        if (($event = $this->event) != null) {
            return $event->viewUrl;
        }
    }

    public function getCategoryBadge()
    {
        if (($event = $this->event) != null) {
            return $event->categoryBadge;
        }
    }

    public function getCategoryLabel()
    {
        if (($event = $this->event) != null) {
            return $event->categoryLabel;
        }
    }

    public function getIsClaimOrAttended()
    {
        return in_array($this->status, [
            self::CLAIMED,
            self::ATTENDED,
        ]);
    }

    public function received()
    {
        if ($this->validate()) {
            if ($this->event->status == Event::ONGOING) {
                if ($this->eventType == Event::ASSISTANCE) {
                    $this->status = self::CLAIMED;
                }
                else {
                    $this->status = self::ATTENDED;
                }

                return $this->save();
            }
            else {
                $this->addError('event_status', 'The event is not yet ongoing.');
            }
        }
    }

    public static function filter($key='id', $condition=[], $limit=false, $andFilterWhere=[])
    {
        $orderBy = ($key == 'purok_no')? ["CAST(purok_no AS unsigned)" => SORT_ASC]: [$key => SORT_ASC];

        $models = self::find()
            ->andFilterWhere($condition)
            ->andFilterWhere($andFilterWhere)
            ->andWhere(['<>', $key, ''])
            ->orderBy($orderBy)
            ->limit($limit)
            ->groupBy($key)
            ->asArray()
            ->all();

        $models = ArrayHelper::map($models, $key, $key);

        return $models;
    }

    public function getMasterlist()
    {
        return $this->hasOne(Masterlist::className(), ['id' => 'social_pensioner_id']);
    }

    public function getSocialPensioner()
    {
        return $this->hasOne(SocialPensioner::className(), ['id' => 'social_pensioner_id']);
    }

    public function getSocialPensionerSlug()
    {
        if (($socialPensioner = $this->socialPensioner) != null) {
            return $socialPensioner->slug;
        }
    }

    public function getPriorityScore()
    {
        return $this->priority_score;
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
}