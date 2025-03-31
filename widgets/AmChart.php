<?php

namespace app\widgets;

use app\helpers\App;
use app\models\Transaction;

 
class AmChart extends BaseWidget
{
    public $view = 'member-transaction';
    public $height = 500;

    public $data;

    const MONTHS = [
        '01' => 'January',
        '02' => 'February',
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'June',
        '07' => 'July',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ];
    public $year;

    public function init()
    {
        parent::init();
        $this->year = $this->year ?: App::formatter()->asDateToTimezone('', 'Y');

        $data = [];

        foreach (self::MONTHS as $no => $label) {
            $ym = "{$this->year}-{$no}";

            $data[] = Transaction::find()
                ->select([
                    'created_at AS date',
                    'CAST(COUNT("*") AS UNSIGNED) as value',
                ])
                ->where(['(DATE_FORMAT(created_at, "%Y-%m"))' => $ym])
                ->orderBy(['created_at' => SORT_ASC])
                ->asArray()
                ->one();
        }

        foreach ($data as $key => &$d) {
            $d['date'] = (int)strtotime($d['date']) * 100000;
            $d['value'] = (int)$d['value'];
        }
        $this->data = json_encode($data);

    }

    public function run()
    {
        return $this->render("am-chart/{$this->view}", [
            'height' => $this->height,
            'data' => $this->data,
        ]);
    }
}
