<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\models\query\EventCategoryQuery;

class EventCategory extends Setting
{
    const TYPE_DEFAULT = 0;
    const TYPE_DISASTER = 1;
    const TYPE_SOCIAL_PENSION = 2;

    const TYPE = 'event-category';

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['name', 'validateUnique'];
        $rules[] = ['sort_order', 'required'];
        $rules[] = ['sort_order', 'in', 'range' => array_keys(App::keyMapParams('event_category_types'))];

        return $rules;
    }

    public function validateUnique($attribute, $params)
    {
        $exist = false;

        if ($this->isNewRecord) {
            $exist = self::find()->where(['name' => $this->name])->exists();
        }
        else {
            $exist = self::find()
                ->where(['name' => $this->name])
                ->andWhere(['<>', 'id', $this->id])
                ->exists();
        }

        if ($exist) {
            $this->addError($attribute, 'Name already exist');
        }
    }

    public function config()
    {
        $config = parent::config();

        $config['controllerID'] = 'event-category';

        return $config;
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['value'] = 'Photo';
        $attributeLabels['sort_order'] = 'Type';

        return $attributeLabels;
    }
    public function init()
    {
        parent::init();
        $this->type = self::TYPE;
    }

    public function getTypeBagde()
    {
        $type = App::params('event_category_types')[$this->sort_order] ?? '';

        if ($type) {
            return Html::tag('label', $type['label'], [
                'class' => 'badge badge-' . $type['class']
            ]);
        }
    }

    public function gridColumns()
    {
        $columns = parent::gridColumns();

        $columns['value'] = [
            'attribute' => 'value', 
            'format' => 'raw',
            'value' => function($model) {
                return Html::image($model->value, ['w' => 50, 'quality' => 90], [
                    'class' => 'img-thumbnail'
                ]);
            }
        ];

        $columns['type'] = [
            'attribute' => 'sort_order', 
            'format' => 'raw',
            'value' => 'typeBagde'
        ];

        return $columns;
    }

    // public function getTableColumns()
    // {
    //     $columns = parent::getTableColumns();
    //     unset($columns['type']);

    //     $columns['type'] = [
    //         'attribute' => 'sort_order', 
    //         'format' => 'raw',
    //         'value' => 'typeBagde'
    //     ];

    //     return $columns;
    // }

    public function getDetailColumns()
    {
        $columns = parent::getDetailColumns();
        // unset($columns['type'], $columns['value']);

        $columns['type'] = [
            'attribute' => 'sort_order', 
            'format' => 'raw',
            'value' => function($model) {
                return $model->typeBagde;
            }
        ];

        $columns['value'] = [
            'attribute' => 'value', 
            'label' => 'Photo',
            'format' => 'raw',
            'value' => function($model) {
                return Html::image($model->value, ['w' => 200], [
                    'class' => 'img-thumbnail'
                ]);
            }
        ];
        return $columns;
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\EventCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventCategoryQuery(get_called_class());
    }

    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['category_id' => 'id']);
    }

    public function getCanDelete()
    {
        if ($this->events) {
            return false;
        }

        return true;
    }

    // public static function dropdown($key='id', $value='name', $condition=[], $map=true, $limit=false)
    // {
    //     $condition['type'] = self::TYPE;
    //     return parent::dropdown($key, $value, $condition, $map, $limit);
    // }
}
