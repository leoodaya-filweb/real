<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\widgets\Anchor;

/**
 * This is the model class for table "{{%countries}}".
 *
 * @property int $id
 * @property int|null $no
 * @property string|null $name
 * @property string|null $alpha_code
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Country extends ActiveRecord
{
    const PHILIPPINES = 608;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%countries}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'country',
            'mainAttribute' => 'name',
            'paramName' => 'id',
            'relatedModels' => ['regions']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['name', 'alpha_code', 'no'], 'required'],
            [['no'], 'unique'],
            [['no'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['alpha_code'], 'string', 'max' => 8],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'no' => 'No',
            'name' => 'Name',
            'alpha_code' => 'Alpha Code',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\CountryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\CountryQuery(get_called_class());
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
            'no' => ['attribute' => 'no', 'format' => 'numPad3'],
            'alpha_code' => ['attribute' => 'alpha_code', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'no:numPad3',
            'name:raw',
            'alpha_code:raw',
        ];
    }

    public function getRegions()
    {
        return $this->hasMany(Region::className(), ['country_id' => 'id']);
    }

    public static function getPhilippinesId()
    {
        $model = self::findOne(['no' => self::PHILIPPINES]);

        return ($model)? $model->id: 0;
    }
}