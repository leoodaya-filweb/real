<?php

namespace tests\unit\jobs;

use app\models\Region;
use app\models\Barangay;
use app\models\Province;
use app\models\Municipality;
use app\jobs\ImportHouseholdJob;

class ImportHouseholdJobTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'developer'
        ]));
    }
    
    public function testExecute()
    {
        $model = new ImportHouseholdJob([
            'file_token' => 'household-OxFBeC2Dzw1624513904-household',
        ]);

        expect_that($model->execute(1));

        $this->tester->seeRecord('app\models\Household', [
            'no' => 729760,
            'longitude' => '121.6030581482',
            'latitude' => '14.6696698107',
            'altitude' => '65.8',
            'region_id' => Region::getRegion4aId(),
            'province_id' => Province::getCalabarzonId(),
            'municipality_id' => Municipality::getRealId(),
            'barangay_id' => 3,
            'zone_no' => 99,
            'purok_no' => 6,
            'street' => 'NONE'
        ]);

        $this->tester->seeRecord('app\models\Household', [
            'no' => 729800,
            'longitude' => '121.6030081082',
            'latitude' => '14.6699229861',
            'altitude' => '38.3',
            'region_id' => Region::getRegion4aId(),
            'province_id' => Province::getCalabarzonId(),
            'municipality_id' => Municipality::getRealId(),
            'barangay_id' => 3,
            'zone_no' => 99,
            'purok_no' => 99,
            'street' => 'NONE'
        ]);
    }
}