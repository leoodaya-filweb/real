<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;

class CertificateOfIndigency extends BaseWidget
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
            '[AGE]' => $this->model->currentAge,
            '[HIS/HER]' => $this->model->isMale ? 'His': 'Her',
            '[HE/SHE]' => $this->model->isMale ? 'He': 'She',
            '[he/she]' => $this->model->isMale ? 'He': 'She',
            '[OCCUPATION]' => ucwords(strtolower($this->model->occupation)) ?: '[OCCUPATION]',
            '[DAY]' => date('jS', strtotime($currentDate)),
            '[MONTH]' => date('F', strtotime($currentDate)),
            '[YEAR]' => date('Y', strtotime($currentDate)),
            '[BARANGAY_NAME]' => $this->model->barangayName,
        ];

        $this->content = str_replace(array_keys($replace), array_values($replace), $template->certificate_of_indigency);
    }


    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('certificate-of-indigency', [
            'content' => $this->content,
            'model' => $this->model,
        ]);
    }
}
