<?php

namespace app\models\form\export;

use Yii;
use PhpOffice\PhpSpreadsheet\Writer\Csv as CsvWriter;
use PhpOffice\PhpSpreadsheet\Reader\Html as HtmlReader;
use app\helpers\App;

class ExportCsvForm extends ExportForm
{
    public $ini_set = false;

    public function init()
    {
        parent::init();

        if ($this->ini_set) {
            ini_set("pcre.backtrack_limit", "5000000");
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '-1');
        }
        
        $this->filename = implode('-', [App::controllerID(), 'export-csv', time()]) . '.csv';
    }

    public function export()
    {
        if ($this->validate()) {

            $reader = new HtmlReader();
            $internalErrors = libxml_use_internal_errors(true);
            $spreadsheet = $reader->loadFromString($this->content);
            libxml_use_internal_errors($internalErrors);
            $writer = new CsvWriter($spreadsheet);
            header('Content-Type: application/csv');
            header("Content-Disposition: attachment; filename={$this->filename}");

            if (App::isWeb()) {
                $writer->save("php://output");
                exit(0);
            }

            echo 'csv-exported';
            return 'csv-exported';
        }

        return false;
    }
}