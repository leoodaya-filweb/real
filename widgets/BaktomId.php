<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;
use app\widgets\DatabaseReport;

class BaktomId extends BaseWidget
{
    public $model;
    public $content;
    public $contentOnly = false;
   
    public function init()
    {
        parent::init();
        $template = App::setting('reportTemplate');
        $template->setData();


        $database = $this->model;

        $replace = [
            '[PHOTO]' => Html::image($database->photo, ['w' => 500], [
                'style' => 'width: 100%;height: auto;'
            ]),
            '[SIGNATURE]' => Html::img($database->signature, [
                'style' => 'width: 100%;height: auto;'
            ]),
            '[BIRTH_NAME]' => implode(' ', [
                $database->first_name,
                $database->middle_name,
                $database->last_name,
            ]),
            '[PREFERRED_NAME]' => $database->preferred_name,
            '[BIRTHDATE]' => $database->date_of_birth,
            '[SEX]' => $database->gender,
            '[GENDER]' => $database->sogie,
            '[ADDRESS]' => $database->address,
            '[OCCUPATION]' => $database->occupation,
        ];

        $this->content = str_replace(
            array_keys($replace), 
            array_values($replace), 
            $template->baktom_id
        );
    }


    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('baktom-id', [
            'content' => $this->content,
            'model' => $this->model,
        ]);
    }
}
