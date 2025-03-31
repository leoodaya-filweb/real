<?php

namespace tests\unit\models\search;

use app\models\search\EventSearch;

class EventSearchTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'developer'
        ]));
    }

    public function testSearchWithResult()
    {
        $searchModel = new EventSearch();
        $dataProviders = $searchModel->search(['EventSearch' => ['keywords' => '']]);
        expect_that($dataProviders);
        expect($dataProviders->totalCount)->equals(5);
    }

    public function testSearchWithNoResult()
    {
        $searchModel = new EventSearch();
        $dataProviders = $searchModel->search([
            'EventSearch' => ['keywords' => 'qwertyuiopasdfghjkl234567890']
        ]);

        expect_that($dataProviders);
        expect($dataProviders->totalCount)->equals(0);
    }
}