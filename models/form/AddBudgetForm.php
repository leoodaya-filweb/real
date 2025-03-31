<?php

namespace app\models\form;

use Yii;
use app\helpers\App;
use app\models\Budget;
use app\models\Transaction;

class AddBudgetForm extends \yii\base\Model
{
    public $year;
    public $budget;
    public $remarks;

    public $budget_id;

    public $_budget;
    public $specific_to;

    public function rules()
    {
        return [
            [['year', 'budget'], 'required'],
            [['budget'], 'number'],
            [['year'], 'integer'],
            [['remarks','specific_to'], 'safe'],
            ['year', 'validateYear'],
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
        }
        else {
            $this->year = $this->year ?: $this->currentYear;
        }
    }

    public function getCurrentYear()
    {
        $currentYear = date('Y', strtotime(App::formatter()->asDateToTimezone()));
        
        return $currentYear;
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
                'action' => Budget::ADD
            ]);
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
}