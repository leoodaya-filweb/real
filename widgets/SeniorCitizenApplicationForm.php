<?php

namespace app\widgets;

use app\helpers\App;
use app\widgets\DatabaseReport;

class SeniorCitizenApplicationForm extends BaseWidget
{
    public $model;
    public $content;
    public $contentOnly = false;

    public function getPensionerCheckbox()
    {
        if ($this->model->isPensioner) {
            return <<< HTML
                <label> <input type="checkbox" checked> Yes </label>
                <label> <input type="checkbox"> No </label>
            HTML;
        }
        else {
            return <<< HTML
                <label> <input type="checkbox"> Yes </label>
                <label> <input type="checkbox" checked> No </label>
            HTML;
        }
    }


    public function getPensionerWhere()
    {
        if (strtoupper($this->model->relation_where) == 'PVAO') {
            return <<< HTML
                <label> <input type="checkbox" checked> PVAO </label>
                <label> <input type="checkbox"> GSIS </label>
                <label> <input type="checkbox"> SSS </label>
            HTML;
        }
        elseif (strtoupper($this->model->relation_where) == 'GSIS') {
            return <<< HTML
                <label> <input type="checkbox"> PVAO </label>
                <label> <input type="checkbox" checked> GSIS </label>
                <label> <input type="checkbox"> SSS </label>
            HTML;
        }
        elseif (strtoupper($this->model->relation_where) == 'SSS') {
            return <<< HTML
                <label> <input type="checkbox"> PVAO </label>
                <label> <input type="checkbox"> GSIS </label>
                <label> <input type="checkbox" checked> SSS </label>
            HTML;
        }
        else {
            return <<< HTML
                <label> <input type="checkbox"> PVAO </label>
                <label> <input type="checkbox"> GSIS </label>
                <label> <input type="checkbox"> SSS </label>
            HTML;
        }
    }

    public function getOtherSpecify()
    {
        if (strtoupper($this->model->relation_where) != 'PVAO'
            && strtoupper($this->model->relation_where) != 'GSIS'
            && strtoupper($this->model->relation_where) != 'SSS') {
            return $this->model->relation_where;
        }
    }

    public function getSexCheckbox()
    {
        if ($this->model->isMale) {
            return <<< HTML
                <label> <input type="checkbox" checked> Male </label>
                <label> <input type="checkbox"> Female </label>
            HTML;
        }
        else {
            return <<< HTML
                <label> <input type="checkbox"> Male </label>
                <label> <input type="checkbox" checked> Female </label>
            HTML;
        }
    }

    public function init()
    {
        parent::init();
        $template = App::setting('reportTemplate');
        $template->setData();

        $currentDate = App::formatter()->asDateToTimezone();

        $database = $this->model;

        $replace = [
            '[NAME]'  => ucwords(strtolower($database->fullname)),
            '[SEX]' => ucwords(strtolower($database->gender)),
            '[BIRTHDATE_AGE]' => $database->date_of_birth . " ({$database->age})",
            '[PLACE_OF_BIRTH]' => $database->birth_place,
            '[CIVIL_STATUS]' => $database->civil_status,
            '[EDUCATIONAL_ATTAINMENT]' => $database->educ_attainment ?: 'None',
            '[OCCUPATION]' => $database->occupation ?: 'None',
            '[MONTHLY_INCOME]' => $database->monthly_income,
            '[OTHER_SOURCE_OF_INCOME]' => $database->other_source_income ?: 'None',
            '[PRESENT_ADDRESS]' => $database->address ?: 'None',
            '[SC_ID_NUMBER]' => $database->sector_id,
            '[DATE_REGISTERED]' => $database->date_registered,
            '[CONTACT_NO]' => $database->contact_no ?: 'None',
            '[FAMILY_COMPOSITION]' => DatabaseReport::widget([
                'model' => $database,
                'template' => 'family-composition-print'
            ]),
            '[PENSIONER]' => $this->pensionerCheckbox,
            '[PENSIONER_WHERE]' => $this->pensionerWhere,
            '[OTHER_SPECIFY]' => $this->otherSpecify,
            '[PENSION_AMOUNT]' => $database->amount_of_pension,
            '[LIVING_WITH_WHOM]' => $database->living_with_whom ?: 'None',
            '[RELATIONSHIP]' => $database->relation ?: 'None',
            '[RELATION_OCCUPATION]' => $database->relation_occupation ?: 'None',
            '[RELATION_INCOME]' => $database->relation_income,
            '[PREPARED_BY]' => App::identity('fullname'),
            '[DATE]' => App::formatter()->asDateToTimezone('', 'm/d/Y'),
            '[BARANGAY_NAME]' => $database->barangay,
            '[ID_NUMBER]' => $database->sector_id,
            '[DAY]' => App::formatter()->asDateToTimezone('', 'jS'),
            '[MONTH]' => App::formatter()->asDateToTimezone('', 'F'),
            '[YEAR]' => App::formatter()->asDateToTimezone('', 'Y'),

        ];

        $this->content = str_replace(
            array_keys($replace), 
            array_values($replace), 
            $template->senior_citizen_application_form
        );
    }


    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('senior-citizen-application-form', [
            'content' => $this->content,
            'model' => $this->model,
        ]);
    }
}
