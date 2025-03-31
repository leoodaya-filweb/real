<?php

namespace app\modules\chat\models;

use Yii;
use app\modules\chat\helpers\App;
use app\modules\chat\widgets\Anchor;
use yii\db\Expression;

/**
 * This is the model class for table "{{%space_group}}".
 *
 * @property int $id
 * @property int $space_id
 * @property int $user_id
 * @property string $token
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class SpaceGroup extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%space_groups}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'space-group',
            'mainAttribute' => 'id',
            'paramName' => 'id',
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        $fields['userPhotoLink'] = 'userPhotoLink';
        $fields['viewUrl'] = 'userViewUrl';
        $fields['fullname'] = 'userFullname';
        $fields['email'] = 'userEmail';
        $fields['timestamp'] = 'timestamp';
        
        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['space_id', 'user_id'], 'integer'],
            ['space_id', 'exist', 'targetRelation' => 'space'],
            ['user_id', 'exist', 'targetRelation' => 'user'],
            [['space_id', 'user_id'], 'validateExistense'],
        ]);
    }

    public function getUserEmail()
    {
        if (($user = $this->user) != null) {
            return $user->email;
        }
    }

    public function getUserViewUrl()
    {
        if (($user = $this->user) != null) {
            return $user->viewUrl;
        }
    }

    public function getUserPhotoLink()
    {
        if (($user = $this->user) != null) {
            return $user->photoLink;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'space_id' => 'Space ID',
            'user_id' => 'User ID',
        ]);
    }

    public function validateExistense($attribute, $params)
    {
        if ($this->isNewRecord) {
            $model = self::findOne(['space_id' => $this->space_id, 'user_id' => $this->user_id]);

            if ($model) {
                $this->addError('user', 'User already exist / added to the space.');
            }
        }
    }

    public function getSpace()
    {
        return $this->hasOne(Space::className(), ['id' => 'space_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUserFullname()
    {
        if (($user = $this->user) != null) {
            return $user->fullname;
        }
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\chat\models\query\SpaceGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\chat\models\query\SpaceGroupQuery(get_called_class());
    }
     
    public function gridColumns()
    {
        return [
            'space_id' => [
                'attribute' => 'space_id', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->space_id,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'user_id' => ['attribute' => 'user_id', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'space_id:raw',
            'user_id:raw',
        ];
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if (App::identity('id') != $this->user_id) {
            SpaceMessage::spaceRemovedMembers($this->space, $this->userFullname);
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            SpaceMessage::spaceAddedMembers($this->space, $this->userFullname);
        }
    }

    public static function rowAttributes($space_id, $user_id)
    {
        $identity  = App::identity();
        return [
            'space_id' => $space_id, 
            'user_id' => $user_id,
            'token' => implode('', [$space_id, $user_id, time()]),
            'record_status' => SpaceGroup::RECORD_ACTIVE,
            'created_by' => $identity->id,
            'updated_by' => $identity->id,
            'created_at' => new Expression('UTC_TIMESTAMP'),
            'updated_at' => new Expression('UTC_TIMESTAMP'),
        ];
    }
}