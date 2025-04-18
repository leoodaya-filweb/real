<?php

namespace app\behaviors;

use yii\db\ActiveRecord;

class JsonBehavior extends \yii\base\Behavior
{
    public $fields = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_INIT => 'afterFind',
        ];
    }

    public function beforeSave($event)
    {
        if ($this->fields) {
            foreach ($this->fields as $e) {
                $data = $this->owner->{$e} ?: [];

                if (is_array($data)) {
                    $this->owner->{$e} = json_encode($data);
                }
            }
        }
    }

    public function afterFind($event)
    {
        if ($this->fields) {
            foreach ($this->fields as $e) {
                $data = $this->owner->{$e} ?: '[]';
                if (!is_array($data)) {
                    $this->owner->{$e} = json_decode($data, true);
                }
            }
        }
    }
}