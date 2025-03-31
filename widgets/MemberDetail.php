<?php

namespace app\widgets;

class MemberDetail extends BaseWidget
{
    public $model;
    public $withTransactionBtn;
    public $withViewBtn;
    public $template = 'default';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render("member-detail/{$this->template}", [
            'model' => $this->model,
            'household' => $this->model->household,
            'withTransactionBtn' => $this->withTransactionBtn,
            'withViewBtn' => $this->withViewBtn,
        ]);
    }
}
