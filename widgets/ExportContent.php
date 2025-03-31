<?php

namespace app\widgets;

use app\helpers\App;
 
class ExportContent extends BaseWidget
{
    public $dataProvider;
    public $searchModel;
    public $params;
    public $file = 'excel';
    public $reportName;
    public $header;

    public $exportColumnsName = 'exportColumns';
    public $excelIgnoreAttributesName = 'excelIgnoreAttributes';
    public $tableColumnsName = 'tableColumns';

    public function init() 
    {
        // your logic here
        parent::init();

        ini_set("pcre.backtrack_limit", "5000000");
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        if (!$this->dataProvider) {
            $params = $this->params ?: App::queryParams();
            if (! is_array($params)) {
                $params = json_decode($params);
            }

            $modelName = App::className($this->searchModel);

            $this->params[$modelName] = $params;
     
            $this->reportName = $this->reportName ?: str_replace('Search', '', $modelName);
            $this->dataProvider = $this->searchModel->search($this->params);

            $this->dataProvider->pagination = false;

        }
    }


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render("export-content/{$this->file}", [
            'dataProvider' => $this->dataProvider,
            'searchModel' => $this->searchModel,
            'reportName' => $this->reportName,
            'exportColumnsName' => $this->exportColumnsName,
            'excelIgnoreAttributesName' => $this->excelIgnoreAttributesName,
            'tableColumnsName' => $this->tableColumnsName,
            'header' => $this->header,
        ]);
    }
}
