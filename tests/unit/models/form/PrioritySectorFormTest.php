<?php

namespace tests\unit\models\form;

use app\helpers\App;
use app\helpers\ArrayHelper;
use app\models\form\PrioritySectorForm;
use app\models\form\setting\PrioritySectorSettingsForm;
use app\models\Database;


class PrioritySectorFormTest extends \Codeception\Test\Unit
{
    private function data($replace=[])
    {
        return array_replace([
            'id' => 99,
            'code' => 'code',
            'label' => 'label',
            'class' => 'class',
        ], $replace);
    }

    public function testAddNewSector()
    {
        $model = new PrioritySectorForm($this->data());
        expect_that($model->save());
        
        $ps = new PrioritySectorSettingsForm();
        $data = ArrayHelper::index($ps->data, 'id');

        expect_that(in_array($model->id, array_keys($data)));
    }

    public function testUpdateSector()
    {
        $model = new PrioritySectorForm($this->data([
            'code' => 'code_update',
            'label' => 'label_update',
            'class' => 'class_update',
        ]));
        $model->id = Database::BAKTOM_ID;
        expect_that($model->save());
        
        $ps = new PrioritySectorSettingsForm();
        $data = ArrayHelper::index($ps->data, 'id');

        expect_that(in_array($model->id, array_keys($data)));
        expect($data[$model->id]['code'])->equals('code_update');
        expect($data[$model->id]['label'])->equals('label_update');
        expect($data[$model->id]['class'])->equals('class_update');
    }

    public function testDeleteSector()
    {
        $this->testAddNewSector();

        $model = new PrioritySectorForm(['id' => 99]);
        expect_that($model->delete());

        $ps = new PrioritySectorSettingsForm();
        $data = ArrayHelper::index($ps->data, 'id');

        expect_not(in_array($model->id, array_keys($data)));
    }

    public function testDeleteInvalid()
    {
        $model = new PrioritySectorForm(['id' => Database::SC_ID]);

        expect_not($model->delete());

        $ps = new PrioritySectorSettingsForm();
        $data = ArrayHelper::index($ps->data, 'id');

        expect_that(in_array($model->id, array_keys($data)));
    }
}