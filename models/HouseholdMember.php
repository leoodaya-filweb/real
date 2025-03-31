<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\widgets\Anchor;

/**
 * This is the model class for table "{{%household_members}}".
 *
 * @property int $id
 * @property int $household_id
 * @property int $member_id
 * @property int $status
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class HouseholdMember extends ActiveRecord
{
    const REMOVED = 0;
    const ACTIVED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%household_members}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'household-member',
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
            [['household_id', 'member_id'], 'required'],
            [['household_id', 'member_id', 'status'], 'integer'],
            ['household_id', 'exist', 'targetRelation' => 'household'],
            ['member_id', 'exist', 'targetRelation' => 'member'],
            ['status', 'default', 'value' => self::REMOVED],
            ['status', 'in', 'range' => [
                self::REMOVED,
                self::ACTIVED,
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
            'household_id' => 'Household ID',
            'member_id' => 'Member ID',
            'status' => 'Status',
        ]);
    }

    public function getHousehold()
    {
        return $this->hasOne(Household::className(), ['id' => 'household_id']);
    }

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\HouseholdMemberQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\HouseholdMemberQuery(get_called_class());
    }
     
    public function gridColumns()
    {
        return [
            'household_id' => [
                'attribute' => 'household_id', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->household_id,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'member_id' => ['attribute' => 'member_id', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'household_id:raw',
            'member_id:raw',
        ];
    }
}