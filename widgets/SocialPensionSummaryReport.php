<?php

namespace app\widgets;

use app\helpers\App;
use app\models\Sex;
use app\models\SocialPension;

class SocialPensionSummaryReport extends BaseWidget
{
    public $date_range;
    public $data;
    public $total_male = 0;
    public $total_female = 0;
    public $total_record = 0;
    public $default = 0;

    public function init()
    {
        parent::init();

        $models = SocialPension::find()
            ->alias('t')
            ->joinWith('member m')
            ->select(['t.status', 'COUNT("t.*") as total', 'm.sex'])
            ->social_pension()
            ->groupBy(['t.status', 'm.sex'])
            // ->orderBy(['t.status' => SORT_ASC])
            ->daterange($this->date_range)
            ->asArray()
            ->all();

        $data = [];
        $genders = Sex::dropdown('value', 'label');

        foreach ($models as $model) {
            $sn = $genders[$model['sex']];
            $tn = App::params('transaction_status')[$model['status']]['label'];
            $data[$tn][$sn] = $model['total'];
            $data[$tn]['total'] = ((int)($data[$tn]['Male'] ?? 0) + (int)($data[$tn]['Female'] ?? 0));

            if ($model['sex'] == '1') {
                $this->total_male += (int)$model['total'];
            }
            elseif ($model['sex'] == '2') {
                $this->total_female += (int)$model['total'];
            }
        }
        uasort($data, function($a, $b) {
            return -1* ($a['total'] <=> $b['total']);
        });

        $this->data = $data;

        $this->total_record = $this->total_male + $this->total_female;
    }

    public function run()
    {
        return $this->render('social-pension-summary-report', [
            'data' => $this->data,
            'date_range' => $this->date_range,
            'total_male' => $this->total_male,
            'total_female' => $this->total_female,
            'total_record' => $this->total_record,
            'start' => App::formatter()->asDaterangeToSingle($this->date_range, 'start', 'F d, Y'),
            'end' => App::formatter()->asDaterangeToSingle($this->date_range, 'end', 'F d, Y'),
            'default' => $this->default
        ]);
    }
}
