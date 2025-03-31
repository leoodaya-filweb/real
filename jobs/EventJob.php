<?php

namespace app\jobs;

use Yii;
use app\helpers\App;
use app\models\ActiveRecord;
use app\models\Event;
use app\models\EventMember;
use app\models\search\MemberSearch;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class EventJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public $event_id;
    public $user_id;
    
    public function execute($queue)
    {
        ini_set('max_execution_time', 0); //0=NOLIMIT
        ini_set('memory_limit', '-1');

    	$event = Event::findOne($this->event_id);

        $tableName = EventMember::tableName();

        if ($event) {
            App::execute("DELETE FROM {$tableName} WHERE event_id = {$event->id}");

            $searchModel = new MemberSearch();
            $dataProvider = $searchModel->search(['MemberSearch' => $event->beneficiaries]);
            $dataProvider->pagination = false;
            $data = [];
            if ($dataProvider->totalCount > 0) {

                $membersId = ArrayHelper::map($dataProvider->models, 'id', 'id');

                foreach ($membersId as $member_id) {
                    $status = $event->isAssistance ? EventMember::UNCLAIM: EventMember::UNATTENDED;

                    $em = EventMember::find()
                        ->where([
                            'event_id' => $event->id, 
                            'member_id' => $member_id
                        ])
                        ->exists();

                    if (! $em) {
                        $data[] = [
                            $event->id, 
                            $member_id, 
                            $status, 
                            ActiveRecord::RECORD_ACTIVE,
                            $this->user_id,
                            $this->user_id,
                            new Expression('UTC_TIMESTAMP'), 
                            new Expression('UTC_TIMESTAMP')
                        ];
                    }
                }

                if ($data) {
                    $arr = array_chunk($data, 1000);

                    foreach ($arr as $r) {
                        App::createCommand()
                            ->batchInsert(
                                $tableName, 
                                [
                                    'event_id', 
                                    'member_id', 
                                    'status', 
                                    'record_status', 
                                    'created_by', 
                                    'updated_by', 
                                    'created_at', 
                                    'updated_at'
                                ], 
                                $r
                            )
                            ->execute();
                    }
                }
            }
        }
    }
}