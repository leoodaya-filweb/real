<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\widgets\Anchor;
use app\widgets\Label;

/**
 * This is the model class for table "{{%allowances}}".
 *
 * @property int $id
 * @property string $semester
 * @property float $amount
 * @property int $status
 * @property string|null $documents
 * @property string|null $remarks
 * @property string|null $token
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Allowance extends ActiveRecord
{
    const PENDING = 0;
    const RECEIVED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%allowances}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'allowance',
            'mainAttribute' => 'semester',
            'paramName' => 'token',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['semester', 'amount', 'scholarship_id', 'date'], 'required'],
            [['amount'], 'number'],
            [['status', 'scholarship_id'], 'integer'],
            [['remarks'], 'string'],
            [['semester'], 'string', 'max' => 255],
            ['status', 'in', 'range' => [
                self::PENDING,
                self::RECEIVED,
            ]],
            ['scholarship_id', 'exist', 'targetRelation' => 'scholarship'],
            ['date', 'validateDate'],
            [['documents'], 'safe'],
        ]);
    }

    public function validateDate($attribute, $params)
    {
        $current_date = strtotime(App::formatter()->asDateToTimezone('', 'Y-m-d'));
        $date = strtotime($this->date);

        if ($date > $current_date) {
            $this->addError('date', 'Date is greater than current date');
        }
    }

    public function getScholarship()
    {
        return $this->hasOne(Scholarship::class, ['id' => 'scholarship_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'semester' => 'Semester',
            'amount' => 'Amount',
            'status' => 'Status',
            'documents' => 'Documents',
            'documentPreviews' => 'Documents',
            'remarks' => 'Remarks',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\AllowanceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\AllowanceQuery(get_called_class());
    }
     
    public function gridColumns()
    {
        return [
            'semester' => [
                'attribute' => 'semester', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->semester,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'amount' => ['attribute' => 'amount', 'format' => 'raw'],
            'documents' => ['attribute' => 'documents', 'format' => 'raw'],
            'remarks' => ['attribute' => 'remarks', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'date:raw',
            'semester:raw',
            'amount:raw',
            'remarks:raw',
            'documentPreviews:raw',
        ];
    }

    public function getDocumentPreviews()
    {
        return App::foreach(
            File::findAll(['token' => $this->documents]), 
            fn ($file) => Html::tag('a', Html::image($file->token, ['w' => 100], ['class' => 'symbol img-fluid']), [
                'href' => $file->viewerUrl,
                'target' => '_blank'
            ])
        );
    }

    public function getStatusBadge()
    {
        return Label::widget([
            'options' => App::params('allowance_status')[$this->status] ?? []
        ]);
    }

    public function getFormattedAmount()
    {
        return App::formatter()->asPeso($this->amount);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['DateBehavior'] = [
            'class' => 'app\behaviors\DateBehavior',
            'attributes' => [
                'date',
            ]
        ];
        $behaviors['JsonBehavior'] = [
            'class' => 'app\behaviors\JsonBehavior',
            'fields' => [
                'documents',
            ]
        ];

        return $behaviors;
    }
}