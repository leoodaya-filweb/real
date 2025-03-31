<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\jobs\ImportMemberJob;
use app\models\Member;
use app\models\Queue;
use app\models\form\BulkImportMemberForm;

class ImportMemberController extends Controller 
{
    public function actionIndex()
    {
        $model = new BulkImportMemberForm();
        $member = new Member();

        if ($model->load(App::post())) {
            if ($model->validate()) {
                Queue::push(new ImportMemberJob([
                    'file_token' => $model->file_token,
                    'user_id' => App::identity('id')
                ]));
                App::success('The member data will be imported in the queue.');
            }
            else {
                App::danger(Html::errorSummary($model));
            }

            return $this->redirect(['index']);
        }

        return $this->render('/member/import', [
            'member' => $member,
            'model' => $model,
        ]);
    }
} 