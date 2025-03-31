<?php

namespace app\widgets;

use Yii;
use app\helpers\App;
use app\models\search\TransactionSearch;
use yii\helpers\Inflector;
 
class ApexChart extends BaseWidget
{
    public $data;
    public $date_range;
    public $title = 'Transaction Statistics';
    public $model;
    public $template = 'transaction';
    public $height = 500;

    public function init() 
    {
        // your logic here
        parent::init();

        $this->model = new TransactionSearch();

        $this->date_range = $this->date_range ?: implode(' - ', [
            $this->model->startDate,
            $this->model->endDate,
        ]);

        if (!$this->data) {
            $this->data = json_encode(TransactionSearch::chartData($this->date_range));
        }
    }
 
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        list($start, $end) = explode(' - ', $this->date_range);
        $start = date('F d, Y', strtotime($start));
        $end = date('F d, Y', strtotime($end));

        return $this->render("apex-chart/{$this->template}", [
            'model' => $this->model,
            'date_range' => $this->date_range,
            'start' => $start,
            'end' => $end,
            'data' => $this->data,
            'id' => $this->id,
            'title' => $this->title,
            'height' => $this->height,
        ]);
    }
}
