<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\widgets\Anchor;

/**
 * This is the model class for table "{{%barangay_coordinates}}".
 *
 * @property int $id
 * @property string|null $country
 * @property string|null $province
 * @property string|null $municipality
 * @property string|null $barangay
 * @property string|null $coordinates
 * @property string|null $color
 */
class BarangayCoordinates extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%barangay_coordinates}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'barangay-coordinates',
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
            [['coordinates'], 'string'],
            [['country', 'province', 'municipality', 'barangay', 'color'], 'string', 'max' => 32],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'country' => 'Country',
            'province' => 'Province',
            'municipality' => 'Municipality',
            'barangay' => 'Barangay',
            'coordinates' => 'Coordinates',
            'color' => 'Color',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\BarangayCoordinatesQuery the active query used by this AR class.
     */
   // public static function find()
   // {
       // return new \app\models\query\BarangayCoordinatesQuery(get_called_class());
   // }
     
    public function gridColumns()
    {
        return [
            'country' => [
                'attribute' => 'country', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->country,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'province' => ['attribute' => 'province', 'format' => 'raw'],
            'municipality' => ['attribute' => 'municipality', 'format' => 'raw'],
            'barangay' => ['attribute' => 'barangay', 'format' => 'raw'],
            'coordinates' => ['attribute' => 'coordinates', 'format' => 'raw'],
            'color' => ['attribute' => 'color', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'country:raw',
            'province:raw',
            'municipality:raw',
            'barangay:raw',
            'coordinates:raw',
            'color:raw',
        ];
    }
}