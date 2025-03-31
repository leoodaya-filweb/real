<?php

namespace app\widgets;

use yii\widgets\DetailView;
 
class Detail extends BaseWidget
{
    public $model;
    public $formatter = ['class' => 'app\components\FormatterComponent'];
    public $options = [
        'class' => 'detail-view table table-active table-bordered table-striped'
    ];

    public $attributes;

    public function init() 
    {
        // your logic here
        parent::init(); 
        
        if ($this->attributes == null) {
            $this->attributes = $this->model->detailColumns ?? ['id'];
        }
    }
  
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return DetailView::widget([
            'options' => $this->options,
            'model' => $this->model,
            'attributes' => $this->attributes,
            'formatter' => $this->formatter,
        ]);
    }
}
