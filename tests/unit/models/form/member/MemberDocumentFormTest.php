<?php

namespace tests\unit\models\form\member;

use app\models\Member;
use app\models\form\member\MemberDocumentForm;

class MemberDocumentFormTest extends \Codeception\Test\Unit
{
    public function testValidRemove()
    {
        $model = new MemberDocumentForm(['member_id' => 1]);
        $model->file_token = 'default-6ccb4a66-0ca3-46c7-88dd-default';
        $model->scenario = 'remove';
        $model->unlink = false;
        expect_that($model->save());

        $member = $this->tester->grabRecord('app\models\Member', [
            'id' => 1,
        ]);

        expect_that($member);
        expect_not($member->documents);
    }

    public function testValidAdd()
    {
        $model = new MemberDocumentForm(['member_id' => 1]);
        $model->file_token = 'add-6ccb4a66-0ca3-46c7-88dd-add';

        expect_that($model->save());

        $member = $this->tester->grabRecord('app\models\Member', [
            'id' => 1,
        ]);

        expect_that($member);
        expect_that($member->documents);
    }

    public function testMemberIdInvalid()
    {
        $model = new MemberDocumentForm(['member_id' => 'invalid']);
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testMemberIdNotExisting()
    {
        $model = new MemberDocumentForm(['member_id' => 99999999]);
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testFileTokenInvalidOnRemoved()
    {
        $model = new MemberDocumentForm(['member_id' => 1]);
        $model->scenario = 'remove';
        $model->file_token = 'invalid';
        expect_not($model->save());
        expect($model->errors)->hasKey('file_token');
    }
}