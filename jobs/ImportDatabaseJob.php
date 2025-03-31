<?php

namespace app\jobs;

use Yii;
use app\helpers\App;
use app\models\form\DatabaseImportForm;

class ImportDatabaseJob extends \yii\base\BaseObject implements \yii\queue\RetryableJobInterface
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
    	$model = new DatabaseImportForm([
            'file_token' => $this->file_token,
            'user_id' => $this->user_id,
        ]);
        
        $model->save();

        if ($model->errors) {
            print_r(substr(json_encode($model->errors), 0, 10000));
            return false;
        }

        return true;
    }
}