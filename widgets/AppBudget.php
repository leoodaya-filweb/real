<?php

namespace app\widgets;

use app\helpers\App;
use app\models\Budget;
use app\models\Transaction;

class AppBudget extends BaseWidget
{
    public $model;

    public $data = [];
    public $year;
    public $template = 'card';
    public $showChart = false;

    public function init() 
    {
        // your logic here
        parent::init();

        $currentYear = date('Y', strtotime(App::formatter()->asDateToTimezone()));
        $this->year = App::get('year') ?: $currentYear;
        $this->model = new Budget(['year' => $this->year]);


        $ewp = App::params('emergency_welfare_programs');
        $type = App::params('transaction_types');

        // $data[$ewp[Transaction::AICS_FINANCIAL]['label']] = $this->ewp(
        //     Transaction::AICS_FINANCIAL
        // );
        $data[$ewp[Transaction::AICS_MEDICAL]['label']] = $this->ewp(
            Transaction::AICS_MEDICAL
        );
        $data[$ewp[Transaction::AICS_LABORATORY_REQUEST]['label']] = $this->ewp(
            Transaction::AICS_LABORATORY_REQUEST
        );
        $data[$ewp[Transaction::BALIK_PROBINSYA_PROGRAM]['label']] = $this->ewp(
            Transaction::BALIK_PROBINSYA_PROGRAM
        );

        $data[$type[Transaction::SOCIAL_PENSION]['label']] = $this->type(
            Transaction::SOCIAL_PENSION
        );

        $data[$type[Transaction::DEATH_ASSISTANCE]['label']] = $this->type(
            Transaction::DEATH_ASSISTANCE
        );

        $data['Event'] = $this->event();
        arsort($data);

        foreach ($data as $d) {
            if ($d > 0) {
                $this->showChart = true;
            }
        }

        $this->data = $data;
    }

    public function event()
    {
        $model = Budget::find()
            ->select(['SUM(budget) AS total'])
            ->where([
                'year' => $this->year,
                'specific_to' => Budget::EVENT,
                'action' => Budget::SUBTRACT
            ])
            ->asArray()
            ->one();

        return (int)$model['total'] ?? 0;
    }

    public function type($type)
    {
        $model = Budget::find()
            ->alias('b')
            ->joinWith('transaction t')
            ->select(['SUM(b.budget) AS total'])
            ->where([
                'b.year' => $this->year,
                'b.specific_to' => Budget::TRANSACTION,
                'b.action' => Budget::SUBTRACT,
                't.transaction_type' => $type,
            ])
            ->asArray()
            ->one();

        return (int)$model['total'] ?? 0;
    }

    public function ewp($ewp)
    {
        $model = Budget::find()
            ->alias('b')
            ->joinWith('transaction t')
            ->select(['SUM(b.budget) AS total'])
            ->where([
                'b.year' => $this->year,
                'b.specific_to' => Budget::TRANSACTION,
                'b.action' => Budget::SUBTRACT,
                't.emergency_welfare_program' => $ewp
            ])
            ->asArray()
            ->one();

        return (int)$model['total'] ?? 0;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('app-budget/index', [
            'model' => $this->model,
            'data' => $this->data,
            'template' => $this->template,
            'showChart' => $this->showChart ? 'true': 'false',
            'series' => json_encode(array_values($this->data)),
            'labels' => json_encode(array_keys($this->data)),
            'totalDisbursed' => $this->model->getTotalDisbursed(true)
        ]);
    }
}
