<?php

namespace app\models\form\transaction;

use Yii;
use app\helpers\App;
use app\models\Member;
use app\models\Transaction;
use app\models\search\TransactionSearch;
use app\widgets\SocialCaseStudyReport;

class SocialCaseStudyReportForm extends \yii\base\Model
{
    public $member_id;
    public $content;

    public $_member;

    public function rules()
    {
        return [
            [['member_id', 'content',], 'required'],
            ['content', 'string'],
            ['member_id', 'integer'],
            ['member_id', 'exist', 'targetClass' => 'app\models\Member', 'targetAttribute' => 'id'],
        ];
    }

    public function getMember()
    {
        if ($this->_member == null) {
            $this->_member = Member::findOne($this->member_id);
        }

        return $this->_member;
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = new Transaction();
            $transaction->member_id = $this->member_id;
            $transaction->transaction_type = Transaction::SOCIAL_CASE_STUDY_REPORT;
            $transaction->content = $this->content;
            $transaction->remarks = 'Social Case Study Report';
            $transaction->status = Transaction::COMPLETED;
            if ($transaction->save()) {
                return $transaction;
            }
            else {
                $this->addError('transaction', $transaction->errors);
            }
        }
    }

    public function getIndexUrl()
    {
        return (new Transaction())->indexUrl;
    }

    public function init()
    {
        parent::init();
        if (($member = $this->getMember()) != null) {
            $this->content = SocialCaseStudyReport::widget([
                'model' => $member,
                'contentOnly' => true
            ]);
        }
    }

    public function search($params = [])
    {
        $member = $this->getMember();

        $searchModel = new TransactionSearch();
        $searchModel->member_id = $member->id;
        $searchModel->pagination = 10;
        $searchModel->transaction_type = Transaction::SOCIAL_CASE_STUDY_REPORT;
        $dataProvider = $searchModel->search(['TransactionSearch' => $params]);
        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];
    }

    public function grid()
    {
        $data = $this->search();
        return App::controller()
            ->renderPartial('@app/views/transaction/create/_social-case-study-report-grid', [
            'dataProvider' => $data['dataProvider'],
            'searchModel' => $data['searchModel'],
        ]);
    }
}