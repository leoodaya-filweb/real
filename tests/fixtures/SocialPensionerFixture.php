<?php

namespace app\tests\fixtures;

class SocialPensionerFixture extends \yii\test\ActiveFixture
{
    public $modelClass = 'app\models\SocialPensioner';
    public $dataFile = '@app/tests/fixtures/data/social-pensioner.php';
    public $depends = ['app\tests\fixtures\UserFixture'];
}