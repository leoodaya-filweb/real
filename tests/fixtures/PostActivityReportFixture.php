<?php

namespace app\tests\fixtures;

class PostActivityReportFixture extends \yii\test\ActiveFixture
{
    public $modelClass = 'app\models\PostActivityReport';
    public $dataFile = '@app/tests/fixtures/data/post-activity-report.php';
    public $depends = ['app\tests\fixtures\UserFixture'];
}