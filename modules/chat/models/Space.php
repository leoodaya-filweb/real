<?php

namespace app\modules\chat\models;

use Yii;
use app\modules\chat\helpers\App;
use app\modules\chat\helpers\Url;
use app\modules\chat\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%space}}".
 *
 * @property int $id
 * @property string $name
 * @property int|null $type
 * @property string $token
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Space extends ActiveRecord
{
    const TYPE_PRIVATE = 0;
    const TYPE_PUBLIC = 1;
    const TYPE_PERSONAL = 2;

    const IS_BLOCK_NO = 0;
    const IS_BLOCK_YES = 1;

    public $private_members;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%spaces}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'space',
            'mainAttribute' => 'id',
            'paramName' => 'id',
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        $fields['photoChangable'] = 'photoChangable';
        $fields['renamable'] = 'renamable';
        $fields['canAddPrivateMember'] = 'canAddPrivateMember';
        $fields['userIsCreator'] = 'userIsCreator';
        $fields['leavable'] = 'leavable';
        $fields['unblockableConversation'] = 'unblockableConversation';
        $fields['blockableConversation'] = 'blockableConversation';
        $fields['blockByUserFullname'] = 'blockByUserFullname';
        $fields['activeMembers'] = 'activeMembers';
        $fields['minimumMessageId'] = 'minimumMessageId';
        $fields['files'] = 'files';
        $fields['displayImageUrl'] = 'displayImageUrl';
        $fields['firstLetter'] = 'firstLetter';
        $fields['displayName'] = 'displayName';

        $fields['typeLabel'] = 'typeLabel';
        $fields['totalSpaceNotificationsByUser'] = 'totalSpaceNotificationsByUser';
        $fields['isPrivate'] = 'isPrivate';
        $fields['isPersonal'] = 'isPersonal';
        $fields['isPublic'] = 'isPublic';
        $fields['createdAt'] = 'createdAt';
        $fields['createdByEmail'] = 'createdByEmail';
        $fields['timestamp'] = 'timestamp';

        $fields['totalSpaceGroups'] = 'totalSpaceGroups';
        $fields['totalSpaceMessages'] = 'totalSpaceMessages';
        
        return $fields;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $typePrivate = self::TYPE_PRIVATE;

        return $this->setRules([
            [['name', 'type'], 'required'],
            [['type', 'user_id', 'is_block', 'is_block_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            ['type', 'in', 'range' => [
                self::TYPE_PRIVATE,
                self::TYPE_PUBLIC,
                self::TYPE_PERSONAL,
            ]],
            ['is_block', 'in', 'range' => [
                self::IS_BLOCK_NO,
                self::IS_BLOCK_YES,
            ]],
            [['private_members'], 'required', 'when' => function($model) {
                return $model->isNewRecord  && ($model->type == self::TYPE_PRIVATE || $model->type == self::TYPE_PERSONAL);
            }, 'whenClient' => <<< JS
                function (attribute, value) {
                    return $('#space-type').val() == {$typePrivate};
                }
            JS],
            [['photo'], 'safe']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
        ]);
    }

    public function getTotalSpaceMessages()
    {
        return SpaceMessage::find()
            ->where(['space_id' => $this->id])
            ->count();
    }

    public function getTotalSpaceGroups()
    {
        return SpaceGroup::find()
            ->where(['space_id' => $this->id])
            ->count();
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\chat\models\query\SpaceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\chat\models\query\SpaceQuery(get_called_class());
    }

    public function gridColumns()
    {
        return [
            'name' => [
                'attribute' => 'name', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->name,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'type' => ['attribute' => 'type', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'name:raw',
            'type:raw',
        ];
    }

    public function getSpaceGroups()
    {
        return $this->hasMany(SpaceGroup::class, ['space_id' => 'id']);
    }

    public function getSpaceMessages()
    {
        return $this->hasMany(SpaceMessage::class, ['space_id' => 'id'])
            ->orderBy(['id' => SORT_DESC]);
    }

    public function getSpaceNotifications()
    {
        return $this->hasMany(SpaceNotification::class, ['space_id' => 'id']);
    }

    public function getTotalSpaceNotificationsByUser()
    {
        return SpaceNotification::find()
            ->where([
                'space_id' => $this->id,
                'user_id' => App::identity('id'),
                'state' => SpaceNotification::STATE_UNREAD
            ])
            ->count();
    }

    public function getSpaceNotificationsByUser()
    {
        $user_id = App::identity('id') ?: '';

        return SpaceNotification::find()
            ->where([
                'space_id' => $this->id,
                'user_id' => $user_id,
            ])
            ->all();
    }

    public function getIsPersonal()
    {
        return $this->type == self::TYPE_PERSONAL;
    }

    public function getIsPrivate()
    {
        return $this->type == self::TYPE_PRIVATE;
    }

    public function getIsPublic()
    {
        return $this->type == self::TYPE_PUBLIC;
    }

    public function afterSave($insert, $changedAttributes)
    {
        $identity = App::identity();
        if ($insert) {

            if ($this->private_members) {
                $data = [
                    SpaceGroup::rowAttributes($this->id, $identity->id)
                ];
                if ($this->private_members) {
                    foreach ($this->private_members as $user_id) {
                        $data[] = SpaceGroup::rowAttributes($this->id, $user_id);
                    }
                }
                SpaceGroup::batchInsert($data);
            }

            if ($this->isPrivate) {
                SpaceMessage::privateSpaceCreated($this, $data);
            }
            elseif ($this->isPersonal) {
                SpaceMessage::personalSpaceCreated($this);
            }
            else {
                SpaceMessage::spaceCreated($this);
            }
        }
        else {
            if (isset($changedAttributes['name'])) {
                SpaceMessage::spaceRenamed($this, $changedAttributes['name']);
            }
        }
    }

    public static function available($user_id='')
    {
        $user_id = $user_id ?: App::identity('id');

        $models = self::find()
            ->alias('s')
            ->joinWith(['spaceGroups sg', 'spaceMessages sm'])
            ->where([
                'or',
                ['sg.user_id' => $user_id], //must be added to the room member
                ['s.created_by' => $user_id], //creator of the room
                ['s.user_id' => $user_id], // personal chat with
                ['s.type' => self::TYPE_PUBLIC], //show public rooms
            ])
            ->andWhere(['s.record_status' => self::RECORD_ACTIVE])
            ->orderBy(['MAX(sm.id)' => SORT_DESC]) //sort by latest message
            ->groupBy('s.id')
            ->all();

        return $models;
    }

    public function getFirstletter()
    {
        return substr($this->name, 0, 1);
    }

    public function getTypeLabel()
    {
        $data = [
            self::TYPE_PRIVATE => 'Private',
            self::TYPE_PUBLIC => 'Public',
            self::TYPE_PERSONAL => 'Personal',
        ];

        return $data[$this->type] ?? '';
    }

    public function getAvailableUsers()
    {
        $user_ids = SpaceGroup::find()
            ->select('user_id')
            ->where(['space_id' => $this->id]);

        return User::find()
            ->where(['NOT IN', 'id', $user_ids])
            ->andWhere(['<>', 'id', $this->created_by])
            ->available()
            ->all();
    }

    public function addSpaceGroups($user_ids=[])
    {
        $identity = App::identity();

        if (($users = User::findAll(['id' => $user_ids])) != null) {
            
            $data = [];
            foreach ($users as $user) {
                $exists = SpaceGroup::find()
                    ->where([
                        'space_id' => $this->id, 
                        'user_id' => $user->id,
                    ])
                    ->exists();

                if (! $exists) {
                    $data[] = SpaceGroup::rowAttributes($this->id, $user->id);
                }
            }

            if ($data) {
                SpaceGroup::batchInsert($data);
                SpaceMessage::spaceAddedMembers($this, $users);

                return true;
            }
        }
        return false;
    }

    public function removeSpaceGroups($token=[])
    {
        $identity = App::identity();
        if (($spaceGroups = SpaceGroup::findAll(['token' => $token])) != null) {

            SpaceGroup::deleteAll(['token' => $token]);
            SpaceMessage::spaceRemovedMembers($this, $spaceGroups);
            return true;
        }
        return false;
    }

    public function getLoadMessages($id=0, $condition=">")
    {
        $models = SpaceMessage::find()
            ->where(['space_id' => $this->id])
            ->andWhere([$condition, 'id', $id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(20)
            ->all();

        return array_reverse($models);
    }

    public static function activeToken($token)
    {
        return self::find()
            ->alias('s')
            ->joinWith(['spaceGroups sg', 'spaceMessages sm'])
            ->where([
                'or',
                ['sg.user_id' => App::identity('id')], //must be added to the room member
                ['s.created_by' => App::identity('id')], //creator of the room
                ['s.type' => self::TYPE_PUBLIC] //show public rooms
            ])
            ->andWhere(['s.token' => $token])
            ->groupBy('s.id')
            ->one();
    }

    public static function existingPersonal($user_id)
    {
        $identity = App::identity();

        $model = Space::find()
            ->where([
                'type' => self::TYPE_PERSONAL,
                'created_by' => $identity->id,
                'user_id' => $user_id
            ])
            ->one();

        if (!$model) {
            $model = Space::find()
                ->where([
                    'type' => self::TYPE_PERSONAL,
                    'created_by' => $user_id,
                    'user_id' => $identity->id
                ])
                ->one();
        }

        return $model;
    }

    public function getPersonalUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getPersonalUserFullname()
    {
        if (($personalUser = $this->personalUser) != null) {
            return $personalUser->fullname;
        }
    }

    public function getDisplayName()
    {
        $identity = App::identity();

        if ($this->isPersonal) {
            if ($this->created_by == $identity->id) {
                return $this->personalUserFullname;
            }
            else {
                return $this->createdByName ?: $this->createdByEmail;
            }
        }

        return $this->name;
    }

    public function getDisplayImageUrl()
    {
        if ($this->photo) {
            return Url::image($this->photo, ['w' => 50]);
        }

        $user = ($this->created_by == App::identity('id')) ? $this->personalUser: $this->createdBy;
        if ($user) {
            return Url::image($user->photo, ['w' => 50]);
        }
    }

    public function getTotalFiles()
    {
        if (($tokens = $this->imageTokens) != null) {
            return count($tokens);
        }

        return 0;
    }

    public function getImageTokens()
    {
        $spaceMessages = $this->spaceMessages;

        $attachments = ArrayHelper::map($spaceMessages, 'id', 'attachments');
        $data = array_values(array_filter($attachments));
        $tokens = array_merge(...$data);

        return $tokens;
    }

    public function getFiles()
    {
        if (($tokens = $this->imageTokens) != null) {

            if ($tokens) {
                $chunk = array_chunk($tokens, 1000);

                $files = array_map(fn($token) => File::find()->where(['token' => $token])->orderBy(['id' => SORT_DESC])->all(), $chunk);

                return array_merge(...$files);
            }
        }

        return [];
    }

    public function getMinimumMessageId()
    {
        $min = SpaceMessage::find()
            ->where(['space_id' => $this->id])
            ->min('id');

        return $min ?: 1;
    }

    public function getActiveMembers()
    {
        $activeUserViaSpaceMessage = SpaceMessage::find()
            ->select('created_by')
            ->where(['space_id' => $this->id])
            ->groupBy('created_by');

        $model = User::find()
            ->where(['id' => $activeUserViaSpaceMessage])
            ->all();


        return $model;
    }

    public function getBlockByUser()
    {
        return $this->hasOne(User::class, ['id' => 'is_block_by']);
    }

    public function getBlockByUserFullname()
    {
        if (($user = $this->blockByUser) != null) {
            return $user->fullname;
        }
    }

    public function getBlockableConversation()
    {
        $identity = App::identity();

        if ($this->is_block == self::IS_BLOCK_NO) {

            if ($this->isPersonal) {
                return true;
            }

            if ($this->isPublic || $this->isPrivate) {
                if ($this->created_by == $identity->id) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getUnblockableConversation()
    {
        $identity = App::identity();

        if ($this->is_block_by == $identity->id && $this->is_block == self::IS_BLOCK_YES) {
            return true;
        }

        return false;
    }

    public function getLeavable()
    {
        $identity = App::identity();

        if ($this->isPrivate) {
            if ($this->created_by != $identity->id) {
                return true;
            }
        }

        return false;
    }

    public function getUserIsCreator()
    {
        $identity = App::identity();

        return $this->created_by == $identity->id;
    }

    public function getCanAddPrivateMember()
    {
        return $this->isPrivate && $this->userIsCreator;
    }

    public function getRenamable()
    {
        if ($this->userIsCreator) {
            if ($this->isPublic || $this->isPrivate) {
                return true;
            }
        }

        return false;
    }

    public function getPhotoChangable()
    {
        if ($this->userIsCreator) {
            return $this->isPublic || $this->isPrivate; 
        }

        return false;
    }
}