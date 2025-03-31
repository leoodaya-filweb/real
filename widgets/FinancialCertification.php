<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;

class FinancialCertification extends BaseWidget
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

        $replace = [
            '[FULLNAME]'  => ucwords(strtolower($this->model->fullname)),
            '[DAY]' => date('jS', strtotime($currentDate)),
            '[MONTH]' => date('F', strtotime($currentDate)),
            '[YEAR]' => date('Y', strtotime($currentDate)),
        ];

        $this->content = str_replace(array_keys($replace), array_values($replace), $template->financial_certification);
    }


    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('financial-certification', [
            'content' => $this->content,
            'model' => $this->model,
        ]);
    }
}
