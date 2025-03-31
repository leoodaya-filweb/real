<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\form\UserAgentForm;
use app\widgets\Anchor;
use app\widgets\Label;
use yii\helpers\StringHelper;
use yii\db\Expression;

/**
 * This is the model class for table "{{%tech_issues}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $type
 * @property string|null $steps
 * @property string|null $description
 * @property string|null $photos
 * @property int $status
 * @property string $ip
 * @property string $browser
 * @property string $os
 * @property string $device
 * @property string|null $token
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class TechIssue extends ActiveRecord
{
    const TYPE_REPORT_BUG = 0;
    const TYPE_AUDIT_LOGS = 1;

    const PENDING = 0;
    const ONGOING = 1;
    const COMPLETED = 2;
    // const UNSOLVED = 3;

    public $remarks;
    public $attachments;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tech_issues}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'tech-issue',
            'mainAttribute' => 'ticketId',
            'paramName' => 'token',
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['minimumLogId'] = 'minimumLogId';

        return $fields;
    }

    public function getMinimumLogId()
    {
        $min = TechIssueLog::find()
            ->where(['tech_issue_id' => $this->id])
            ->min('id');

        return $min ?: 1;
    }

    public function getTicketId()
    {
        return App::formatter()->AsStrPad($this->id, 7);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['user_id', 'type', 'status'], 'integer'],
            [['description'], 'string'],
            [['user_id', 'type', 'status', 'description', 'steps'], 'required'],
            [['ip'], 'string', 'max' => 32],
            [['photos', 'steps', 'remarks', 'attachments'], 'safe'],
            [['browser', 'os', 'device'], 'string', 'max' => 128],
            ['user_id', 'exist', 'targetRelation' => 'user'],
            ['type', 'in', 'range' => [
                self::TYPE_AUDIT_LOGS,
                self::TYPE_REPORT_BUG,
            ]],
            ['status', 'in', 'range' => [
                self::PENDING,
                self::ONGOING,
                self::COMPLETED,
                // self::UNSOLVED,
            ]],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'steps' => 'How to Reproduce',
            'description' => 'Description',
            'photos' => 'Photos',
            'status' => 'Status',
            'ip' => 'Ip',
            'browser' => 'Browser',
            'os' => 'Os',
            'device' => 'Device',
            'typeLabel' => 'Type',
            'statusLabel' => 'Status',
            'ticketId' => 'Ticket ID'
        ]);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getUserEmail()
    {
        return App::if($this->user, fn ($user) => $user->email);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\TechIssueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\TechIssueQuery(get_called_class());
    }
     
    public function gridColumns()
    {
        return [
            'token' => [
                'attribute' => 'token', 
                'label' => 'Ticket Id',
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->ticketId,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'email' => [
                'attribute' => 'userEmail', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->userEmail,
                        'link' => $model->user->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'type' => [
                'attribute' => 'type', 
                'value' => 'typeLabel',
                'format' => 'raw'
            ],
            // 'steps' => ['attribute' => 'steps', 'format' => 'raw'],
            'description' => ['attribute' => 'description', 'format' => 'raw', 'value' => 'truncatedDescription'],
            // 'photos' => ['attribute' => 'photos', 'format' => 'raw'],
            'ip' => ['attribute' => 'ip', 'format' => 'raw'],
            'browser' => ['attribute' => 'browser', 'format' => 'raw'],
            'os' => ['attribute' => 'os', 'format' => 'raw'],
            'device' => ['attribute' => 'device', 'format' => 'raw'],
            'status' => [
                'attribute' => 'status', 
                'value' => 'statusLabel',
                'format' => 'raw'
            ],
        ];
    }

    public function init()
    {
        parent::init();

        $this->user_id = App::identity('id') ?: 0;
        $this->type = self::TYPE_REPORT_BUG;
        $this->status = self::PENDING;

        $userAgent = new UserAgentForm();
        $this->ip = App::ip();
        $this->browser = $userAgent->browser;
        $this->os = $userAgent->os;
        $this->device = $userAgent->device;
    }

    public function detailColumns()
    {
        return [
            'statusLabel:raw',
            'ticketId:raw',
            [
                'attribute' => 'userEmail', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->userEmail,
                        'link' => $model->user->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'typeLabel:raw',
            'steps:ul',
            'description:raw',
            'images:raw',
            'ip:raw',
            'browser:raw',
            'os:raw',
            'device:raw',
        ];
    }

    public function getStatusLabel()
    {
        return Label::widget([
            'options' => App::params('tech_issue_status')[$this->status]
        ]);
    }

    public function getTypeLabel()
    {
        return Label::widget([
            'options' => App::params('tech_issue_types')[$this->type]
        ]);
    }

    public function getImageFiles()
    {
        return File::findAll(['token' => $this->photos]);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['JsonBehavior']['fields'] = [
            'steps', 
            'photos', 
        ];

        return $behaviors;
    }

    public function getBeforeCanUpdate()
    {
        $status = [
            self::PENDING,
        ];
        
        return in_array($this->status, $status);
    }

    public function getBeforeCanDelete()
    {
        return false;
    }
    public function getCanDelete()
    {
        return false;
    }

    public function getImages()
    {
        return App::foreach($this->imageFiles, fn ($file) => Html::a(Html::image($file, ['w' => 50], [
            'class' => 'img-thumbnail symbol'
        ]), $file->viewerUrl, ['target' => '_blank']));
    }

    public static function findByKeywords($keywords='', $attributes=[], $limit=10, $andFilterWhere=[])
    {
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit, $andFilterWhere) {
            return self::find()
                ->select("{$attribute} AS data")
                ->alias('t')
                ->joinWith('user u')
                ->groupBy('data')
                ->where(['LIKE', $attribute, $keywords])
                ->andFilterWhere($andFilterWhere)
                ->limit($limit)
                ->asArray()
                ->all();
        });
    }

    public function afterSave ($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        TechIssueLog::createOne($this);

        if ($insert) {
            Notification::techIssue($this);
        }
    } 

    public function getTechIssueLogs()
    {
        return $this->hasMany(TechIssueLog::class, ['tech_issue_id' => 'id'])
            ->orderBy(['id' => SORT_DESC]);
    }

    public function getTotalTechIssueLogs()
    {
        return TechIssueLog::find()
            ->where(['tech_issue_id' => $this->id])
            ->count();
    }

    public function getLoadLogs($id=0, $condition=">")
    {
        $models = TechIssueLog::find()
            ->where(['tech_issue_id' => $this->id])
            ->andWhere([$condition, 'id', $id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(20)
            ->all();

        return $models ? array_reverse($models): [];
    }

    public function getNewTechIssueLog()
    {
        return new TechIssueLog([
            'tech_issue_id' => $this->id
        ]);
    }

    public function getTruncatedDescription($limit=70)
    {
        return StringHelper::truncate($this->description, $limit);
    }

    public function getIsOpen()
    {
        return $this->status == self::PENDING;
    }

    public function getCanClosed()
    {
        return $this->status == self::ONGOING;
    }
}