<?php

namespace app\models\form;

use Yii;
use app\helpers\ArrayHelper;
use app\models\Database;
use app\models\form\setting\PrioritySectorSettingsForm;

class PrioritySectorForm extends \yii\base\Model
{
    public $id;
    public $code;
    public $label; 
    public $class; 
    public $user_access;


    public function rules()
    {
        return [
            [['id', 'code', 'label', 'class'], 'required'],
            [['code', 'label', 'class'], 'string'],
            [['id'], 'integer'],
            [['user_access'], 'safe'],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $model = new PrioritySectorSettingsForm();
            
            $data = ArrayHelper::index($model->data, 'id');

            if (in_array($this->id, array_keys($data))) {
                $data[$this->id] = $this->attributes; 
            }
            else {
                $data[] = $this->attributes;
            }

            $model->data = $data;

            return $model->save();
        }
    }

    public function delete()
    {
        $model = new PrioritySectorSettingsForm();
        $data = ArrayHelper::index($model->data, 'id');

        if (in_array($this->id, array_keys($data))) {

            if (($database = Database::findOne(['priority_sector' => $this->id])) == null) {
                unset($data[$this->id]);

                $model->data = $data;

                return $model->save();
            }
            else {
                $this->addError('id', 'Priority sector has database record.');
            }
        }
        else {
            $this->addError('id', 'ID not exist');
        }

        return false;
    }
}