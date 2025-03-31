<?php

namespace app\widgets;

class EventSummary extends BaseWidget
{
    public $event;
    public $withBeneficiaries = false;
    public $template = 'index';

    public function init() 
    {
        // your logic here
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render("event-summary/{$this->template}", [
            'event' => $this->event,
            'withBeneficiaries' => $this->withBeneficiaries,
        ]);
    }
}
