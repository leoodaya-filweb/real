<?php

namespace app\widgets;

use app\helpers\App;
use app\widgets\DatabaseReport;

class SoloParentApplicationForm extends BaseWidget
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
        
        $incase_emergency = $database->incase_emergency; //json_decode($database->incase_emergency,true);
        

        $replace = [
            '[NAME]'  => ucwords(strtolower($database->fullname)),
            '[SEX]' => ucwords(strtolower($database->gender)),
            '[BIRTHDATE]' => $database->date_of_birth,
            '[PLACE_OF_BIRTH]' => $database->birth_place,
            '[CIVIL_STATUS]' => $database->civil_status,
            '[EDUCATIONAL_ATTAINMENT]' => $database->educ_attainment ?: 'None',
            '[OCCUPATION]' => $database->occupation ?: 'None',
            '[MONTHLY_INCOME]' => $database->monthly_income,
            '[OTHER_SOURCE_OF_INCOME]' => $database->other_source_income ?: 'None',
            '[PRESENT_ADDRESS]' => $database->address ?: 'None',
            '[SP_ID_NUMBER]' => $database->sector_id,
            '[DATE_REGISTERED]' => $database->date_registered,
            '[CONTACT_NO]' => $database->contact_no ?: 'None',
            '[RELIGION]' =>$database->religion ?: '',
            '[AGE]' => $database->age ?: '',
            '[COMPANY]' => '', //$database->company ?: '',
            '[EMAIL]' => $database->email ?: '',
            '[REMARKS]' => $database->remarks ?: '',
            '[IN_NAME]' => $incase_emergency['name'] ?: '',
            '[IN_RELATION]' => $incase_emergency['relationship'] ?: '',
            '[IN_CONTACT]' => $incase_emergency['contact_number'] ?: '',
            '[IN_ADDRESS]' => $incase_emergency['address'] ?: '',
            '[BENEFIT_CODE]' => App::formatter('asImplode', $database->benefit_code), //$database->benefit_code ?: '',
            '[APPROVE]' => '<span style="font-size: 13pt; height:25px; text-align: center; display: inline-block; width: 40px; border: 1px solid #000; vertical-align: middle;">'.($database->application_status==2?'&#x2713;':null).'</span>',
            '[NEW]' => '<span style="font-size: 13pt; height:25px; text-align: center; display: inline-block; width: 40px; border: 1px solid #000; vertical-align: middle;">'.($database->application_status==1?'&#x2713;':null).'</span>',
            '[RENEW]' => '<span style="font-size: 13pt; height:25px; text-align: center; display: inline-block; width: 40px; border: 1px solid #000; vertical-align: middle;">'.($database->application_status==3?'&#x2713;':null).'</span>',
            '[DISAPPROVE]' => '<span style="font-size: 13pt; height:25px; text-align: center; display: inline-block; width: 40px; border: 1px solid #000; vertical-align: middle;">'.($database->application_status==4?'&#x2713;':null).'</span>',
            
            
            '[FAMILY_COMPOSITION]' => DatabaseReport::widget([
                'model' => $database,
                'template' => 'family-composition-print'
            ]),

            '[CLIENT_CATEGORY]' => App::formatter('asImplode', $database->client_category),
            '[REASON]' => App::formatter('asImplode', $database->reasons),
            '[INTERVIEWER]' => App::identity('fullname'),
            '[DATE]' => App::formatter()->asDateToTimezone('', 'm/d/Y'),
        ];

        $this->content = str_replace(
            array_keys($replace), 
            array_values($replace), 
            $template->solo_parent_application_form
        );
    }


    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('solo-parent-application-form', [
            'content' => $this->content,
            'model' => $this->model,
        ]);
    }
}
