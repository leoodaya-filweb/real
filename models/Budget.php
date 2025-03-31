<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Event;
use app\models\SocialPension;
use app\models\Transaction;
use app\models\search\BudgetSearch;
use app\widgets\Anchor;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%budgets}}".
 *
 * @property int $id
 * @property string $year
 * @property int $type
 * @property float|null $budget
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Budget extends ActiveRecord
{
    // ACTION
    const ADD = 1;
    const SUBTRACT = 2;

    // SPECIFIC
    const INITIAL = 0;
    const TRANSACTION = 1;
    const EVENT = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%budgets}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'budget',
            'mainAttribute' => 'id',
            'paramName' => 'id',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['year', 'budget', 'type'], 'required'],
            [['type', 'year', 'specific_to', 'model_id', 'action'], 'integer'],
            ['type', 'default', 'value' => Transaction::EMERGENCY_WELFARE_PROGRAM],
            [['budget'], 'number'],
            ['type', 'in', 'range' => array_keys(App::keyMapParams('transaction_types'))],
            ['specific_to', 'in', 'range' => array_keys(App::keyMapParams('budget_specific_to'))],
            ['action', 'in', 'range' => array_keys(App::keyMapParams('budget_actions'))],
            ['year', 'validateYear'],
            ['remarks', 'safe'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'year' => 'Year',
            'type' => 'Type',
            'budget' => 'Budget',
        ]);
    }

    public function setToCurrentYear()
    {
        $this->year = $this->currentYear;
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

        if ($this->action == self::INITIAL) {
            if ($this->isNewRecord) {
                $existInitial = self::initial();

                if ($existInitial) {
                    $this->addError($attribute, 'Already have an initial budget for this year.');
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\BudgetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\BudgetQuery(get_called_class());
    }

    public function getBeforeCanUpdate()
    {
        if ($this->specific_to == self::TRANSACTION) {
            return false;
        }

        return parent::getBeforeCanUpdate();
    }

    public function getBeforeCanDelete()
    {
        if ($this->action == self::INITIAL) {
            return false;
        }

        if ($this->specific_to == self::TRANSACTION) {
            return false;
        }

        return true;

        return parent::getBeforeCanDelete();
    }

    public function getGridColumns()
    {
        $columns = parent::getGridColumns();

        unset($columns['active'], $columns['last_updated'], $columns['checkbox']);

        $columns['action'] = [
            'attribute' => 'action', 
            'format' => 'raw', 
            'value' => function($model) {
                switch ($model->action) {
                    case self::INITIAL:
                        $updateClass = 'btn-update-initial-budget';
                        $deleteClass = 'btn-delete-initial-budget';
                        $url = Url::to(['budget/update', 'id' => $model->id]);
                        break;

                    case self::ADD:
                        $updateClass = 'btn-update-additional-budget';
                        $deleteClass = 'btn-delete-additional-budget';
                        $url = Url::to(['budget/update', 'id' => $model->id, 'action' => Budget::ADD]);
                        break;

                    case self::SUBTRACT:
                        $updateClass = 'btn-update-disbursed-budget';
                        $deleteClass = 'btn-delete-disbursed-budget';
                        $url = Url::to(['budget/update', 'id' => $model->id, 'action' => Budget::SUBTRACT]);
                        break;
                    
                    default:
                        $updateClass = 'btn-update-budget';
                        $deleteClass = 'btn-delete-budget';
                        $url = Url::to(['budget/update', 'id' => $model->id]);
                        break;
                }

                $update = $model->beforeCanUpdate ? Html::button('<i class="fa fa-edit"></i>', [
                    'class' => "btn btn-light-primary btn-sm btn-icon {$updateClass}",
                    'data-url' => $url,
                ]): '';

                $delete = $model->beforeCanDelete ? Html::a('<i class="fa fa-trash"></i>', ['budget/delete', 'id' => $model->id], [
                    'class' => "btn btn-light-danger btn-sm btn-icon {$deleteClass}",
                    'data-confirm' => 'Delete Budget?',
                    'data-method' => 'post'
                ]): '';

                return <<< HTML
                    <div class="btn-group">
                        {$update}
                        {$delete}
                    </div>
                HTML;
            },
            'headerOptions' => ['style' => 'text-align: center'],
            'contentOptions' => ['style' => 'text-align: center'],
        ];

        return $columns;
    }
     
    public function gridColumns()
    {
        return [
            'year' => [
                'attribute' => 'year', 
                'format' => 'raw',
                // 'value' => function($model) {
                //     return Anchor::widget([
                //         'title' => $model->year,
                //         'link' => $model->viewUrl,
                //         'text' => true
                //     ]);
                // }
            ],
            // 'type' => ['attribute' => 'type', 'format' => 'raw'],
            'details' => [
                'label' => 'Details',
                'attribute' => 'budget', 
                'format' => 'raw', 
                'value' => 'budgetDetails'
            ],
            'budget' => [
                'attribute' => 'budget', 
                'format' => 'number',
                'headerOptions' => ['style' => 'text-align: right'],
                'contentOptions' => ['style' => 'text-align: right'],
            ],
            'created_by' => [
                'label' => 'responsible',
                'attribute' => 'createdByEmail', 
                'format' => 'raw',
                'value' => function($model) {
                    return implode('<br>', [
                        'C: ' . $model->createdByEmail,
                        'U: ' . $model->updatedByEmail,
                    ]);
                }
            ],
            'type' => [
                'attribute' => 'action', 
                'format' => 'raw', 
                'label' => 'type',
                'value' => 'actionBadge',
                'headerOptions' => ['style' => 'text-align: center'],
                'contentOptions' => ['style' => 'text-align: center'],
            ],
        ];
    }

    public function getTransaction()
    {
        return $this->hasOne(Transaction::className(), ['id' => 'model_id']);
    }

    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'model_id']);
    }

    public function getModel()
    {
        switch ($this->specific_to) {
            case self::TRANSACTION:
                $model = Transaction::findOne($this->model_id);

                if ($model->transaction_type == Transaction::SOCIAL_PENSION) {
                    $model = new SocialPension($model->attributes);
                }
                break;

            case self::EVENT:
                $model = Event::findOne($this->model_id);
                break;
            
            default:
                $model = $this;
                break;
        }

        return $model;
    }

    public function getBudgetDetails()
    {
        if ($this->remarks) {
            return $this->remarks;
        }
        
        $model = $this->getModel();

        if ($this->action == self::ADD) {
            return 'Added Budget';
        }

        if ($this->specific_to == self::INITIAL) {
            return 'Initial Budget';
        }

        if ($this->specific_to == self::TRANSACTION) {
            return implode(' ', [
                "<b>Transaction:</b> {$model->getTransactionTag('<br>')}",
                Html::a('View', $model->viewUrl, [
                    'class' => 'font-weight-bolder',
                    'target' => '_blank'
                ])
            ]);
        }

        if ($this->specific_to == self::EVENT) {
            return implode(' ', [
                "<b>Event:</b>",
                $model->name,
                "<br>",
                $model->categoryBadge,
                Html::a('View', $model->viewUrl, [
                    'class' => 'font-weight-bolder',
                    'target' => '_blank'
                ])
            ]);
        }
    }

    public function detailColumns()
    {
        return [
            'year:raw',
            'details' => [
                'label' => 'Details',
                'attribute' => 'budget', 
                'format' => 'raw', 
                'value' => function($model) {
                    return $model->budgetDetails;
                }
            ],
            'budget' => [
                'attribute' => 'budget', 
                'format' => 'number',
            ],
            'created_by' => [
                'label' => 'responsible',
                'attribute' => 'createdByEmail', 
                'format' => 'raw',
                'value' => function($model) {
                    return implode('<br>', [
                        'C: ' . $model->createdByEmail,
                        'U: ' . $model->updatedByEmail,
                    ]);
                }
            ],
            'type' => [
                'attribute' => 'action', 
                'format' => 'raw', 
                'label' => 'type',
                'value' => function($model) {
                    return $model->actionBadge;
                }
            ],
        ];
    }

    public function getBudgetAmount()
    {
        return App::formatter('asNumber', $this->budget);
    }

    public function getTotalAmount($formatted = false)
    {
        $data = self::find()
            ->select(['SUM(budget) as total'])
            ->where([
                'action' => [self::ADD, self::INITIAL],
                'year' => $this->year
            ])
            ->asArray()
            ->one();

        $total = (int) $data['total'] ?? 0;

        return $formatted ? App::formatter('asNumber', $total): $total;
    }

    public function getTotalDisbursed($formatted = false)
    {
        $data = self::find()
            ->select(['SUM(budget) as total'])
            ->where([
                'action' => self::SUBTRACT,
                'year' => $this->year
            ])
            ->asArray()
            ->one();

        $total = (int) $data['total'] ?? 0;

        return $formatted ? App::formatter('asNumber', $total): $total;
    }

    public function getTotalUsable($formatted = false)
    {
        $total = $this->totalAmount - $this->totalDisbursed;

        return $formatted ? App::formatter('asNumber', $total): $total;
    }


    public static function dropdown($key='id', $value='year', $condition=[], $map=true, $limit=false)
    {
        $models = self::find()
            ->andFilterWhere($condition)
            ->orderBy([$value => SORT_DESC])
            ->limit($limit)
            ->all();

        $models = ($map)? ArrayHelper::map($models, $key, $value): $models;

        return $models;
    }


    public function getActionBadge()
    {
        $action = App::params('budget_actions')[$this->action];

        return Html::tag('label', $action['label'], [
            'class' => 'badge badge-' . $action['class']
        ]);
    }

    public function getFulldate()
    {
        return App::formatter('asFulldate', $this->created_at);
    }

    public function subtract($amount)
    {
        $this->year = $this->currentYear;
        $this->action = Budget::SUBTRACT;
        $this->budget = $amount;
        $this->type = $this->type ?: Transaction::EMERGENCY_WELFARE_PROGRAM;
        $this->save();
    }

    public static function initial($year='')
    {
        $currentYear = date('Y', strtotime(App::formatter()->asDateToTimezone()));

        $year = $year ?: $currentYear;

        return self::findOne([
            'action' => self::INITIAL,
            'year' => $year
        ]);
    }

    public function getSearchModel($params = [])
    {
        return new BudgetSearch();
    }

    public function getDataProvider($searchModel='', $params = [], $currentYear=true)
    {
        $searchModel = $searchModel ?: $this->searchModel;
        $params = App::get() ?: $params;

        if ($currentYear) {
            $searchModel->setToCurrentYear();
        }

        return $searchModel->search(['BudgetSearch' => $params]);
    }

    public function getCreateAdditionalUrl($fullpath=true)
    {
        if ($this->checkLinkAccess('create')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'create']),
                'action' => Budget::ADD
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }

    public function getCreateDisburseUrl($fullpath=true)
    {
        if ($this->checkLinkAccess('create')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'create']),
                'action' => Budget::SUBTRACT
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }
}