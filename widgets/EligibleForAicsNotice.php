<?php

namespace app\widgets;

use app\helpers\Html;
 
class EligibleForAicsNotice extends BaseWidget
{
    public $model;
    public $template = 'eligible';
    public $transaction_id;

    public $tagOnly = false;

    public function init()
    {
        parent::init();
        if ($this->model->getAssistanceRecentTransactions(6, $this->transaction_id)) {
            $this->template = 'not-eligible';
        }
    }
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->tagOnly == true) {
            return ($this->template == 'not-eligible')? 
                Html::tag('label', 'Not Eligible', ['class' => 'badge badge-warning']): 
                Html::tag('label', 'Eligible', ['class' => 'badge badge-success']);
        }

        return $this->render("eligible-for-aics/{$this->template}", [
            'model' => $this->model,
        ]);
    }
}
