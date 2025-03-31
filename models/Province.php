<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\widgets\Anchor;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%provinces}}".
 *
 * @property int $id
 * @property string $name
 * @property int|null $region_id
 * @property int|null $no
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Province extends ActiveRecord
{
    const CALABARZON = 56;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%provinces}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'province',
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
            [['region_id', 'no'], 'integer'],
            ['region_id', 'exist', 'targetRelation' => 'region'],
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
            'region_id' => 'Region',
            'no' => 'No',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\ProvinceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ProvinceQuery(get_called_class());
    }

    public function getHouseholds()
    {
        return $this->hasMany(Household::className(), ['province_id' => 'id']);
    }

    public function setTheNo($counter=1)
    {
        if ($this->isNewRecord) {
            $address = App::setting('address');

            $count = self::find()
                ->where(['region_id' => $address->region_id])
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
        $model = Province::findOne([
            'region_id' => $this->region_id,
            'name' => $this->name,
        ]);

        if (! $this->isNewRecord) {
            $model = Province::find()
                ->where([
                    'region_id' => $this->region_id,
                    'name' => $this->name,
                ])
                ->andWhere(['<>', 'id', $this->id])
                ->one();
        }

        if ($model) {
            $this->addError($attribute, 'Name exist with the same region.');
        }
    }
    
    public function validateNo($attribute, $params)
    {
        $model = Province::findOne([
            'region_id' => $this->region_id,
            'no' => $this->no,
        ]);

        if (! $this->isNewRecord) {
            $model = Province::find()
                ->where([
                    'region_id' => $this->region_id,
                    'no' => $this->no,
                ])
                ->andWhere(['<>', 'id', $this->id])
                ->one();
        }

        if ($model) {
            $this->addError($attribute, 'No exist with the same region.');
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
            'no' => ['attribute' => 'no', 'format' => 'numPad2'],
        ];
    }

    public function detailColumns()
    {
        return [
            'name:raw',
            'regionName:numPad2',
            'no:numPad2',
        ];
    }

    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'region_id']);
    }

    public function getRegionName()
    {
        if (($region = $this->region) != null) {
            return $region->name;
        }
    }

    public static function findByKeywords($keywords='', $attributes, $limit=10, $andFilterWhere=[])
    {
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit, $andFilterWhere) {
            return self::find()
                ->select("{$attribute} AS data")
                ->alias('p')
                ->joinWith('region r')
                ->groupBy('data')
                ->where(['LIKE', $attribute, $keywords])
                ->andFilterWhere($andFilterWhere)
                ->limit($limit)
                ->asArray()
                ->all();
        });
    }

    public static function getCalabarzonId()
    {
        $model = self::findOne(['no' => self::CALABARZON]);

        return ($model)? $model->id: 0;
    }
}