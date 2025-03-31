<?php

namespace app\models\form;

use Yii;
use app\models\File;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class SpreadsheetReaderForm extends \yii\base\Model
{
    const ALLOWED_EXTENSIONS = ['csv', 'xlsx', 'xls'];
   
    public $file;

    protected $_data;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'required'],
        ];
    }

    public function beforeValidate()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        return parent::beforeValidate();
    }


    public function getData()
    {
        if ($this->_data == null) {
            $file = $this->file;

            $reader = ReaderEntityFactory::createReaderFromFile($file->rootPath);
            $reader->open($file->rootPath);

            $data = [];
            $header = [];

            foreach ($reader->getSheetIterator() as $sheetKey => $sheet) {
                foreach ($sheet->getRowIterator() as $rowKey => $row) {
                    // do stuff with the row

                    $data[$sheetKey - 1]['title'] = $sheet->getName();
                    $data[$sheetKey - 1]['row'][$rowKey - 1] = $row->toArray();
                }
            }

            $reader->close();

            $this->_data = $data;
        }

        return $this->_data;
    }
}