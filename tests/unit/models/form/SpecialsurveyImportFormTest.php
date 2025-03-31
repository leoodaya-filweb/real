<?php

namespace tests\unit\models\form;

use app\helpers\App;
use app\models\Specialsurvey;
use app\models\form\SpecialsurveyImportForm;

class SpecialsurveyImportFormTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'developer'
        ]));
    }
    
    protected function data($replace=[])
    {
        return array_replace([
            'file_token' => 'survey-OxFBeC2Dzw1624513904-survey',
        ], $replace);
    }

    public function testParse()
    {
        $model = new SpecialsurveyImportForm($this->data());

        $data = $model->getData();
        expect(count($data))->equals(1);
        expect(count($data[1]))->equals(9);
    }

    public function testSave()
    {
        $model = new SpecialsurveyImportForm($this->data());

        expect_that($model->save());

        $this->tester->seeRecord('app\models\Specialsurvey', [
            'survey_name' => 'Survey 4',
            'last_name' => "DELA CRUZ",
            'first_name' => "MARY GRACE",
            'middle_name' => "AVENION",
            'household_no' => "760585",
            'gender' => "Female",
            'age' => "28",
            'date_of_birth' => "1993-09-15",
            'civil_status' => "Married",
            'house_no' => "10001",
            'sitio' => "1",
            'purok' => "4",
            'barangay' => "Bagong Silang",
            'municipality' => "REAL",
            'province' => "QUEZON",
            'religion' => "Catholic",
            'criteria1_color_id' => 1,
            'criteria2_color_id' => 4,
            'criteria3_color_id' => 4,
            'criteria4_color_id' => 3,
            'criteria5_color_id' => 3,
            'date_survey' => "2022-01-21",
            'remarks' => '',
        ]);

        $this->tester->seeRecord('app\models\Specialsurvey', [
            'survey_name' => 'Survey 3',
            'last_name' => "LOPEZ",
            'first_name' => "ROBERTO",
            'middle_name' => "ALBUERTO",
        ]);
        $this->tester->seeRecord('app\models\Specialsurvey', [
            'survey_name' => 'Survey 3',
            'last_name' => "HENRIKSSON",
            'first_name' => "MARY ANGEL",
            'middle_name' => "BUENSALIDO",
        ]);

        expect(Specialsurvey::find()->count())->equals(8);
    }


    public function testInvalidExtension()
    {
        $model = new SpecialsurveyImportForm([
            'file_token' => 'default-6ccb4a66-0ca3-46c7-88dd-default',
            'scenario' => 'contentValidation'
        ]);
        expect_not($model->save());
        expect($model->errors)->hasKey('file_token');
    }

    public function testInvalidContentFormat()
    {
        $model = new SpecialsurveyImportForm([
            'file_token' => 'invalid-survey-OxFBeC2Dzw1624513904-invalid-survey',
            'scenario' => 'contentValidation'
        ]);
        expect_not($model->save());
        expect($model->errors)->hasKey('file_token');
    }
}