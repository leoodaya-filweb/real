<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;
use app\models\Event;

 
class EventStatus extends BaseWidget
{
    public $event;
    public $dropdownMenu = [];
   
    public function init()
    {
        parent::init();
        $options = [
            'class' => 'dropdown-item',
            'data-confirm' => 'Are you sure?',
            'data-method' => 'post'
        ];

        $completed = $this->event->completed;

        switch ($this->event->status) {
            case Event::PENDING:
                $this->dropdownMenu[] = Html::a('Set as Ongoing', [
                    'event/change-status', 
                    'token' => $this->event->token,
                    'status' => Event::ONGOING
                ], $options);

                $this->dropdownMenu[] = Html::a('Set as Cancelled', [
                    'event/change-status', 
                    'token' => $this->event->token,
                    'status' => Event::CANCELLED
                ], $options);
                break;

            case Event::ONGOING:
                if (!$completed) {
                    $this->dropdownMenu[] = Html::a('Set as Pending', [
                        'event/change-status', 
                        'token' => $this->event->token,
                        'status' => Event::PENDING
                    ], $options);
                }

                $this->dropdownMenu[] = Html::a('Set as Cancelled', [
                    'event/change-status', 
                    'token' => $this->event->token,
                    'status' => Event::CANCELLED
                ], $options);

                $this->dropdownMenu[] = Html::a('Set as Completed', [
                    'event/change-status', 
                    'token' => $this->event->token,
                    'status' => Event::COMPLETED
                ], $options);
            default:
                // code...
                break;
        }
    }

    public function run()
    {
        if ($this->event->status == Event::COMPLETED) {
            return Html::tag('label', 'Completed', ['class' => 'badge badge-success']);
        }

        if ($this->event->status == Event::CANCELLED) {
            return Html::tag('label', 'Cancelled', ['class' => 'badge badge-danger']);
        }

        return $this->render('event-status', [
            'event' => $this->event,
            'dropdownMenu' => $this->dropdownMenu,
        ]);
    }
}
