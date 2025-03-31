<?php

namespace app\jobs;

use Yii;
use app\helpers\App;
use app\models\form\BulkImportMemberForm;
use app\models\form\ImportMemberForm;

class ImportMemberJob extends \yii\base\BaseObject implements \yii\queue\RetryableJobInterface
{
    public $file_token;
    public $user_id = 0;

    public function getTtr()
    {
        return 10 * (60 * 60);
    }

    public function canRetry($attempt, $error)
    {
        return ($attempt < 5) && ($error instanceof TemporaryException);
    }
    
    public function execute($queue)
    {
    	$model = new BulkImportMemberForm([
            'file_token' => $this->file_token,
            'user_id' => $this->user_id,
        ]);
        $model->save();

        if ($model->errors) {
            print_r($model->errors);
            return false;
        }

        return true;
    }
}