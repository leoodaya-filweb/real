<?php

namespace app\widgets;

use app\helpers\App;

class FilterColumn extends BaseWidget
{
    public $searchModel;
    public $title = 'Filter Columns';
    public $buttonTitle = 'Filter';
    public $filterColumns;
    public $customTblname=false;
    public $searchModelOnly = false;
    public $style = 'float: right;margin-right: -8px;';

    public function init() 
    {
        // your logic here
        parent::init();

        $this->filterColumns = App::identity()->filterColumns($this->searchModel, true, $this->customTblname );
    }


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('filter-column', [
            'searchModel' => $this->searchModel,
            'title' => $this->title,
            'buttonTitle' => $this->buttonTitle,
            'id' => $this->id,
            'filterColumns' => $this->filterColumns,
            'searchModelOnly' => $this->searchModelOnly,
            'style' => $this->style,
            'customTblname'=>$this->customTblname
        ]);
    }
}
