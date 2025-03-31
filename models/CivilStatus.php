<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class CivilStatus extends ValueLabel
{
    const VAR = 'civstat';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['ValueLabelSubclassBehavior'] = [
            'class' => 'app\behaviors\ValueLabelSubclassBehavior'
        ];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\BuzzWordQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\CivilStatusQuery(get_called_class());
    }

    public static function single()
    {
        $model = self::findOne([
            'label' => 'Single',
        ]);

        return $model ? $model->value: 0;
    }

    public static function married()
    {
        $model = self::findOne([
            'label' => 'Married',
        ]);

        return $model ? $model->value: 0;
    }

    public static function widow()
    {
        $model = self::findOne([
            'label' => 'Widow/er',
        ]);

        return $model ? $model->value: 0;
    }

    public static function dropdown($key='value', $value='label', $condition=[], $map=true, $limit=false)
    {
        $models = self::find()
            ->andFilterWhere($condition)
            ->orderBy([$key => SORT_ASC])
            ->limit($limit)
            ->all();

        $models = ($map)? ArrayHelper::map($models, $key, $value): $models;

        return $models;
    }
}