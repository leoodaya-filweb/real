<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\widgets\Anchor;
use app\widgets\Label;

/**
 * This is the model class for table "{{%tech_issue_logs}}".
 *
 * @property int $id
 * @property int $tech_issue_id
 * @property int $status
 * @property string|null $remarks
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class TechIssueLog extends ActiveRecord
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['creatorImage'] = 'creatorImage';
        $fields['createdByEmail'] = 'createdByEmail';
        $fields['ago'] = 'ago';
        $fields['statusLabel'] = 'statusLabel';
        $fields['filePreviews'] = 'filePreviews';
        $fields['timeSent'] = 'timeSent';

        return $fields;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tech_issue_logs}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'tech-issue-log',
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
            [['tech_issue_id', 'status'], 'integer'],
            [['status'], 'required'],
            [['remarks'], 'string'],
            [['attachments'], 'safe']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'tech_issue_id' => 'Tech Issue ID',
            'status' => 'Status',
            'remarks' => 'Remarks',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\TechIssueLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\TechIssueLogQuery(get_called_class());
    }
     
    public function gridColumns()
    {
        return [
            'tech_issue_id' => [
                'attribute' => 'tech_issue_id', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->tech_issue_id,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'remarks' => ['attribute' => 'remarks', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'tech_issue_id:raw',
            'remarks:raw',
        ];
    }

    public function getCreatorImage()
    {
        return App::if($this->createdBy, fn ($createdBy) => Url::image($createdBy->photo ?: '', ['w' => 50]));
    }

    public function getStatusLabel()
    {
        return Label::widget([
            'options' => App::params('tech_issue_status')[$this->status]
        ]);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['JsonBehavior']['fields'] = [
            'attachments', 
        ];

        return $behaviors;
    }

    public function getFiles()
    {
        return File::findAll(['token' => $this->attachments]);
    }

    public function getFilePreviews()
    {
        return App::foreach($this->files, fn ($file) => Html::a($file->show([
            'class' => "img-thumbnail pointer symbol",
            'loading' => 'lazy',
        ], 50), $file->viewerUrl, ['target' => '_blank']));
    }

    public static function createOne($techIssue)
    {
        $log = new self([
            'tech_issue_id' => $techIssue->id,
            'remarks' => $techIssue->remarks ?: 'New issue submitted',
            'status' => $techIssue->status,
            'attachments' => $techIssue->attachments,
        ]);
        $log->save();
    }
}