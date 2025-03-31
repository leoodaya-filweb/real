<?php

namespace app\models\form;

use Yii;
use app\helpers\App;
use app\models\Budget;
use app\models\Event;
use app\models\Transaction;
use app\widgets\Anchor;

class DisbursedBudgetForm extends \yii\base\Model
{
    public $year;
    public $budget;
    public $remarks;

    public $specific_to;
    public $model_id;

    public $budget_id;

    public $_budget;

    public function rules()
    {
        return [
            [['year', 'budget', 'specific_to', 'model_id'], 'required'],
            [['budget'], 'number'],
            [['year', 'specific_to', 'model_id'], 'integer'],
            [['remarks'], 'safe'],
            ['year', 'validateYear'],
            ['budget', 'validateBudget'],
            ['budget_id', 'required', 'on' => 'update'],
            ['budget_id', 'integer'],
            ['budget_id', 'exist', 
                'targetClass' => 'app\models\Budget', 
                'targetAttribute' => 'id',
                'on' => 'update'
            ],
        ];
    }

    public function getBudget()
    {
        if ($this->_budget == null) {
            $this->_budget = Budget::findOne($this->budget_id);
        }

        return $this->_budget;
    }

    public function init()
    {
        parent::init();

        if (($budget = $this->getBudget()) != null) {
            $this->year = $budget->year;
            $this->remarks = $budget->remarks;
            $this->budget = $budget->budget;
            $this->specific_to = $budget->specific_to;
            $this->model_id = $budget->model_id;
        }
        else {
            $this->year = $this->year ?: $this->currentYear;
            $this->specific_to = $this->specific_to ?: Budget::EVENT;
        }
    }

    public function getCurrentYear()
    {
        $currentYear = date('Y', strtotime(App::formatter()->asDateToTimezone()));
        
        return $currentYear;
    }

    public function validateBudget($attribute, $params)
    {
        $budget = new Budget();
        $budget->setToCurrentYear();

        if ($budget->totalUsable < $this->budget) {
            $this->addError($attribute, 'Disbursed budget is greater than usable budget as of this year.');
        }
    }

    public function validateYear($attribute, $params)
    {
        $currentYear = $this->currentYear;

        if ($this->year > $currentYear) {
            $this->addError($attribute, 'Year is greater than current year.');
        }
    }

    public function save()
    {
        if ($this->validate()) {
            $budget = $this->getBudget() ?: new Budget([
                'type' => Transaction::EMERGENCY_WELFARE_PROGRAM,
                'action' => Budget::SUBTRACT,
            ]);
            $budget->model_id = $this->model_id;
            $budget->specific_to = $this->specific_to;
            $budget->year = $this->year;
            $budget->budget = $this->budget;
            $budget->remarks = $this->remarks;

            if ($budget->save()) {
                return $budget;
            }
            else {
                $this->addError('budget', $budget->errors);
            }
        }
    }

    public function refresh()
    {
        // dummy function for ajax creation
        // do not delete this
    }

    public function getEventDetailView()
    {
        if (($model = Event::findOne($this->model_id)) != null) {

            return implode('', [
                Anchor::widget([
                    'title' => 'View Event',
                    'link' => $model->viewUrl,
                    'text' => true,
                    'options' => [
                        'target' => '_blank',
                        'class' => 'btn btn-light-primary btn-sm font-weight-bolder'
                    ]
                ]),
                $model->detailView
            ]);
        }
    }
}