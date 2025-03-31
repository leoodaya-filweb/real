<?php

namespace app\modules\chat\models;

use Yii;
use app\modules\chat\helpers\App;
use app\modules\chat\helpers\ArrayHelper;
use app\modules\chat\widgets\Anchor;

/**
 * This is the model class for table "{{%space_message}}".
 *
 * @property int $id
 * @property int $reply_id
 * @property int $space_id
 * @property string|null $content
 * @property string|null $attachments
 * @property int $type
 * @property string $token
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class SpaceMessage extends ActiveRecord
{
    const TYPE_DEFAULT = 0;
    const TYPE_LABEL = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%space_messages}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'space-message',
            'mainAttribute' => 'id',
            'paramName' => 'id',
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        $fields['isTypeLabel'] = 'isTypeLabel';
        $fields['isSender'] = 'isSender';
        $fields['senderName'] = 'senderName';
        $fields['userPhoto'] = 'userPhoto';
        $fields['formattedContent'] = 'formattedContent';
        $fields['ago'] = 'ago';
        $fields['senderPhotoLink'] = 'senderPhotoLink';
        $fields['timeSent'] = 'timeSent';
        $fields['files'] = 'files';

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['content'], 'required', 'when' => function($model) {
                return !$model->attachments;
            }],
            [['reply_id', 'space_id', 'type'], 'integer'],
            [['content'], 'string'],
            ['type', 'default', 'value' => self::TYPE_DEFAULT],
            ['type', 'in', 'range' => [
                self::TYPE_DEFAULT,
                self::TYPE_LABEL,
            ]],
            ['space_id', 'exist', 'targetRelation' => 'space'],
            ['attachments', 'safe'],
            ['content', 'trim'],
            ['space_id', 'validateSpaceId'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'reply_id' => 'Reply ID',
            'space_id' => 'Space ID',
            'content' => 'Content',
            'attachments' => 'Attachments',
            'type' => 'Type',
        ]);
    }

    public function validateSpaceId($attribute, $params)
    {
        if ($this->space->is_block && $this->type != self::TYPE_LABEL) {
            $this->addError($attribute, 'Space conversation was blocked!');
        }
    }

    public function getTimeSent()
    {
        $start = date("Y-m-d", strtotime(App::formatter()->asDateToTimezone('', 'Y-m-d H:i:s'))); 
        $end = date("Y-m-d", strtotime($this->created_at)); 

        $day   = App::component('general')->dateDiff($start, $end);
        $month = App::component('general')->dateDiff($start, $end, 'm');
        $year  = App::component('general')->dateDiff($start, $end, 'y');

        if ($year > 1) {
            return implode(' AT ', [
                date('M d, Y', strtotime($this->createdAt)),
                date('h:i A', strtotime($this->createdAt)),
            ]);
        }
        elseif ($month > 1 || $day >= 6) {
            return implode(' AT ', [
                date('M d', strtotime($this->createdAt)),
                date('h:i A', strtotime($this->createdAt)),
            ]);
        }
        elseif ($day > 1 || $start != $end) {
            return implode(' AT ', [
                date('D', strtotime($this->createdAt)),
                date('h:i A', strtotime($this->createdAt)),
            ]);
        }
        else {
            return date('h:i A', strtotime($this->createdAt));
        }
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\chat\models\query\SpaceMessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\chat\models\query\SpaceMessageQuery(get_called_class());
    }

    public function getSpace()
    {
        return $this->hasOne(Space::className(), ['id' => 'space_id']);
    }

    public function getSpaceGroup()
    {
        return $this->hasOne(SpaceGroup::className(), ['space_id' => 'id'])
            ->via('space');
    }
     
    public function gridColumns()
    {
        return [
            'reply_id' => [
                'attribute' => 'reply_id', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->reply_id,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'space_id' => ['attribute' => 'space_id', 'format' => 'raw'],
            'content' => ['attribute' => 'content', 'format' => 'raw'],
            'attachments' => ['attribute' => 'attachments', 'format' => 'raw'],
            'type' => ['attribute' => 'type', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'reply_id:raw',
            'space_id:raw',
            'content:raw',
            'attachments:raw',
            'type:raw',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }


    public function getSenderPhotoLink()
    {
        if (($user = $this->user) != null) {
            return $user->photoLink;
        }
    }

    public function getUserPhoto()
    {
        if (($user = $this->user) != null) {
            return $user->photo;
        }
    }

    public function getSenderName()
    {
        if (($user = $this->user) != null) {
            return $user->fullName;
        }
    }

    public function getIsTypeLabel()
    {
        return $this->type == self::TYPE_LABEL;
    }

    public function getFormattedContent()
    {
        return App::formatter()->asNtext($this->content);
    }

    public function getIsSender()
    {
        return App::identity('id') == $this->created_by;
    }

    public static function spaceCreated($space)
    {
        $identity = App::identity();

        $model = new self([
            'space_id' => $space->id,
            'type' => self::TYPE_LABEL,
            'content' => "{$identity->fullname} created this space"
        ]);

        $model->save();
    }

    public static function privateSpaceCreated($space, $data)
    {
        $identity = App::identity();
        $totalMembers = number_format(count($data));

        $model = new self([
            'space_id' => $space->id,
            'type' => self::TYPE_LABEL,
            'content' => "{$identity->fullname} created this space with ({$totalMembers}) members"
        ]);
        $model->save();
    }

    public static function spaceRenamed($space, $oldName)
    {
        $identity = App::identity();

        $model = new self([
            'space_id' => $space->id,
            'type' => self::TYPE_LABEL,
            'content' => "{$identity->fullname} rename this space from {$oldName} to {$space->name}"
        ]);
        $model->save();
    }

    public static function leave($space, $user)
    {
        $model = new self([
            'space_id' => $space->id,
            'type' => self::TYPE_LABEL,
            'content' => "{$user->fullname} leave from space"
        ]);
        $model->save();
    }

    public static function replacePhoto($space)
    {
        $identity = App::identity();

        $model = new self([
            'space_id' => $space->id,
            'type' => self::TYPE_LABEL,
            'content' => "{$identity->fullname} replace the space photo"
        ]);
        $model->save();
    }

    public static function block($space, $user)
    {
        $model = new self([
            'space_id' => $space->id,
            'type' => self::TYPE_LABEL,
            'content' => "{$user->fullname} block the conversation"
        ]);
        $model->save();
    }

    public static function unblock($space, $user)
    {
        $model = new self([
            'space_id' => $space->id,
            'type' => self::TYPE_LABEL,
            'content' => "{$user->fullname} unblock the conversation"
        ]);
        $model->save();
    }

    public static function personalSpaceCreated($space)
    {
        $identity = App::identity();

        $model = new self([
            'space_id' => $space->id,
            'type' => self::TYPE_LABEL,
            'content' => "{$identity->fullname} created this connection"
        ]);
        $model->save();
    }


    public static function spaceAddedMembers($space, $users)
    {
        $identity = App::identity();

        if (is_array($users)) {
            $arrayMap = ArrayHelper::map($users, 'id', 'fullname');
            $names = App::formatter('asImplodeLimit', array_values($arrayMap));
        }
        else {
            $names = $users;
        }

        $model = new self([
            'space_id' => $space->id,
            'type' => self::TYPE_LABEL,
            'content' => "{$identity->fullname} added {$names} to the space"
        ]);
        $model->save();
    }

    public static function spaceRemovedMembers($space, $spaceGroups)
    {
        $identity = App::identity();

        if (is_array($spaceGroups)) {
            $arrayMap = ArrayHelper::map($spaceGroups, 'id', 'userFullname');
            $names = App::formatter('asImplodeLimit', array_values($arrayMap));
        }
        else {
            $names = $spaceGroups;
        }

        $model = new self([
            'space_id' => $space->id,
            'type' => self::TYPE_LABEL,
            'content' => "{$identity->fullname} removed {$names} from the space"
        ]);
        $model->save();
    }

    public static function totalMessages()
    {
        $identity = App::identity();
        return self::find()
            ->alias('sm')
            ->joinWith(['space s', 'spaceGroup sg'])
            ->where([
                'or',
                ['sg.user_id' => $identity->id], //must be added to the room member
                ['s.created_by' => $identity->id], //creator of the room
                ['s.type' => Space::TYPE_PUBLIC] //show public rooms
            ])
            ->groupBy('sm.id')
            ->count();
    }

    public function afterSave($insert, $changedAttributes)
    {
        $identity = App::identity();
        if ($insert) {
            if ($this->space->isPrivate) {
                if (($spaceGroups = $this->space->spaceGroups) != null) {
                    $data = [];

                    foreach ($spaceGroups as $key => $spaceGroup) {
                        if ($spaceGroup->user_id != $identity->id) {
                            $data[] = [
                                'user_id' => $spaceGroup->user_id,
                                'space_id' => $this->space_id,
                                'space_message_id' => $this->id,
                                'state' => SpaceNotification::STATE_UNREAD,
                                'token' => implode('-', [$this->space_id, $this->id, $key])
                            ];
                        }
                    }

                    SpaceNotification::batchInsert($data);
                }
            }
            else {
                if (($users = User::available()) != null) {
                    $data = [];
                    foreach ($users as $key => $user) {
                        $data[] = [
                            'user_id' => $user->id,
                            'space_id' => $this->space_id,
                            'space_message_id' => $this->id,
                            'state' => SpaceNotification::STATE_UNREAD,
                            'token' => implode('-', [$this->space_id, $this->id, $key])
                        ];
                    }
                    SpaceNotification::batchInsert($data);
                }
            }
        }
    }

    public function getFiles()
    {
        return File::findAll(['token' => $this->attachments]);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->type == self::TYPE_LABEL) {
            return true;
        }

        if ($this->space->is_block) {
            return false;
        }

        return true;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['JsonBehavior']['fields'] = [
            'attachments', 
        ];

        return $behaviors;
    }
}