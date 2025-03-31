<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\models\Country;
use app\widgets\Anchor;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%regions}}".
 *
 * @property int $id
 * @property string $name
 * @property int|null $country_id
 * @property int|null $no
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Region extends ActiveRecord
{
    const REGION_4A = 4;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%regions}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'region',
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
            [['name', 'country_id', 'no'], 'required'],
            [['country_id', 'no'], 'integer'],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
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
            'country_id' => 'Country',
            'no' => 'No',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\RegionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\RegionQuery(get_called_class());
    }

    public function getHouseholds()
    {
        return $this->hasMany(Household::className(), ['region_id' => 'id']);
    }

    public function setTheNo($counter=1)
    {
        if ($this->isNewRecord) {
            $count = self::find()
                ->where(['country_id' => Country::PHILIPPINES_ID])
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
        $model = Region::findOne([
            'country_id' => $this->country_id,
            'name' => $this->name,
        ]);

        if (! $this->isNewRecord) {
            $model = Region::find()
                ->where([
                    'country_id' => $this->country_id,
                    'name' => $this->name,
                ])
                ->andWhere(['<>', 'id', $this->id])
                ->one();
        }

        if ($model) {
            $this->addError($attribute, 'Name exist with the same country.');
        }
    }
    
    public function validateNo($attribute, $params)
    {
        $model = Region::findOne([
            'country_id' => $this->country_id,
            'no' => $this->no,
        ]);

        if (! $this->isNewRecord) {
            $model = Region::find()
                ->where([
                    'country_id' => $this->country_id,
                    'no' => $this->no,
                ])
                ->andWhere(['<>', 'id', $this->id])
                ->one();
        }

        if ($model) {
            $this->addError($attribute, 'No exist with the same country.');
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
            'country_name' => ['attribute' => 'countryName', 'format' => 'raw'],
            'no' => ['attribute' => 'no', 'format' => 'numPad2'],
        ];
    }

    public function detailColumns()
    {
        return [
            'name:raw',
            'countryName:numPad3',
            'no:numPad2',
        ];
    }

    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    public function getCountryName()
    {
        if (($country = $this->country) != null) {
            return $country->name;
        }
    }

    public static function findByKeywords($keywords='', $attributes, $limit=10, $andFilterWhere=[])
    {
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit, $andFilterWhere) {
            return self::find()
                ->select("{$attribute} AS data")
                ->alias('r')
                ->joinWith('country c')
                ->groupBy('data')
                ->where(['LIKE', $attribute, $keywords])
                ->andFilterWhere($andFilterWhere)
                ->limit($limit)
                ->asArray()
                ->all();
        });
    }

    public static function getRegion4aId()
    {
        $model = self::findOne(['no' => self::REGION_4A]);

        return ($model)? $model->id: 0;
    }
}