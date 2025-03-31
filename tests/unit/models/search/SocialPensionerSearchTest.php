<?php

namespace tests\unit\models\search;

use app\models\search\SocialPensionerSearch;

class SocialPensionerSearchTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'developer'
        ]));
    }

    public function testSearchWithResult()
    {
        $searchModel = new SocialPensionerSearch();
        $dataProviders = $searchModel->search(['SocialPensionerSearch' => ['keywords' => '']]);
        expect_that($dataProviders);
        expect($dataProviders->totalCount)->equals(2);
    }

    public function testSearchWithNoResult()
    {
        $searchModel = new SocialPensionerSearch();
        $dataProviders = $searchModel->search([
            'SocialPensionerSearch' => ['keywords' => 'qwertyuiopasdfghjkl234567890']
        ]);

        expect_that($dataProviders);
        expect($dataProviders->totalCount)->equals(0);
    }
}