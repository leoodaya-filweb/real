<?php

namespace app\widgets;

use app\helpers\App;
use app\widgets\DatabaseReport;

class PyapForm extends BaseWidget
{
    public $model;
    public $content;
    public $contentOnly = false;

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
            '[AGE]' => $database->age,
            '[ADDRESS]' => $database->address,
            '[RELIGION]' => $database->religion ?: 'None',
            '[BIRTH_DATE_PLACE]' => implode(' | ', [
                $database->birth_place,
                $database->date_of_birth,
            ]),
            '[FATHERS_NAME]' => $database->fathers_name,
            '[MOTHERS_NAME]' => $database->mothers_name,
            '[SIBLINGS]' => DatabaseReport::widget([
                'model' => $database,
                'template' => 'siblings-print'
            ]),

            '[EDUCATIONAL_ATTAINMENT_SCHOOL]' => $database->school_name_last_attended ?: 'None',
            '[YEAR]' => $database->school_year_last_attended ?: 'None',
            '[EDUCATIONAL_ATTAINMENT]' => $database->educ_attainment ?: 'None',

            '[SKILLS]' => App::formatter('asImplode', $database->skills),
            '[INTEREST]' => App::formatter('asImplode', $database->interests),
            '[WORK_EXPERIENCE]' => DatabaseReport::widget([
                'model' => $database,
                'template' => 'work-experience-print'
            ]),

            '[ORGANIZATION]' => DatabaseReport::widget([
                'model' => $database,
                'template' => 'organizations-print'
            ]),

            '[ORGANIZATION_NAME]' => $database->organization_name,
            '[POSITION]' => $database->position,
        ];

        $this->content = str_replace(
            array_keys($replace), 
            array_values($replace), 
            $template->pyap_form
        );
    }


    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('pyap-form', [
            'content' => $this->content,
            'model' => $this->model,
        ]);
    }
}
