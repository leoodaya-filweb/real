<?php

namespace app\models\form\export;

use Yii;
use app\helpers\App;
use kartik\mpdf\Pdf;

class ExportPdfForm extends ExportForm
{
    public $orientation = Pdf::ORIENT_PORTRAIT;
    public $ini_set = false;

    public function init()
    {
        parent::init();

        if ($this->ini_set) {
            ini_set("pcre.backtrack_limit", "5000000");
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '-1');
        }

        $this->filename = implode('-', [App::controllerID(), 'pdf', time()]) . '.pdf';
    }

    public function export()
    {
        if ($this->validate()) {

            $pdf = App::component('pdf');
            $pdf->filename = $this->filename;
            $pdf->content = $this->content;
            $pdf->orientation = $this->orientation;
            $render = $pdf->render();

            if (App::isWeb()) {
                return $render;
            }

            return 'pdf-exported';
        }

        return false;
    }
}