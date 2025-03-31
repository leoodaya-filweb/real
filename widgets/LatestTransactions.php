<?php

namespace app\widgets;

use app\helpers\App;
use app\models\Role;
use app\models\Transaction;

 
class LatestTransactions extends BaseWidget
{
    public $header = 'Latest Transactions';
    public $subHeader = 'Recent Transactions';
    public $transactions;
    public $limit = 5;

    public function init()
    {
        parent::init();

        $user = App::identity();


        switch ($user->role_id) {
            case Role::MSWDO_CLERK:
                $this->transactions = Transaction::find()
                    ->where([
                        'status' => [
                            Transaction::MHO_APPROVED,
                            Transaction::NEW_TRANSACTION,
                            Transaction::MSWDO_CLERK_PROCESSING,
                            Transaction::PAYMENT_COMPLETED,
                        ],
                    ])
                    ->orderBy([
                        'status' => SORT_ASC,
                        'id' => SORT_DESC
                    ])
                    ->limit($this->limit)
                    ->all();
                break;

            case Role::MSWDO_HEAD:
                $this->transactions = Transaction::find()
                    ->where(['status' => [
                        Transaction::NEW_TRANSACTION,
                        Transaction::MSWDO_CLERK_PROCESSING,
                        Transaction::MSWDO_HEAD_PROCESSING,
                        Transaction::MSWDO_CLERK_APPROVED,
                        Transaction::PAYMENT_COMPLETED,
                    ]])
                    ->orderBy([
                        'status' => SORT_ASC,
                        'id' => SORT_DESC
                    ])
                    ->limit($this->limit)
                    ->all();
                break;

            case Role::MHO:
                $this->transactions = Transaction::find()
                    ->where(['status' => Transaction::FOR_WHITE_CARD_CREATION])
                    // ->andWhere(['emergency_welfare_program' => array_keys(App::keyMapParams('emergency_welfare_programs'))])
                    ->orderBy(['id' => SORT_DESC])
                    ->limit($this->limit)
                    ->all();
                $this->header = 'For WhiteCard Creation';
                break;

            case Role::MAYOR:
                $this->transactions = Transaction::find()
                    ->where(['status' => Transaction::MSWDO_HEAD_APPROVED])
                    ->orderBy(['id' => SORT_DESC])
                    ->limit($this->limit)
                    ->all();
                $this->header = 'For Approval Transactions';
                break;

            case Role::BUDGET_OFFICER:
                $this->transactions = Transaction::find()
                    ->where(['status' => Transaction::MAYOR_APPROVED])
                    ->orderBy(['id' => SORT_DESC])
                    ->limit($this->limit)
                    ->all();
                $this->header = 'For Budget Certifications';
                break;

            case Role::ACCOUNTING_OFFICER:
                $this->transactions = Transaction::find()
                    ->where(['status' => Transaction::BUDGET_OFFICER_CERTIFIED])
                    ->orderBy(['id' => SORT_DESC])
                    ->limit($this->limit)
                    ->all();
                $this->header = 'For Accounting Transactions';
                break;
                
            case Role::DISBURSING_OFFICER:
                $this->transactions = Transaction::find()
                    ->where(['status' => Transaction::ACCOUNTING_COMPLETED])
                    ->orderBy(['id' => SORT_DESC])
                    ->limit($this->limit)
                    ->all();
                $this->header = 'For Approval Transactions';
                break;
            
            case Role::TREASURER:
                $this->transactions = Transaction::find()
                    ->where([
                        'status' => Transaction::MSWDO_CLERK_APPROVED,
                        'transaction_type' => Transaction::SENIOR_CITIZEN_ID_APPLICATION
                    ])
                    ->orderBy(['id' => SORT_DESC])
                    ->limit($this->limit)
                    ->all();
                $this->header = 'For Approval Transactions';
                $this->subHeader = 'For Payment Processing';
                break;
            
            default:
                $this->transactions = Transaction::recent($this->limit);
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render("latest-transactions", [
            'transactions' => $this->transactions,
            'header' => $this->header,
            'subHeader' => $this->subHeader,
        ]);
    }
}
