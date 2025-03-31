<?php

namespace app\widgets;

use Yii;
use app\helpers\App;
 
class MemberRecentTransaction extends BaseWidget
{
    public $member;
    public $transactions;

    public function init() 
    {
        // your logic here
        parent::init();

        $this->transactions = $this->transactions ?: $this->member->recentTransactions;
    }
 
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('member-recent-transaction', [
            'member' => $this->member,
            'transactions' => $this->transactions,
        ]);
    }
}
