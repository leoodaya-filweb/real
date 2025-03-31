<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\widgets\Anchor;

/**
 * This is the model class for table "{{%emails}}".
 *
 * @property int $id
 * @property string $to
 * @property string $from_email
 * @property string|null $from_name
 * @property string $subject
 * @property string|null $body
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Email extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%emails}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'email',
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
            [['to', 'from_email', 'subject',], 'required'],
            [['to', 'from_email'], 'email'],
            [['to', 'from_email'], 'trim'],
            [['body'], 'string'],
            [['to', 'from_email', 'from_name', 'subject'], 'string', 'max' => 255],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'to' => 'To',
            'from_email' => 'From Email',
            'from_name' => 'From Name',
            'subject' => 'Subject',
            'body' => 'Body',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\EmailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\EmailQuery(get_called_class());
    }
     
    public function gridColumns()
    {
        return [
            'to' => [
                'attribute' => 'to', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->to,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'from_email' => ['attribute' => 'from_email', 'format' => 'raw'],
            'from_name' => ['attribute' => 'from_name', 'format' => 'raw'],
            'subject' => ['attribute' => 'subject', 'format' => 'raw'],
            'body' => ['attribute' => 'body', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'to:raw',
            'from_email:raw',
            'from_name:raw',
            'subject:raw',
            'body:raw',
        ];
    }
}