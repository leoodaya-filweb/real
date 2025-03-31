<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class UnPlannedAttendeesEvent extends Event
{
    const STEP_FORM = [
        1 => [
            'id' => 1,
            'slug' => 'general-information',
            'title' => 'General Information',
            'description' => 'Primary Event Details',
        ],
        2 => [
            'id' => 2,
            'slug' => 'documents',
            'title' => 'Documents | Photos',
            'description' => 'Document & Photos',
        ],
        3 => [
            'id' => 3,
            'slug' => 'summary',
            'title' => 'Summary',
            'description' => 'Event Summary',
        ],
    ];

    public function gridColumns()
    {
        $columns = parent::gridColumns();
        $columns['beneficiaries']['label'] = 'Attendees';

        return $columns;
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['totalBeneficiaryMember'] = 'Total Attended';

        return $labels;
    }

	public function config()
    {
    	$config = parent::config();
        $config['controllerID'] = 'un-planned-attendees-event';

        return $config;
    }
    
    public function init()
    {
        parent::init();
        $this->category_type = parent::UN_PLANNED_CATEGORY;
        $this->scenario = parent::SCENARIO_UNPLANNED;
    }


    public static function find()
    {
        return new \app\models\query\UnPlannedAttendeesEventQuery(get_called_class());
    }


    public static function filter($key='id', $condition=[], $limit=false, $andFilterWhere=[])
    {
        $models = self::find()
            ->andWhere(['category_type' => parent::UN_PLANNED_CATEGORY])
            ->andFilterWhere($condition)
            ->andFilterWhere($andFilterWhere)
            ->orderBy([$key => SORT_ASC])
            ->limit($limit)
            ->groupBy($key)
            ->asArray()
            ->all();

        $models = ArrayHelper::map($models, $key, $key);

        return $models;
    }
}