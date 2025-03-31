<?php

namespace app\models;

use Yii;
use app\helpers\App;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

class SocialPensionEvent extends Event
{
	public function config()
    {
    	$config = parent::config();
        $config['controllerID'] = 'social-pension-event';

        return $config;
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['no_of_pensioner', 'integer', 'min' => 1];
        $rules[] = ['social_pension_fund', 'integer'];
        $rules[] = ['social_pension_fund', 'in', 
            'range' => array_keys(App::keyMapParams('social_pension_funds'))
        ];

        return $rules;
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['amount'] = 'Pension Amount Per Person';

        return $labels;
    }

    public function init()
    {
        parent::init();
        $this->category_type = parent::SOCIAL_PENSION_CATEGORY;
        $this->scenario = parent::SCENARIO_SOCIAL_PENSION;
        $this->social_pension_fund = $this->social_pension_fund ?: parent::NATIONAL_FUND;
    }

    public function detailColumns()
    {
        return [
            'name:raw',
            'description:raw',
            'amount:number',
            'date_from:raw',
            'date_to:raw',
            'totalBeneficiaryMember:raw',
            'noOfPensioners',
            'fundLabel'
        ];
    }

    public function getGridColumns()
    {
        $columns = parent::getGridColumns();
        $status = $columns['status'];

        unset(
            $columns['status'],
            $columns['category'],
            $columns['type'],
        );

        $columns['no_of_pensioner'] = [
            'attribute' => 'no_of_pensioner',
            'format' => 'raw'
        ];

        $columns['social_pension_fund'] = [
            'attribute' => 'social_pension_fund',
            'format' => 'raw',
            'value' => 'fundLabel'
        ];

        $columns['status'] = $status;

        return $columns;
    }

    public function getNoOfPensioners()
    {
        return App::formatter('asNumber', $this->no_of_pensioner);
    }
    

    public static function find()
    {
        return new \app\models\query\SocialPensionEventQuery(get_called_class());
    }

    public static function filter($key='id', $condition=[], $limit=false, $andFilterWhere=[])
    {
        $models = self::find()
            ->andWhere(['category_type' => parent::SOCIAL_PENSION_CATEGORY])
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

    public function getStepForm()
    {
        $form = parent::STEP_FORM;

        unset($form[3]);

        return $form;
    }

    public function populatePensioners()
    {
        ini_set('max_execution_time', 0); //0=NOLIMIT
        ini_set('memory_limit', '-1');

        $models = Masterlist::find()
            ->limit($this->no_of_pensioner)
            ->all();

        $data = [];
        if ($models) {
            foreach ($models as $model) {
                $em = EventMember::find()
                    ->where([
                        'event_id' => $this->id, 
                        'social_pensioner_id' => $model->id
                    ])
                    ->exists();

                if (! $em) {
                    $data[] = [
                        $this->id, 
                        $model->id, 
                        $model->fullname, 
                        $model->qr_id, 
                        $model->is_solo_parent,
                        $model->is_solo_member,
                        $model->genderName,
                        $model->civilStatusName,
                        $model->educationalAttainmentLabel,
                        ($model->is_pwd ? 1: 2),
                        $model->barangay,
                        $model->purok,
                        $model->age,
                        EventMember::UNCLAIM, 
                        $model->priorityScore,
                        ActiveRecord::RECORD_ACTIVE,
                        App::identity('id'),
                        App::identity('id'),
                        new Expression('UTC_TIMESTAMP'), 
                        new Expression('UTC_TIMESTAMP'),
                        $model->pwd_score,
                        $model->senior_score,
                        $model->solo_parent_score,
                        $model->solo_member_score,
                        $model->accessibility_score,
                    ];
                }
            }

            if ($data) {
                $arr = array_chunk($data, 1000);
                $tableName = EventMember::tableName();

                foreach ($arr as $r) {
                    App::createCommand()
                        ->batchInsert(
                            $tableName, 
                            [
                                'event_id', 
                                'social_pensioner_id', 
                                'name',
                                'qr_id',
                                'solo_parent',
                                'solo_member',
                                'gender',
                                'civil_status',
                                'educational_attainment',
                                'pwd',
                                'barangay',
                                'purok_no',
                                'age',
                                'status', 
                                'priority_score',
                                'record_status', 
                                'created_by', 
                                'updated_by', 
                                'created_at', 
                                'updated_at',
                                'pwd_score',
                                'senior_score',
                                'solo_parent_score',
                                'solo_member_score',
                                'accessibility_score',
                            ], 
                            $r
                        )
                        ->execute();
                }
            }
        }
    }

    public function addSocialPensioner($masterlist)
    {
        $model = new EventMember([
            'event_id' => $this->id, 
            'social_pensioner_id' => $masterlist->id,
            'name' => $masterlist->fullname,
            'qr_id' => $masterlist->qr_id,
            'solo_parent' => $masterlist->is_solo_parent ? 1: 2,
            'solo_member' => $masterlist->is_solo_member ? 1: 2,
            'gender' => $masterlist->genderName,
            'civil_status' => $masterlist->civilStatusName,
            'educational_attainment' => $masterlist->educationalAttainmentLabel,
            'pwd' => $masterlist->is_pwd ? 1: 2,
            'barangay' => $masterlist->barangay,
            'purok_no' => $masterlist->purok,
            'age' => $masterlist->age,
            'status' => EventMember::UNCLAIM,
            'priority_score' => $masterlist->priorityScore,
            'pwd_score' => $masterlist->pwd_score,
            'senior_score' => $masterlist->senior_score,
            'solo_parent_score' => $masterlist->solo_parent_score,
            'solo_member_score' => $masterlist->solo_member_score,
            'accessibility_score' => $masterlist->accessibility_score,
        ]);


        if ($model->save()) {
            return $model;
        }
        else {
            $this->addError('social_pensioner', $model->errorSummary);
        }
    }
}