<?php

namespace app\widgets;

use app\helpers\Html;

class DatabaseReport extends BaseWidget
{
    public $template = 'per-barangay';
    public $model;
    public $models;
    public $rowsummary;
    public $priority_sector;
    public $content;
    public $default = 0;


    public function init()
    {
        parent::init();


        if ($this->template == 'per-barangay') {
            $th = '';
            $tbody = '';

           
            $footer_total_active = '';
            $footer_total_male_active = '';
            $footer_total_female_active = '';
            foreach ($this->models as $model) {
                $th = '';
                $active = '';
                $male = '';
                $female = '';
                $footer_total_active = '';
                $footer_total_male_active = '';
                $footer_total_female_active = '';

                // $total_active = Html::number($this->rowsummary['active_total']);
                // $total_male = Html::number($this->rowsummary['active_male_total']);
                // $total_female = Html::number($this->rowsummary['active_female_total']);

                foreach ($this->priority_sector as $row) {
                    $th .= Html::tag('th', $row['code'], ['class' => 'text-right']);
                    $active .= Html::tag('td', Html::number($model["{$row['id']}_active"]) ?: $this->default, ['class' => 'text-right']) ;

                    $male .=  Html::tag('td',  Html::number($model["{$row['id']}_active_male"]) ?: $this->default, ['class' => 'text-right']) ;

                    $female .=  Html::tag('td',  Html::number($model["{$row['id']}_active_female"]) ?: $this->default, ['class' => 'text-right']);


                    $footer_total_active .= Html::tag('td', Html::number($this->rowsummary["{$row['id']}_active_total"]) ?: $this->default, ['class' => 'text-right']);

                    $footer_total_male_active .= Html::tag('td',  Html::number($this->rowsummary["{$row['id']}_active_male_total"]) ?: $this->default);

                    $footer_total_female_active .= Html::tag('td', Html::number($this->rowsummary["{$row['id']}_active_female_total"]) ?: $this->default);
                }

                $total_active = Html::number($model['active']) ?: $this->default;
                $total_active_male = Html::number($model['active_male']) ?: $this->default;
                $total_active_female = Html::number($model['active_female']) ?: $this->default;

                $tbody .= <<< HTML
                    <tr>
                        <td rowspan="3"> {$model['barangay']} </td>
                        <td>Male</td>
                        {$male}
                    </tr>

                    <tr>
                        <td>Female</td>
                        {$female}
                    </tr>

                    <tr>
                        <td>Total</td>
                        {$active}
                    </tr>
                HTML;
            }

            $summary_active = Html::number($this->rowsummary['active_total']) ?: $this->default;
            $summary_male_active = Html::number($this->rowsummary['active_male_total']) ?: $this->default;
            $summary_female_active = Html::number($this->rowsummary['active_female_total']) ?: $this->default;

            $this->content = <<< HTML
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2">
                                TOTAL
                            </th>
                            {$footer_total_active}
                        </tr>
                        <tr>
                            <th colspan="2">BARANGAY</th>
                            {$th}
                        </tr>
                    </thead>
                    <tbody>
                        {$tbody}
                    </tbody>
                </table>
            HTML;
        }
    }
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->content ?: $this->render("database-report/{$this->template}", [
            'model' => $this->model
        ]);
    }
}
