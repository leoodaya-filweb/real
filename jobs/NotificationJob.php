<?php

namespace app\jobs;

use Yii;
use app\models\Notification;
use yii\db\Expression;

class NotificationJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public $user_id;
    public $type;
    public $link;
    public $message;

    public $loginId = 0;
    
    public function execute($queue)
    {
        $result = false;
        if (is_array($this->user_id)) {
            foreach ($this->user_id as $user_id) {
                $result = $this->insertNotification($user_id);
            }
        }
    	else {
            $result = $this->insertNotification($this->user_id);
        }

        return $result;
    }

    public function insertNotification($user_id)
    {
        $notification = new Notification([
            'status' => Notification::STATUS_UNREAD,
            'record_status' => 1,
            'user_id' => $user_id,
            'type' => $this->type,
            'link' => $this->link,
            'message' => $this->message,
        ]);
        $notification->created_by = $this->loginId;
        $notification->updated_by = $this->loginId;
        $notification->created_at = new Expression('UTC_TIMESTAMP');
        $notification->updated_at = new Expression('UTC_TIMESTAMP');

        return $notification->save();
    }
}