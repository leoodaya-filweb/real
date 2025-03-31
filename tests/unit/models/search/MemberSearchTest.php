<?php

namespace tests\unit\models\search;

use app\models\search\MemberSearch;

class MemberSearchTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'developer'
        ]));
    }

    public function testSearchWithResult()
    {
        $searchModel = new MemberSearch();
        $dataProviders = $searchModel->search(['MemberSearch' => ['keywords' => '']]);
        expect_that($dataProviders);
        expect($dataProviders->totalCount)->equals(8);
    }

    public function testSearchWithNoResult()
    {
        $searchModel = new MemberSearch();
        $dataProviders = $searchModel->search([
            'MemberSearch' => ['keywords' => 'qwertyuiopasdfghjkl234567890']
        ]);

        expect_that($dataProviders);
        expect($dataProviders->totalCount)->equals(0);
    }
}