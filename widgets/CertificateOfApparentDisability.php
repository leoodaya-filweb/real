<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;

class CertificateOfApparentDisability extends BaseWidget
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

        $member = $this->model;

        $replace = [
            '[PROCESSING_OFFICER]'  => strtoupper(App::identity('fullname')),
            '[FULLNAME]'  => ucwords(strtolower($member->fullname)),
            '[BARANGAY_NAME]' => $member->barangayName,
            '[DATE]' => date('F d, Y', strtotime($currentDate)),
        ];

        $this->content = str_replace(
            array_keys($replace), 
            array_values($replace), 
            $template->certificate_of_apparent_disability
        );
    }


    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('certificate-of-apparent-disability', [
            'content' => $this->content,
            'model' => $this->model,
        ]);
    }
}
