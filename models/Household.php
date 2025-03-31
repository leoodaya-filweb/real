<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\File;
use app\widgets\Anchor;
use app\widgets\HouseholdDetail;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%households}}".
 *
 * @property int $id
 * @property int $no
 * @property string|null $transfer_date
 * @property string|null $longitude
 * @property string|null $latitude
 * @property string|null $altitude
 * @property int|null $region_id
 * @property int|null $province_id
 * @property int|null $municipality_id
 * @property int|null $zone_no
 * @property int|null $barangay_id
 * @property int|null $purok_no
 * @property int|null $lot_no
 * @property string|null $street
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Household extends ActiveRecord
{
    const STEP_FORM = [
        1 => [
            'id' => 1,
            'slug' => 'general-information',
            'title' => 'General Information',
            'description' => 'Geo-Location & Address',
        ],
        2 => [
            'id' => 2,
            'slug' => 'map',
            'title' => 'Map Plotting',
            'description' => 'Map plotting location',
        ],
        3 => [
            'id' => 3,
            'slug' => 'family-head',
            'title' => 'Family Head',
            'description' => 'Head of the family\'s Information',
        ],
        4 => [
            'id' => 4,
            'slug' => 'family-composition',
            'title' => 'Family Composition',
            'description' => 'Family Members',
        ],
        5 => [
            'id' => 5,
            'slug' => 'summary',
            'title' => 'Summary',
            'description' => 'Information Summary',
        ],
    ];

    const STEP_FORM_VIEW = [
        1 => [
            'id' => 1,
            'slug' => 'overview',
            'title' => 'Overview',
            'description' => 'Summary Data',
        ],
        2 => [
            'id' => 2,
            'slug' => 'general-information',
            'title' => 'General Information',
            'description' => 'Geo-Location & Address',
        ],
        3 => [
            'id' => 3,
            'slug' => 'map',
            'title' => 'Map View',
            'description' => 'Latitude, Longitude & Directions',
        ],
        4 => [
            'id' => 4,
            'slug' => 'family-head',
            'title' => 'Family Head',
            'description' => 'Head of the family\'s Information',
        ],
        5 => [
            'id' => 5,
            'slug' => 'family-composition',
            'title' => 'Family Composition',
            'description' => 'Family Members',
        ],
        6 => [
            'id' => 6,
            'slug' => 'dafac',
            'title' => 'DAFAC',
            'description' => 'Disaster Assistance Family Access Card',
        ],
    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%households}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'household',
            'mainAttribute' => 'no',
            'paramName' => 'no',
            'relatedModels' => ['members']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['no', 'region_id', 'province_id', 'municipality_id'], 'required'],
            [['no', 'region_id', 'province_id', 'municipality_id', 'zone_no', 'barangay_id', 'lot_no'], 'integer'],
            [['transfer_date', 'blk_no'], 'safe'],
            [['blk_no'], 'string', 'max' => 32],
            [['longitude', 'latitude', 'altitude', 'street', 'purok_no'], 'string', 'max' => 255],
            [['no'], 'unique'],
            ['region_id', 'exist', 'targetRelation' => 'region', 'on' => 'manual'],
            ['province_id', 'exist', 'targetRelation' => 'province', 'on' => 'manual'],
            ['municipality_id', 'exist', 'targetRelation' => 'municipality', 'on' => 'manual'],
            [['longitude', 'latitude',], 'required', 'on' => 'map'],
            ['transfer_date', 'validateTransferDate'],
            [['sitio', 'landmark'], 'string', 'max' => 255],
            [['files'], 'safe']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'no' => 'Household No',
            'transfer_date' => 'Transfer Date',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'altitude' => 'Altitude',
            'region_id' => 'Region No',
            'province_id' => 'Province No',
            'municipality_id' => 'Municipal No',
            'zone_no' => 'Zone No',
            'barangay_id' => 'Barangay',
            'purok_no' => 'Purok',
            'blk_no' => 'Blk No',
            'lot_no' => 'Lot No',
            'street' => 'Street',
            'regionName' => 'Region',
            'provinceName' => 'Province',
            'municipalityName' => 'Municipality',
            'barangayName' => 'Barangay',
            'purokNo' => 'Purok'
        ]);
    }

    public function validateTransferDate($attribute, $params)
    {
        $today = strtotime(App::formatter()->asDateToTimezone());
        $transfer_date = strtotime($this->transfer_date);

        if ($transfer_date > $today) {
            $this->addError($attribute, 'Transfer date is greater than the date today.');
        }
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\HouseholdQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\HouseholdQuery(get_called_class());
    }

    public function setTheNo($counter=1)
    {
        if ($this->isNewRecord) {
            $count = self::find()->andWhere(['new_cbms'=>1])->count();

            $this->no = date('Y').($count + $counter);
            

            $model = self::findOne(['no' => $this->no]);
            if ($model) {
                return $this->setTheNo($counter + 1);
            }

            return $this->no;
        }
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

        return $actions;
    }

    public function getGridColumns()
    {
        $columns = parent::getGridColumns();
        unset($columns['active']);

        $columns['status'] = [
            'attribute' => 'record_status',
            'label' => 'status',
            'format' => 'raw', 
            'value' => 'recordStatusBadge'
        ];

        return $columns;
    }
     
    public function gridColumns()
    {
        return [
            'household_no' => [
                'attribute' => 'no', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->no,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],

            'family_head' => [
                'attribute' => 'headerName', 
                'format' => 'raw',
                'label' => 'family head'
            ],
            'totalMembers' => ['attribute' => 'totalMembers', 'format' => 'raw'],
            'transfer_date' => ['attribute' => 'transfer_date', 'format' => 'raw'],
            // 'longitude' => ['attribute' => 'longitude', 'format' => 'raw'],
            // 'latitude' => ['attribute' => 'latitude', 'format' => 'raw'],
            // 'altitude' => ['attribute' => 'altitude', 'format' => 'raw'],
            // 'region_id' => ['attribute' => 'region_id', 'format' => 'raw'],
            // 'province_id' => ['attribute' => 'province_id', 'format' => 'raw'],
            // 'municipality_id' => ['attribute' => 'municipality_id', 'format' => 'raw'],
            'barangay_name' => ['attribute' => 'barangayName', 'format' => 'raw'],
            'zone_no' => ['attribute' => 'zone_no', 'format' => 'raw'],
            'purok_no' => ['attribute' => 'purok_no', 'format' => 'raw'],
            'blk_no' => ['attribute' => 'blk_no', 'format' => 'raw'],
            'lot_no' => ['attribute' => 'lot_no', 'format' => 'raw'],
            'street' => ['attribute' => 'street', 'format' => 'raw'],
        ];
    }

    public function getDetailView()
    {
        return HouseholdDetail::widget(['model' => $this]);
    }

    public function detailColumns()
    {
        return [
            'no:raw',
            'transfer_date:raw',
            'longitude:raw',
            'latitude:raw',
            'altitude:raw',
            'region_id:raw',
            'province_id:raw',
            'municipality_id:raw',
            'zone_no:raw',
            'barangay_id:raw',
            'purok_no:raw',
            'blk_no:raw',
            'lot_no:raw',
            'street:raw',
        ];
    }

    public function getRegionName()
    {
        if (($region = $this->region) != null) {
            return $region->name;
        }
    }

    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'region_id']);
    }


    public function getProvinceName()
    {
        if (($province = $this->province) != null) {
            return $province->name;
        }
    }

    public function getProvince()
    {
        return $this->hasOne(Province::className(), ['id' => 'province_id']);
    }

    public function getMunicipalityName()
    {
        if (($municipality = $this->municipality) != null) {
            return $municipality->name;
        }
    }

    public function getMunicipality()
    {
        return $this->hasOne(Municipality::className(), ['id' => 'municipality_id']);
    }

    public function getBarangayName()
    {
        if (($barangay = $this->barangay) != null) {
            return $barangay->name;
        }
    }

    public function getBarangay()
    {
        return $this->hasOne(Barangay::className(), ['no' => 'barangay_id']);
    }

    public function getFamilyCompositionForm($id='')
    {
        $member = new Member([
            'household_id' => $this->id,
            'head' => Member::FAMILY_HEAD_NO,
            'record_status' => 1 //Household::RECORD_DRAFT,
        ]);

        if (($m = Member::findOne($id)) != null) {
            $model = $m;
        }
        else {
            $model = $member;
        }

        $model->scenario = 'family-composition';

        return $model;
    }

    public function getFamilyHeadForm()
    {
        $model = $this->familyHead ?: new Member([
            'household_id' => $this->id,
            'head' => Member::FAMILY_HEAD_YES,
            'record_status' => 1 //Household::RECORD_DRAFT,
        ]);
        $model->scenario = 'family-head';
        
        return $model;
    }

    public function getFamilyCompositions()
    {
        return $this->hasMany(Member::className(), ['household_id' => 'id'])
            ->with('gender', 'civilStatus', 'relationModel')
           // ->onCondition(['head' => Member::FAMILY_HEAD_NO])
            ;
    }

    public function getHeaderName()
    {
        if (($model = $this->familyHead) != null) {
            return $model->fullname;
        }
    }

    public function getHeaderPhoto()
    {
        if (($model = $this->familyHead) != null) {
            return Html::image($model->photo, ['w' => 50, 'quality' => 90]);
        }

        return Html::image('', ['w' => 50, 'quality' => 90]);
    }

    public function getFamilyHead()
    {
        return $this->hasOne(Member::className(), ['household_id' => 'id'])
            ->onCondition(['head' => Member::FAMILY_HEAD_YES]);
    }

    public function getMembers()
    {
        return $this->hasMany(Member::className(), ['household_id' => 'id'])
            ->orderBy([
                'head' => SORT_DESC,
                'birth_date' => SORT_ASC,
            ]);
    }

    public function getLastMember()
    {
        return $this->hasOne(Member::className(), ['household_id' => 'id'])
            ->orderBy(['id' => SORT_DESC]);
    }

    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['member_id' => 'id'])
            ->via('members');
    }

    public function getTotalTransactions()
    {
        return Transaction::find()
            ->alias('t')
            ->joinWith('member m')
            ->where(['m.household_id' => $this->id])
            ->groupBy('m.id')
            ->count();
    }

    public function getTotalAmountTransactions()
    {
        $model = Transaction::find()
            ->select(['SUM(t.amount) AS total_amount'])
            ->alias('t')
            ->joinWith('member m')
            ->where(['m.household_id' => $this->id])
            ->groupBy('t.id')
            ->asArray()
            ->one();
        
        return App::formatter()->asNumber($model['total_amount'] ?? 0);
    }

    public function getTotalAmountTransactionsTag()
    {
        return Html::tag('div', $this->totalAmountTransactions, [
            'class' => 'text-center font-weight-bolder'
        ]);
    }

    public function getTotalTransactionsTag()
    {
        return Html::tag('div', $this->totalTransactions, [
            'class' => 'text-center font-weight-bolder'
        ]);
    }

    public function getTotalFamilyComposition()
    {
        $totalMembers = $this->totalMembers;

        return ($totalMembers == 0)? 0: $totalMembers;
    }

    public function getTotalMembers()
    {
        return Member::find()
            ->where(['household_id' => $this->id])
            ->count();
    }

    public function getTotalMembersTag()
    {
        return Html::tag('div', $this->totalMembers, [
            'class' => 'text-center font-weight-bolder'
        ]);
    }

    public function getFamilyCompositionsId()
    {
        if (($members = $this->familyCompositions) != null) {
            return array_keys(ArrayHelper::map($members, 'id', 'id'));
        }
    }

    public function getAddressColumns()
    {
        return [
            'region_id:raw',
            'province_id:raw',
            'municipality_id:raw',
            'zone_no:raw',
            'barangay_id:raw',
            'purok_no:raw',
            'blk_no:raw',
            'lot_no:raw',
            'street:raw',
        ];
    }

    public function getAddress()
    {
        $address = [];


        if ($this->street && trim($this->street) != 'NONE' && trim($this->street)!= '-') {
            $address[] = "{$this->street} St.";
        }


        if ($this->purok_no) {
            $this->purok_no=ucwords(strtolower($this->purok_no));
            $address[] = "Purok {$this->purok_no}";
        }

        if ($this->sitio) {
            $address[] = "Sitio {$this->sitio}";
        }

        if ($this->barangay_id) {
            $barangayName = trim($this->barangayName);
            $address[] = "Brgy. {$barangayName},";
        }

        // if ($this->blk_no) {
        //     $address[] = "Blk {$this->blk_no}";
        // }


        // if ($this->lot_no) {
        //     $address[] = "Lot {$this->lot_no}";
        // }

        $address[] = ' '.ucwords(strtolower(App::setting('address')->municipalityName));
        $address[] = ucwords(strtolower(App::setting('address')->provinceName));

        return implode(' ', $address);
    }

    public function getGeneralInformationColumns()
    {
        return [
            'no:raw',
            'transfer_date:raw',
        ];
    }

    public function getHouseholdNo()
    {
        return $this->no;
    }

    public function getImageFiles()
    {
        if (($photos = $this->files) != null) {
            $files = [];

            foreach ($photos as $token) {
                if (($file = File::findByToken($token)) != null) {
                    $files[] = $file;
                }
            }

            return $files;
        }
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['JsonBehavior']['fields'] = [
            'files',
        ];

      
        $behaviors['HouseholdBehavior'] = [
            'class' => 'app\behaviors\HouseholdBehavior'
        ];

        return $behaviors;
    }

    public function getPurokNo()
    {
        return $this->purok_no ?: 'None';
    }

    public function getBlkNo()
    {
        return $this->blk_no ?: 'None';
    }

    public function getLotNo()
    {
        return $this->lot_no ?: 'None';
    }

    public function getZoneNo()
    {
        return $this->zone_no ?: 'None';
    }

    public function getStreetName()
    {
        return $this->street ?: 'None';
    }

    public static function findByKeywords($keywords='', $attributes='', $limit=10, $andFilterWhere=[])
    {
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit, $andFilterWhere) {
            return self::find()
                ->select("{$attribute} AS data")
                ->alias('h')
                ->joinWith('barangay b')
                ->groupBy('data')
                ->where(['LIKE', $attribute, $keywords])
                ->andFilterWhere($andFilterWhere)
                ->limit($limit)
                ->asArray()
                ->all();
        });
    }

    public static function findByMemberNoKeywords($keywords='', $attributes='', $limit=10)
    {
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit) {
            return self::find()
                ->select("{$attribute} AS data")
                ->alias('h')
                ->joinWith('members m')
                ->groupBy('data')
                ->where(['LIKE', $attribute, $keywords])
                ->andWhere(['m.new_cbms'=>1])
                ->limit($limit)
                ->asArray()
                ->all();
        });
    }

    public function getDefaultGridColumns()
    {
        return [
            'serial',
            'checkbox',
            'household_no',
            'family_head',
            'totalMembers',
            'transfer_date',
            'barangay_name',
            // 'zone_no',
            // 'purok_no',
            // 'blk_no',
            // 'lot_no',
            // 'street',
            'status',
            'created_at',
        ];
    }

    public function getHouseholdMembers()
    {
        return $this->hasMany(HouseholdMember::className(), ['household_id' => 'id']);
    }

    public function getInactiveFamilyCompositions()
    {
        return $this->hasMany(Member::className(), ['id' => 'member_id'])
            ->via('householdMembers');
    }

    public static function recent($limit = 6)
    {
        return self::find()
            ->orderBy(['id' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    public function getUpdateUrlFamilyCompositionTab($fullpath=true)
    {
        if ($this->checkLinkAccess('update')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'update']),
                $paramName => $this->{$paramName},
                'step' => 'family-composition'
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }
}