<?php

namespace tests\unit\models\form\setting;

use app\models\form\setting\PrioritySectorSettingsForm;

class PrioritySectorSettingsFormTest extends \Codeception\Test\Unit
{
    public function testValid()
    {
        $model = new PrioritySectorSettingsForm();

        expect_that($model->save());

        $this->tester->seeRecord('app\models\Setting', [
            'name' => $model::NAME
        ]);
    }

    public function testMswdoRequired()
    {
        $model = new PrioritySectorSettingsForm();
        $model->data = '';
        expect_not($model->save());
        expect($model->errors)->hasKey('data');
    }
}