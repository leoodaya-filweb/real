<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\widgets\Anchor;

/**
 * This is the model class for table "{{%medicines}}".
 *
 * @property int $id
 * @property int|null $transaction_id
 * @property string $name
 * @property float|null $price
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Medicine extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%medicines}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'medicine',
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
            [['transaction_id',], 'integer'],
            [['name', 'transaction_id', 'quantity', 'unit'], 'required'],
            [['price'], 'number'],
            [['name', 'unit'], 'string', 'max' => 255],
            ['transaction_id', 'exist', 'targetRelation' => 'transaction'],
            [['quantity'], 'integer', 'min' => 1],
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
            'name' => 'Name',
            'price' => 'Price',
        ]);
    }

    public function getTransaction()
    {
        return $this->hasOne(Transaction::className(), ['id' => 'transaction_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\MedicineQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\MedicineQuery(get_called_class());
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
            'name' => ['attribute' => 'name', 'format' => 'raw'],
            'price' => ['attribute' => 'price', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'transaction_id:raw',
            'name:raw',
            'price:raw',
        ];
    }

    public function getFormattedPrice()
    {
        return Html::number($this->price);
    }
}