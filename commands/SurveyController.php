<?php

namespace app\commands;

use Faker\Factory;
use app\models\Member;
use app\models\Specialsurvey;

class SurveyController extends Controller
{
    public function actionRefresh()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        
        $faker = Factory::create();

        $survey_names = [
            'Survey 1',
            'Survey 2',
            'Survey 3',
            'Survey 4',
            'Survey 5',
        ];

        $models = Specialsurvey::find()
            ->where([
                'survey_name' => NULL,
                'household_no' => NULL,
            ])
            ->all();

        if ($models) {
            foreach ($models as $model) {
                $member = Member::findOne([
                    'last_name' => $model->last_name,
                    'middle_name' => $model->middle_name,
                    'first_name' => $model->first_name,
                ]);

                $model->household_no = $member ? $member->householdNo: '';
                $model->survey_name = $faker->randomElement($survey_names);
                $model->date_survey = $faker->date;
                $model->save(false);
            }
        }
    }
}
