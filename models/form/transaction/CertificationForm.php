<?php

namespace app\models\form\transaction;

use Yii;
use app\helpers\App;
use app\models\Member;
use app\models\Transaction;
use app\models\search\TransactionSearch;
use app\widgets\CertificateOfApparentDisability;
use app\widgets\CertificateOfIndigency;
use app\widgets\FinancialCertification;

class CertificationForm extends \yii\base\Model
{
    public $member_id;
    public $content;
    public $transaction_type;

    public $_member;

    public function rules()
    {
        return [
            [['member_id', 'content', 'transaction_type'], 'required'],
            ['content', 'string'],
            [['member_id', 'transaction_type'], 'integer'],
            [['transaction_type'], 'default', 'value' => Transaction::CERTIFICATE_OF_INDIGENCY],
            ['member_id', 'exist', 'targetClass' => 'app\models\Member', 'targetAttribute' => 'id'],
            ['transaction_type', 'in', 'range' => [
                Transaction::CERTIFICATE_OF_INDIGENCY,
                Transaction::FINANCIAL_CERTIFICATION,
                Transaction::CERTIFICATE_OF_APPARENT_DISABILITY,
            ]]
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
            $transaction->transaction_type = $this->transaction_type;
            $transaction->content = $this->content;
            $transaction->remarks = 'Certification';
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
        $this->transaction_type = $this->transaction_type ?: Transaction::CERTIFICATE_OF_INDIGENCY;

        if (($member = $this->getMember()) != null) {
            if ($this->transaction_type == Transaction::CERTIFICATE_OF_INDIGENCY) {
                $this->content = CertificateOfIndigency::widget([
                    'model' => $member,
                    'contentOnly' => true
                ]);
            }

            if ($this->transaction_type == Transaction::FINANCIAL_CERTIFICATION) {
                $this->content = FinancialCertification::widget([
                    'model' => $member,
                    'contentOnly' => true
                ]);
            }

            if ($this->transaction_type == Transaction::CERTIFICATE_OF_APPARENT_DISABILITY) {
                $this->content = CertificateOfApparentDisability::widget([
                    'model' => $member,
                    'contentOnly' => true
                ]);
            }
        }
    }

    public function search($params = [])
    {
        $member = $this->getMember();

        $searchModel = new TransactionSearch();
        $searchModel->member_id = $member->id;
        $searchModel->pagination = 10;
        $searchModel->transaction_type = [
            Transaction::CERTIFICATE_OF_INDIGENCY,
            Transaction::FINANCIAL_CERTIFICATION,
            Transaction::CERTIFICATE_OF_APPARENT_DISABILITY,
        ];
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
            ->renderPartial('@app/views/transaction/create/_certification-grid', [
            'dataProvider' => $data['dataProvider'],
            'searchModel' => $data['searchModel'],
        ]);
    }

    public function getCoi()
    {
        return App::params('transaction_types')[Transaction::CERTIFICATE_OF_INDIGENCY];
    }

    public function getFc()
    {
        return App::params('transaction_types')[Transaction::FINANCIAL_CERTIFICATION];
    }

    public function getCoad()
    {
        return App::params('transaction_types')[Transaction::CERTIFICATE_OF_APPARENT_DISABILITY];
    }
}