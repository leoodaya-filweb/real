<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\widgets\Anchor;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%barangays}}".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $municipality_id
 * @property int|null $no
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Barangay extends ActiveRecord
{
    const POBLACION_1 = 1;

    public $_totalHouseholds;
    public $_totalMembers;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%barangays}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'barangay',
            'mainAttribute' => 'name',
            'paramName' => 'id',
            'relatedModels' => ['households']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            ['municipality_id', 'exist', 'targetRelation' => 'municipality'],
            [['municipality_id', 'no'], 'integer'],
            [['name'], 'validateName'],
            [['no'], 'validateNo'],
            ['priority_score', 'number']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'name' => 'Name',
            'municipality_id' => 'Municipal Id',
            'no' => 'No',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\BarangayQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\BarangayQuery(get_called_class());
    }

    public function setTheNo($counter=1)
    {
        if ($this->isNewRecord) {
            $address = App::setting('address');

            $count = self::find()
                ->where(['municipality_id' => $address->municipality_id])
                ->count();
            $this->no = $count + $counter;

            $model = self::findOne(['no' => $this->no]);
            if ($model) {
                $this->setTheNo($counter + 1);
            }
        }
    }

    public function validateName($attribute, $params)
    {
        $model = Barangay::findOne([
            'municipality_id' => $this->municipality_id,
            'name' => $this->name,
        ]);

        if (! $this->isNewRecord) {
            $model = Barangay::find()
                ->where([
                    'municipality_id' => $this->municipality_id,
                    'name' => $this->name,
                ])
                ->andWhere(['<>', 'id', $this->id])
                ->one();
        }

        if ($model) {
            $this->addError($attribute, 'No exist with the same municipality.');
        }
    }
    
    public function validateNo($attribute, $params)
    {
        $model = Barangay::findOne([
            'municipality_id' => $this->municipality_id,
            'no' => $this->no,
        ]);

        if (! $this->isNewRecord) {
            $model = Barangay::find()
                ->where([
                    'municipality_id' => $this->municipality_id,
                    'no' => $this->no,
                ])
                ->andWhere(['<>', 'id', $this->id])
                ->one();
        }

        if ($model) {
            $this->addError($attribute, 'No exist with the same municipality.');
        }
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
            'region_name' => ['attribute' => 'regionName', 'format' => 'raw'],
            'province_name' => ['attribute' => 'provinceName', 'format' => 'raw'],
            'municipal_name' => ['attribute' => 'municipalityName', 'format' => 'raw'],
            'no' => ['attribute' => 'no', 'format' => 'numPad3'],
            'priority_score' => ['attribute' => 'priority_score', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'name:raw',
            'regionName:numPad2',
            'provinceName:numPad2',
            'municipalityName:numPad2',
            'no:numPad3',
            'priority_score:raw',
        ];
    }

    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'region_id'])
            ->via('province');
    }

    public function getRegionName()
    {
        if (($region = $this->region) != null) {
            return $region->name;
        }
    }

    public function getProvince()
    {
        return $this->hasOne(Province::className(), ['id' => 'province_id'])
            ->via('municipality');
    }

    public function getProvinceName()
    {
        if (($province = $this->province) != null) {
            return $province->name;
        }
    }

    public function getMunicipality()
    {
        return $this->hasOne(Municipality::className(), ['id' => 'municipality_id']);
    }

    public function getMunicipalityName()
    {
        if (($municipality = $this->municipality) != null) {
            return $municipality->name;
        }
    }

    public static function findByKeywords($keywords='', $attributes, $limit=10, $andFilterWhere=[])
    {
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit, $andFilterWhere) {
            return self::find()
                ->select("{$attribute} AS data")
                ->alias('b')
                ->joinWith(['region r', 'province p', 'municipality m'])
                ->groupBy('data')
                ->where(['LIKE', $attribute, $keywords])
                ->andFilterWhere($andFilterWhere)
                ->limit($limit)
                ->asArray()
                ->all();
        });
    }

    public static function getPoblacion1Id()
    {
        $model = self::find()
            ->alias('b')
            ->joinWith(['region r', 'province p', 'municipality m'])
            ->where([
                'r.no' => Region::REGION_4A,
                'p.no' => Province::CALABARZON,
                'm.no' => Municipality::MUNICIPALITY_REAL,
                'b.no' => self::POBLACION_1
            ])
            ->groupBy('b.id')
            ->one();

        return ($model)? $model->id: 0;
    }

    public function getHouseholds()
    {
        return $this->hasMany(Household::className(), ['barangay_id' => 'id']);
    }

    public function getMembers()
    {
        return $this->hasMany(Member::className(), ['household_id' => 'id'])
            ->via('households');
    }

    public function getTotalHouseholds($formatted = false)
    {
        if ($this->_totalHouseholds === null) {
            $this->_totalHouseholds = Household::find()
                ->where(['barangay_id' => $this->id])
                ->count();
        }

        return ($formatted)? number_format($this->_totalHouseholds): $this->_totalHouseholds;
    }

    public function getTotalMembers($formatted = false)
    {
        if ($this->_totalMembers === null) {
            $model = Member::find()
                ->select(['COUNT("m.*") AS total'])
                ->alias('m')
                ->joinWith('barangay b', false, 'INNER JOIN')
                ->where(['b.id' => $this->id])
                ->asArray()
                ->one();

            $this->_totalMembers = $model['total'] ?? 0;
        }
        
        return ($formatted)? number_format($this->_totalMembers): $this->_totalMembers;
    }
}