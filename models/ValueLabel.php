<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\widgets\Anchor;

/**
 * This is the model class for table "{{%value_labels}}".
 *
 * @property int $id
 * @property string $var
 * @property int|null $value
 * @property int|null $elementID
 * @property string|null $label
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class ValueLabel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%value_labels}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'value-label',
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
            [['var'], 'required'],
            [['value', 'elementID'], 'integer'],
            [['label'], 'string'],
            [['var'], 'string', 'max' => 255],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'var' => 'Var',
            'value' => 'Value',
            'elementID' => 'Element ID',
            'label' => 'Label',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\ValueLabelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ValueLabelQuery(get_called_class());
    }
     
    public function gridColumns()
    {
        return [
            'var' => [
                'attribute' => 'var', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->var,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'value' => ['attribute' => 'value', 'format' => 'raw'],
            'elementID' => ['attribute' => 'elementID', 'format' => 'raw'],
            'label' => ['attribute' => 'label', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'var:raw',
            'value:raw',
            'elementID:raw',
            'label:raw',
        ];
    }
}