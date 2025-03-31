<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\widgets\Anchor;

/**
 * This is the model class for table "{{%transaction_logs}}".
 *
 * @property int $id
 * @property int $transaction_id
 * @property int $status
 * @property string|null $remarks
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class TransactionLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%transaction_logs}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'transaction-log',
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
            [['transaction_id', 'status'], 'required'],
            [['transaction_id', 'status'], 'integer'],
            ['transaction_id', 'exist', 'targetRelation' => 'transaction'],
            [['remarks'], 'string'],
            [
                'status', 'in', 
                'range' => array_keys(App::keyMapParams('transaction_status'))
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'transaction_id' => 'Transaction ID',
            'status' => 'Status',
            'remarks' => 'Remarks',
            'transactionStatusLabel' => 'Status'
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\TransactionLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\TransactionLogQuery(get_called_class());
    }
     
    public function gridColumns()
    {
        return [
            'transaction_id' => [
                'attribute' => 'transaction_id', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->transaction_id,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'status' => [
                'attribute' => 'status', 
                'value' => 'label', 
                'format' => 'raw'
            ],
            'remarks' => ['attribute' => 'remarks', 'format' => 'raw'],
        ];
    }

    public function getTransactionStatus()
    {
        return App::params('transaction_status')[$this->status];
    }

    public function getTransactionStatusLabel()
    {
        return $this->transactionStatus['label'];
    }

    public function detailColumns()
    {
        return [
            'transaction_id:raw',
            'remarks:raw',
            'label:raw',
        ];
    }

    public function getTransaction()
    {
        return $this->hasOne(Transaction::className(), ['id' => 'transaction_id']);
    }

    public function getCreatorImage()
    {
        if (($createdBy = $this->createdBy) != null) {
            return Url::image($createdBy->photo ?: '', ['w' => 50]);
        }
    }

    public function getAgo()
    {
        return App::formatter('asAgo', $this->created_at);
    }

    public function getLabel()
    {
        return Html::tag('span', $this->transactionStatus['label'], [
            'class' => 'label font-weight-bolder label-inline ml-2 label-light-' . $this->transactionStatus['class']
        ]);
    }
}