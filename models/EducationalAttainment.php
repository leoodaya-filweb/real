<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class EducationalAttainment extends ValueLabel
{
    const VAR = 'educal';

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
        return new \app\models\query\EducationalAttainmentQuery(get_called_class());
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