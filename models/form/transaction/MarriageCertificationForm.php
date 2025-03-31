<?php

namespace app\models\form\transaction;

use Yii;
use app\helpers\App;
use app\models\Member;
use app\models\Transaction;
use app\models\search\TransactionSearch;
use app\widgets\CertificateOfCompliance;
use app\widgets\CertificateOfMarriageCounseling;

class MarriageCertificationForm extends \yii\base\Model
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
            [['transaction_type'], 'default', 'value' => Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING],
            ['member_id', 'exist', 'targetClass' => 'app\models\Member', 'targetAttribute' => 'id'],
            ['transaction_type', 'in', 'range' => [
                Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING,
                Transaction::CERTIFICATE_OF_COMPLIANCE,
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
        $this->transaction_type = $this->transaction_type ?: Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING;

        if (($member = $this->getMember()) != null) {
            if ($this->transaction_type == Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING) {
                $this->content = CertificateOfMarriageCounseling::widget([
                    'model' => $member,
                    'contentOnly' => true
                ]);
            }

            if ($this->transaction_type == Transaction::CERTIFICATE_OF_COMPLIANCE) {
                $this->content = CertificateOfCompliance::widget([
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
            Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING,
            Transaction::CERTIFICATE_OF_COMPLIANCE,
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

    public function getComc()
    {
        return App::params('transaction_types')[Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING];
    }

    public function getCoc()
    {
        return App::params('transaction_types')[Transaction::CERTIFICATE_OF_COMPLIANCE];
    }
}