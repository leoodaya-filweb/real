<?php

namespace app\tests\fixtures;

class MedicineFixture extends \yii\test\ActiveFixture
{
    public $modelClass = 'app\models\Medicine';
    public $dataFile = '@app/tests/fixtures/data/medicine.php';
    public $depends = ['app\tests\fixtures\UserFixture'];
}