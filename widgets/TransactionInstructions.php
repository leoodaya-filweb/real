<?php

namespace app\widgets;

use app\models\Transaction;

class TransactionInstructions extends BaseWidget
{
    public $transaction;
    public $template = 'empty';
    public $button;
    public $checked = '<i class="fa fa-check-circle text-success"></i>';
    public $xmark = '<i class="fa fas fa-times-circle text-warning"></i>';
    public $buttonOptions = [
        'type' => 'button',
        'data-toggle' => 'modal',
        'class' => 'lead font-weight-bold btn btn-outline-white btn-show-instructions btn-icon btn-sm toggle-tooltip',
        'title' => 'Show Instuctions'
    ];
    public $buttonContent = '<i class="far fa-question-circle"></i>';
    public $withAlert = false;
    public $title = 'Instuctions';

    public function init() 
    {
        // your logic here
        parent::init();


        if ($this->withAlert) {
            $this->buttonOptions['id'] = "btn-instructions-{$this->id}";
        }
        else {
            $this->buttonOptions['data-target'] = "#modal-instructions-{$this->id}";
        }

        $transaction = $this->transaction;
        switch ($transaction->status) {
            case Transaction::NEW_TRANSACTION:
            case Transaction::FOR_WHITE_CARD_CREATION:
            case Transaction::MHO_PROCESSING:
            case Transaction::WHITE_CARD_CREATED:
                if ($transaction->isEmergencyWelfareProgram || $transaction->isDeathAssistance) {
                    if ($transaction->isMedical) {
                        $this->template = 'ewp-w-whitecard';
                        $this->title = 'MHO Process';
                    }
                    else {
                        $this->template = 'ewp-no-whitecard';
                        $this->title = 'Clerk Process';
                    }
                }

                if ($transaction->isSeniorCitizenIdApplication || $transaction->isSocialPension) {
                    $this->template = 'id-clerk';
                    $this->title = 'MSWDO Clerk Process';
                }

                break;

            case Transaction::MHO_APPROVED:
            case Transaction::MSWDO_CLERK_PROCESSING:
                if ($transaction->isMedical) {
                    $this->template = 'ewp-w-whitecard';
                }
                else {
                    if ($transaction->isSeniorCitizenIdApplication) {
                        $this->template = 'senior-citizen-id-mswdo-clerk';
                    }
                    else {
                        $this->template = 'ewp-mswdo-clerk';
                    }

                    $this->title = 'MSWDO Clerk Process';
                }
                break;

            case Transaction::MSWDO_CLERK_APPROVED:
            case Transaction::MSWDO_HEAD_PROCESSING:
            case Transaction::TREASURER_PROCESSING:
                if ($transaction->isEmergencyWelfareProgram || $transaction->isDeathAssistance) {
                    $this->template = 'ewp-mswdo-head';
                    $this->title = 'MSWDO Head Process';
                }
                if ($transaction->isSeniorCitizenIdApplication) {
                    $this->template = 'id-treasurer';
                    $this->title = 'Treasurer Process';
                }
                break;

            case Transaction::PAYMENT_COMPLETED:
                $this->template = 'id-releasing';
                $this->title = 'MSWDO Clerk Process';
                break;

            case Transaction::MSWDO_HEAD_APPROVED:
            case Transaction::MAYOR_PROCESSING:
                if ($transaction->isSocialPension) {
                   
                }
                else {
                    $this->template = 'ewp-mayor';
                    $this->title = 'Mayor Process';
                }
                break;

            case Transaction::MAYOR_APPROVED:
            case Transaction::BUDGET_OFFICER_PROCESSING:
                $this->template = 'ewp-budget-officer';
                $this->title = 'Budget Officer Process';
                break;

            case Transaction::BUDGET_OFFICER_CERTIFIED:
            case Transaction::ACCOUNTING_OFFICER_PROCESSING:
                $this->template = 'ewp-accounting-officer';
                $this->title = 'Accounting Officer Process';
                break;

            case Transaction::ACCOUNTING_COMPLETED:
            case Transaction::DISBURSING_OFFICER_PROCESSING:
                $this->template = 'ewp-disbursing-officer';
                $this->title = 'Disbursing Officer Process';
                break;
                
            case Transaction::DISBURSED:
            case Transaction::ACCOUNTING_OFFICER_PROOFING:
                $this->template = 'ewp-accounting-officer-completed';
                $this->title = 'Accounting Officer Process';
                break;

            default:
                // code...
                break;
        }


        if ($transaction->isSocialPension) {
            $this->template = 'social-mswdo-clerk';
        }

        if ($transaction->isCompleted) {
            $this->template = 'empty';
        }

        $this->title = 'Process';

        $this->button = $this->button ?: $this->render('transaction-instructions/button', [
            'transaction' => $this->transaction,
            'widgetId' => $this->id,
            'buttonOptions' => $this->buttonOptions,
            'buttonContent' => $this->buttonContent,
            'template' => $this->template,
            'title' => $this->title,
            'checked' => $this->checked,
            'xmark' => $this->xmark,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->template == 'empty') {
            return ;
        }
        return $this->render('transaction-instructions/index', [
            'transaction' => $this->transaction,
            'template' => $this->template,
            'button' => $this->button,
            'checked' => $this->checked,
            'xmark' => $this->xmark,
            'title' => $this->title,
        ]);
    }
}
