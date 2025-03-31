<?php

namespace app\models;

use Yii;
use app\helpers\App;

class Masterlist extends SocialPensioner
{

    public function config()
    {
        return [
            'controllerID' => 'masterlist',
            'mainAttribute' => 'fullname',
            'paramName' => 'slug',
            'dateAttribute' => 'date_registered'
        ];
    }

    public function init()
    {
        parent::init();
        $this->status = parent::ADDED;
    }

    public static function find()
    {
        return new \app\models\query\MasterlistQuery(get_called_class());
    }

    public function gridColumns()
    {
        $columns = parent::gridColumns();

        unset($columns['status']);

        return $columns;
    }

    public function getBulkActions()
    {
        $columns = [];

        $columns['remove-to-masterlist'] = [
            'label' => 'Remove to Masterlist',
            'process' => 'remove-to-masterlist',
            'icon' => 'minus',
            'function' => function($id) {
                self::updateAll(['status' => parent::PENDING], ['id' => $id]);
            }
        ];

        if (App::isLogin() && App::identity()->can('delete', $this->controllerID())) {
            $columns['delete'] = [
                'label' => 'Delete',
                'process' => 'delete',
                'icon' => 'delete',
                'function' => function($id) {
                    self::deleteAll(['id' => $id]);
                }
            ];
        }
        
        return $columns;
    }

    public function getStartDate($from_database = false)
    {
        if ($this->date_range && $from_database == false) {
            $date = App::formatter()->asDaterangeToSingle($this->date_range, 'start');
            return date('F d, Y', strtotime($date));
        }
        else {
            if ($this->_startDate === null) {
                $this->_startDate = self::find()
                    ->visible()
                    ->min($this->dateAttribute);
            }
            $date = $this->_startDate ?: 'today';
        }

        return App::formatter()->asDateToTimezone($date, 'F d, Y');
    }

    public function getEndDate($from_database = false)
    {
        if ($this->date_range && $from_database == false) {
            $date = App::formatter()->asDaterangeToSingle($this->date_range, 'end');
            return date('F d, Y', strtotime($date));
        }
        else {
            if ($this->_endDate === null) {
                $this->_endDate = self::find()
                    ->visible()
                    ->max($this->dateAttribute);
            }
            $date = ($this->_endDate)? $this->_endDate: 'today';
        }

        return App::formatter()->asDateToTimezone($date, 'F d, Y');
    }

    public function getMemberId()
    {
        if (($member = Member::findOne(['qr_id' => $this->qr_id])) != null) {
            return $member->id;
        }
    }

    public function removeFromMasterlist()
    {
        $this->status = parent::PENDING;
        return $this->save();
    }
}