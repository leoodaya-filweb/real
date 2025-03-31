<?php

namespace app\widgets;

use app\helpers\App;

class ReportTemplate extends BaseWidget
{
    public $template = 'header';
    public $image;
    public $address;
    public $personnel;

    public $content;

    public function init()
    {
        parent::init();
    }

    public function getDafacAssistancePlaceholder()
    {
        $data = [];
        for ($i=1; $i <= 38 ; $i++) { 
            foreach (['D', 'N', 'K', 'Q', 'C', 'P'] as $letter) {
                $data[$i][] = implode('', [$letter, $i]);
            }
        }
        return $data;
    }

    public function getDafacFamilyMemberPlaceholder()
    {
        $data = [];
        for ($i=1; $i <= 5 ; $i++) { 
            foreach (['M', 'MR', 'MA', 'MG', 'MC', 'ME', 'MO', 'MM'] as $letter) {
                $data[$i][] = implode('', [$letter, $i]);
            }
        }
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render("report-template/{$this->template}", [
            'image' => $this->image,
            'address' => $this->address,
            'personnel' => $this->personnel,
            'assistance_placeholder' => $this->dafacAssistancePlaceholder,
            'members_placeholder' => $this->dafacFamilyMemberPlaceholder,
        ]);
    }
}
