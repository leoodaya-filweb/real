<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\widgets\Anchor;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%municipalities}}".
 *
 * @property int $id
 * @property string $name
 * @property int|null $province_id
 * @property int|null $no
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Municipality extends ActiveRecord
{
    const MUNICIPALITY_REAL = 38;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%municipalities}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'municipality',
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
            [['province_id', 'no'], 'integer'],
            ['province_id', 'exist', 'targetRelation' => 'province'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'validateName'],
            [['no'], 'validateNo'],
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
            'province_id' => 'Province',
            'no' => 'No',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\MunicipalityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\MunicipalityQuery(get_called_class());
    }

    public function setTheNo($counter=1)
    {
        if ($this->isNewRecord) {
            $address = App::setting('address');

            $count = self::find()
                ->where(['province_id' => $address->province_id])
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
        $model = Municipality::findOne([
            'province_id' => $this->province_id,
            'name' => $this->name,
        ]);

        if (! $this->isNewRecord) {
            $model = Municipality::find()
                ->where([
                    'province_id' => $this->province_id,
                    'name' => $this->name,
                ])
                ->andWhere(['<>', 'id', $this->id])
                ->one();
        }

        if ($model) {
            $this->addError($attribute, 'Name exist with the same province.');
        }
    }

    public function getHouseholds()
    {
        return $this->hasMany(Household::className(), ['municipality_id' => 'id']);
    }
    
    public function validateNo($attribute, $params)
    {
        $model = Municipality::findOne([
            'province_id' => $this->province_id,
            'no' => $this->no,
        ]);

        if (! $this->isNewRecord) {
            $model = Municipality::find()
                ->where([
                    'province_id' => $this->province_id,
                    'no' => $this->no,
                ])
                ->andWhere(['<>', 'id', $this->id])
                ->one();
        }

        if ($model) {
            $this->addError($attribute, 'No exist with the same province.');
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
            'no' => ['attribute' => 'no', 'format' => 'numPad2'],
        ];
    }

    public function detailColumns()
    {
        return [
            'name:raw',
            'regionName:numPad2',
            'provinceName:numPad2',
            'no:numPad2',
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
        return $this->hasOne(Province::className(), ['id' => 'province_id']);
    }

    public function getProvinceName()
    {
        if (($province = $this->province) != null) {
            return $province->name;
        }
    }

    public static function findByKeywords($keywords='', $attributes, $limit=10, $andFilterWhere=[])
    {
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit, $andFilterWhere) {
            return self::find()
                ->select("{$attribute} AS data")
                ->alias('m')
                ->joinWith(['region r', 'province p'])
                ->groupBy('data')
                ->where(['LIKE', $attribute, $keywords])
                ->andFilterWhere($andFilterWhere)
                ->limit($limit)
                ->asArray()
                ->all();
        });
    }

    public static function getRealId()
    {
        $model = self::find()
            ->alias('m')
            ->joinWith(['region r', 'province p'])
            ->where([
                'r.no' => Region::REGION_4A,
                'p.no' => Province::CALABARZON,
                'm.no' => self::MUNICIPALITY_REAL,
            ])
            ->groupBy('m.id')
            ->one();

        return ($model)? $model->id: 0;
    }
}