<?php

namespace app\modules\chat\models;

use Yii;
use app\modules\chat\helpers\App;
use app\modules\chat\widgets\Anchor;

/**
 * This is the model class for table "{{%space_notification}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $space_id
 * @property int $space_message_id
 * @property int|null $state
 * @property string $token
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class SpaceNotification extends ActiveRecord
{
    const STATE_UNREAD = 0;
    const STATE_READ = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%space_notifications}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'space-notification',
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
            [['user_id', 'space_id', 'space_message_id', 'state'], 'integer'],
            ['state', 'default' , 'value' => self::STATE_UNREAD],
            ['state', 'in', 'range' => [
                self::STATE_UNREAD,
                self::STATE_READ,
            ]],
            ['user_id', 'exist', 'targetRelation' => 'user'],
            ['space_id', 'exist', 'targetRelation' => 'space'],
            ['space_message_id', 'exist', 'targetRelation' => 'spaceMessage'],
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
            'space_id' => 'Space ID',
            'space_message_id' => 'Space Message ID',
            'state' => 'State',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\chat\models\query\SpaceNotificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\chat\models\query\SpaceNotificationQuery(get_called_class());
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getSpace()
    {
        return $this->hasOne(Space::className(), ['id' => 'space_id']);
    }

    public function getSpaceMessage()
    {
        return $this->hasOne(SpaceMessage::className(), ['id' => 'space_message_id']);
    }
     
    public function gridColumns()
    {
        return [
            'user_id' => [
                'attribute' => 'user_id', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->user_id,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'space_id' => ['attribute' => 'space_id', 'format' => 'raw'],
            'space_message_id' => ['attribute' => 'space_message_id', 'format' => 'raw'],
            'state' => ['attribute' => 'state', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'user_id:raw',
            'space_id:raw',
            'space_message_id:raw',
            'state:raw',
        ];
    }

    public static function readBySpace($space_id, $user_id='')
    {
        $user_id = $user_id ?: App::identity('id');
        $space_id = is_object($space_id) ? $space_id->id: $space_id;

        self::updateAll(['state' => self::STATE_READ], [
            'space_id' => $space_id,
            'user_id' => $user_id,
            'state' => self::STATE_UNREAD
        ]);
    }
}