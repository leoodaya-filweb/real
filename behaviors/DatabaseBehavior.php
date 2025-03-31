<?php

namespace app\behaviors;

use app\helpers\App;
use app\models\Database;
use app\models\Member;
use app\widgets\DatabaseReport;
use yii\db\ActiveRecord;

class DatabaseBehavior extends \yii\base\Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_INIT => 'eventInit',
            ActiveRecord::EVENT_AFTER_FIND => 'eventAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'eventBeforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'eventBeforeUpdate',
            ActiveRecord::EVENT_AFTER_INSERT => 'eventAfterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'eventAfterUpdate',
        ];
    }

    public function setTheOrginazations()
    {
        if ($this->owner->priority_sector == Database::PYAP_ID) {
            if ($this->owner->organizations == null) {
                $data = [];
                $row = ['name' => '', 'position' => '', 'year' => ''];

                for ($i=1; $i <= 3; $i++) { 
                    $row['no'] = $i;
                    array_push($data, $row);
                }
            }
            else {
                $data = $this->owner->organizations;
                foreach ($data as $key => &$d) {
                    $d['no'] = $d['no'] ?? ($key + 1);
                    $d['name'] = $d['name'] ?? '';
                    $d['position'] = $d['position'] ?? '';
                    $d['year'] = $d['year'] ?? '';
                }
            }

            $this->owner->organizations = $data;
        }
    }

    public function setTheWorkExperience()
    {
        if ($this->owner->priority_sector == Database::PYAP_ID) {
            if ($this->owner->work_experience == null) {
                $data = [];
                $row = ['year_month' => '', 'job_title' => '', 'monthly_income' => '', 'reason_for_leaving' => ''];

                for ($i=1; $i <= 4; $i++) { 
                    $row['no'] = $i;
                    array_push($data, $row);
                }
            }
            else {
                $data = $this->owner->work_experience;

                foreach ($data as $key => &$d) {
                    $income = App::formatter('asNumber', (int)$d['monthly_income'] ?? 0);
                    $income = $d['year_month'] ? $income: '';

                    $d['no'] = $d['no'] ?? ($key + 1);
                    $d['year_month'] = $d['year_month'] ?? '';
                    $d['job_title'] = $d['job_title'] ?? '';
                    $d['monthly_income'] = $income;
                    $d['reason_for_leaving'] = $d['reason_for_leaving'] ?? '';
                }
            }

            $this->owner->work_experience = $data;
        }
    }

    public function setTheFamilyComposition()
    {
        if ($this->owner->family_composition == null) {
            $data = [];
            $rowCount = 10;
            $row = ['name' => '', 'birth_date' => '', 'age' => '', 'civil_status' => '', 'relationship' => '', 'occupation' => '', 'income' => ''];
            

            switch ($this->owner->priority_sector) {
                case Database::PYAP_ID:
                    $row = ['name' => '', 'gender' => '', 'age' => '',  'grade' => '', 'isy' => '', 'osy' => ''];

                    $rowCount = 5;
                    break;
                
                default:
                    
                    break;
            }

            for ($i=1; $i <= $rowCount; $i++) { 
                $row['no'] = $i;
                array_push($data, $row);
            }
        }
        else {
            $data = $this->owner->family_composition;

            switch ($this->owner->priority_sector) {
                case Database::PYAP_ID:
                    foreach ($data as $key => &$d) {
                        $d['no'] = $d['no'] ?? ($key + 1);
                        $d['name'] = strtoupper($d['name']) ?? '';
                        $d['gender'] = $d['gender'] ?? '';
                        $d['age'] = $d['age'] ?? '';
                        $d['grade'] = $d['grade'] ?? '';
                        $d['isy'] = $d['isy'] ?? '';
                        $d['osy'] = $d['osy'] ?? '';
                    }
                    break;
                
                default:
                    foreach ($data as $key => &$d) {
                        $income = App::formatter('asNumber', (int)$d['income'] ?? 0);
                        $income = $d['name'] ? $income: '';

                        $d['no'] = $d['no'] ?? ($key + 1);
                        $d['name'] = strtoupper($d['name']) ?? '';
                        $d['birth_date'] = $d['birth_date'] ?? '';
                        $d['age'] = $d['age'] ?? '';
                        $d['civil_status'] = $d['civil_status'] ?? '';
                        $d['relationship'] = $d['relationship'] ?? '';
                        $d['occupation'] = $d['occupation'] ?? '';
                        $d['income'] = $income;
                    }
                    break;
            }
        }

        $this->owner->family_composition = $data;
    }

    public function eventInit($event)
    {
        if ($this->owner->isNewRecord) {
            $this->owner->status = 'Active';
        }

        $this->setTheFamilyComposition();
        $this->setTheWorkExperience();
        $this->setTheOrginazations();
    }

    public function eventAfterFind($event)
    {
        $this->setTheFamilyComposition();
        $this->setTheWorkExperience();
        $this->setTheOrginazations();
    }

    public function eventBeforeInsert($event)
    {
        $this->owner->status = $this->owner->isActive ? 'Active': 'Inactive';

        $this->owner->is_senior = $this->owner->priority_sector == Database::SC_ID ? Database::SENIOR_YES: Database::SENIOR_NO;

        if (App::isLogin()) {
            $fullname = App::identity('fullname');

            $this->owner->encoded_by = $fullname;
            $this->owner->edited_by = $fullname;
        }
    }

    public function eventBeforeUpdate($event)
    {
        $this->owner->status = $this->owner->isActive ? 'Active': 'Inactive';
        $this->owner->is_senior = $this->owner->priority_sector == Database::SC_ID ? Database::SENIOR_YES: Database::SENIOR_NO;

        if (App::isLogin()) {
            $this->owner->edited_by = App::identity('fullname');
        }
    }

    public function updateIsSenior()
    {
        if ($this->owner->priority_sector == Database::SC_ID) {
            Database::updateAllNoLogs(
                ['is_senior' => Database::SENIOR_YES],
                ['CONCAT_WS(" ", first_name, middle_name, last_name, date_of_birth)' => implode(' ', [
                    $this->owner->first_name,
                    $this->owner->middle_name,
                    $this->owner->last_name,
                    date('Y-m-d', strtotime($this->owner->date_of_birth)),
                ])]
            );
        }
    }

    public function eventAfterUpdate($event)
    {
        if (($member = $this->owner->member) != null) {
            if ($this->owner->isActive) {
                Member::activeAll(['id' => $member->id]);
            }
            else {
                Member::inactiveAll(['id' => $member->id]);
            }
        }
        $this->updateIsSenior();
    }

    public function eventAfterInsert($event)
    {
        $this->updateIsSenior();
    }

}