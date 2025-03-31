<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Url;
use app\helpers\Html;
use app\models\Member;
use app\models\EventMember;

class Dafac extends BaseWidget
{
    public $household;
    public $content;
    public $contentOnly = false;


    public function getDafacFamilyMemberPlaceholder()
    {
        $data = [];
        for ($i=1; $i <= 5 ; $i++) { 
            foreach (['M', 'MR', 'MA', 'MG', 'MC', 'ME', 'MO', 'MM'] as $letter) {
                $data[$i][] = implode('', [$letter, $i]);
            }
        }
        return $data;
    }

    public function getDafacAssistancePlaceholder()
    {
        $data = [];
        for ($i=1; $i <= 38 ; $i++) { 
            foreach (['D', 'N', 'K', 'Q', 'C', 'P'] as $letter) {
                $data[$i][] = implode('', [$letter, $i]);
            }
        }
        return $data;
    }

    public function init()
    {
        parent::init();
        $template = App::setting('reportTemplate');
        $template->setData();

        $currentDate = App::formatter()->asDateToTimezone();
        
        $replace = [
            '[BARANGAY_NAME]' => $this->household->barangayName,
            '[DATE_REGISTERED]' => date('m/d/Y h:i A', strtotime($this->household->transfer_date)),
        ];

        if (($head = $this->household->familyHead) != null) {
            $replace['[SURNAME]'] = $head->last_name;
            $replace['[FIRST_NAME]'] = $head->first_name;
            $replace['[MIDDLE_NAME]'] = $head->middle_name;
            $replace['[AGE]'] = $head->currentAge;
            $replace['[DATE_OF_BIRTH]'] = $head->birthDate;
            $replace['[OCCUPATION]'] = $head->occupation ?: 'None';
            $replace['[MONTHLY_INCOME]'] = $head->monthlyIncome;
            
            if ($head->isMale) {
                $replace['id="checkbox-male"'] = 'id="checkbox-male" checked';
            }
            else {
                $replace['id="checkbox-female"'] = 'id="checkbox-female" checked';
            }
            
            if ($head->isSingle) {
                $replace['id="checkbox-single"'] = 'id="checkbox-single" checked';
                $replace['[OTHERS]'] = '';
            }
            elseif ($head->isMarried) {
                $replace['id="checkbox-married"'] = 'id="checkbox-married" checked';
                $replace['[OTHERS]'] = '';
            } 
            elseif ($head->isWidow) {
                $replace['id="checkbox-widow"'] = 'id="checkbox-widow" checked';
                $replace['[OTHERS]'] = '';
            } 
            else {
                $replace['[OTHERS]'] = $head->civilStatusName;
            }

            if ($head->is4Ps) {
                $replace['id="checkbox-4ps"'] = 'id="checkbox-4ps" checked';
            }
        }

        $members = Member::find()
            ->where([
                'household_id' => $this->household->id,
                'head' => Member::FAMILY_HEAD_NO,
                'living_status' => Member::ALIVE
            ])
            ->limit(5)
            ->all();
        foreach ($this->dafacFamilyMemberPlaceholder as $key => $d) {
            if ($members && isset($members[$key - 1])) {
                $member = $members[$key - 1];
                $replace["[{$d[0]}]"] = $member->name;

                if ($member->relationName == 'Wife/Spouse') {
                    $replace["[{$d[1]}]"] = ($member->isMale) ? 'Spouse': 'Wife';
                }
                elseif ($member->relationName == 'Son/Daughter') {
                    $replace["[{$d[1]}]"] = ($member->isMale) ? 'Son': 'Daughter';
                }
                elseif ($member->relationName == 'Son in law/Daughter in law
                ') {
                    $replace["[{$d[1]}]"] = ($member->isMale) ? 'Son in law': 'Daughter in law';
                }
                elseif ($member->relationName == 'Grandson/Granddaughter
                ') {
                    $replace["[{$d[1]}]"] = ($member->isMale) ? 'Grandson': 'Granddaughter';
                }
                elseif ($member->relationName == 'Father/Mother
                ') {
                    $replace["[{$d[1]}]"] = ($member->isMale) ? 'Father': 'Mother';
                }
                elseif ($member->relationName == 'Housemaid/boy
                ') {
                    $replace["[{$d[1]}]"] = ($member->isMale) ? 'Housemaid': 'Houseboy';
                }

                $replace["[{$d[2]}]"] = $member->currentAge;
                $replace["[{$d[3]}]"] = $member->sexLabel;
                $replace["[{$d[4]}]"] = $member->civilStatusName;
                $replace["[{$d[5]}]"] = $member->educationalAttainmentLabel ?: 'None';
                $replace["[{$d[6]}]"] = $member->occupation ?: 'None';
                $replace["[{$d[7]}]"] = '';
            }
            else {
                $replace["[{$d[0]}]"] = '';
                $replace["[{$d[1]}]"] = '';
                $replace["[{$d[2]}]"] = '';
                $replace["[{$d[3]}]"] = '';
                $replace["[{$d[4]}]"] = '';
                $replace["[{$d[5]}]"] = '';
                $replace["[{$d[6]}]"] = '';
                $replace["[{$d[7]}]"] = '';
            }
        }

        $eventMembers = EventMember::find()
            ->alias('em')
            ->joinWith(['member m', 'event e'])
            ->where([
                'e.status' => [
                    EventMember::ATTENDED,
                    EventMember::CLAIMED,
                ],
                'm.household_id' => $this->household->id,
                'e.category_id' => [1, 2]
            ])
            ->groupBy('em.id')
            ->all();
        
        foreach ($this->dafacAssistancePlaceholder as $key => $d) {
            if ($eventMembers && isset($eventMembers[$key - 1])) {
                $eventMember = $eventMembers[$key - 1];

                $member = $eventMember->member;
                $event = $eventMember->event;

                $replace["[{$d[0]}]"] = date('m/d/y', strtotime($eventMember->created_at));
                $replace["[{$d[1]}]"] = $member ? $member->name: '';
                $replace["[{$d[2]}]"] = ($event->isTypeAssistance)? $event->assistanceTypeLabel: $event->eventTypeLabel;
                $replace["[{$d[3]}]"] = 0;
                $replace["[{$d[4]}]"] = $event ? $event->amount: '';
                $replace["[{$d[5]}]"] = '';
            }
            else {
                $replace["[{$d[0]}]"] = '';
                $replace["[{$d[1]}]"] = '';
                $replace["[{$d[2]}]"] = '';
                $replace["[{$d[3]}]"] = '';
                $replace["[{$d[4]}]"] = '';
                $replace["[{$d[5]}]"] = '';
            }
        }



        $this->content = str_replace(
            array_keys($replace), 
            array_values($replace), 
            $template->dafac
        );
    }


    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('dafac', [
            'content' => $this->content,
            'household' => $this->household,
        ]);
    }
}
