<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;

class CertificateOfMarriageCounseling extends BaseWidget
{
    public $model;
    public $content;
    public $contentOnly = false;

    public function init()
    {
        parent::init();
        $template = App::setting('reportTemplate');
        $template->setData();

        $identity = App::identity();

        $replace = [
            // '[MR]' => ucwords(strtolower($this->model->fullname)),
            // '[MS]' => ucwords(strtolower($this->model->fullname)),
            '[ISSUED_BY]'  => $identity ? $identity->fullname: '',
            '[POSITION]'  => $identity ? $identity->profile->position: '',
        ];

        if ($this->model->isMale) {
            $replace['[MR]'] = ucwords(strtolower($this->model->fullname));

            if (($spouse = $this->model->spouse) != null) {
                $replace['[MS]'] = ucwords(strtolower($spouse->fullname));
            }
        }
        else {
            $replace['[MS]'] = ucwords(strtolower($this->model->fullname));

            if (($spouse = $this->model->spouse) != null) {
                $replace['[MR]'] = ucwords(strtolower($spouse->fullname));
            }
        }

        $this->content = str_replace(array_keys($replace), array_values($replace), $template->certificate_of_marriage_counseling);
    }


    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('certificate-of-marriage-counseling', [
            'content' => $this->content,
            'model' => $this->model,
        ]);
    }
}
