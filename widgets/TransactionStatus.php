<?php

namespace app\widgets;

use app\helpers\App;
use app\models\Role;
use app\models\Transaction;
use app\models\form\transaction\ChangeTransactionStatusForm;

class TransactionStatus extends BaseWidget
{
    public $model;
    public $status;

    public $formModel;
    public $template = 'dropdown';
    
    public function init() 
    {
        // your logic here
        parent::init();
        $this->formModel = new ChangeTransactionStatusForm([
            'transaction_id' => $this->model->id
        ]);

        $user = App::identity();
        $status = [];

        $transaction = $this->model;

        switch ($transaction->status) {
            case Transaction::NEW_TRANSACTION:
                  if ($transaction->canCompleted || $this->template == 'separate') {
                    if ($transaction->canCompleted
                        //$user->role_id == Role::MSWDO_CLERK || $user->role_id == Role::MSWDO_HEAD
                    ) {
                        $status[] = Transaction::COMPLETED;
                    }
                    
                    
                 }
                  break;
            case Transaction::FOR_WHITE_CARD_CREATION:
            case Transaction::MHO_PROCESSING:
                if ($this->model->isMedical) {
                    if ($transaction->canMhoApproved || $this->template == 'separate') {
                        if ($user->role_id == Role::MHO) {
                            $status[] = Transaction::MHO_APPROVED;
                        }
                    }

                    if ($transaction->canMhoDeclined || $this->template == 'separate') {
                        if ($user->role_id == Role::MHO) {
                            $status[] = Transaction::MHO_DECLINED;
                        }
                    }
                }
                else {
                    if ($transaction->canMswdoClerkApproved || $this->template == 'separate') {
                        if ($user->role_id == Role::MSWDO_CLERK) {
                            $status[] = Transaction::MSWDO_CLERK_APPROVED;
                        }
                    }
                }
                break;
            case Transaction::WHITE_CARD_CREATED:
                if ($transaction->canMhoApproved || $this->template == 'separate') {
                    if ($user->role_id == Role::MHO) {
                        $status[] = Transaction::MHO_APPROVED;
                    }
                }

                if ($transaction->canMhoDeclined || $this->template == 'separate') {
                    if ($user->role_id == Role::MHO) {
                        $status[] = Transaction::MHO_DECLINED;
                    }
                }
                break;

            case Transaction::MHO_APPROVED:
            case Transaction::MSWDO_CLERK_PROCESSING:
            case Transaction::MSWDO_HEAD_PROCESSING:
                // if ($transaction->canMswdoClerkApproved || $this->template == 'separate') {
                //     if ($user->role_id == Role::MSWDO_CLERK) {
                //         $status[] = Transaction::MSWDO_CLERK_APPROVED;
                //     }
                // }

                if ($transaction->canCompleted || $this->template == 'separate') {
                    if ($transaction->canCompleted
                        //$user->role_id == Role::MSWDO_CLERK || $user->role_id == Role::MSWDO_HEAD
                    ) {
                        $status[] = Transaction::COMPLETED;
                    }
                    
                    
                }

                if ($transaction->canClerkDeclined || $this->template == 'separate') {
                    if (//$user->role_id == Role::MSWDO_CLERK
                     $transaction->canClerkDeclined
                    ) {
                        $status[] = Transaction::MSWDO_CLERK_DECLINED;
                    }
                }

                if ($transaction->canMswdoHeadDeclined || $this->template == 'separate') {
                    if ($user->role_id == Role::MSWDO_HEAD) {
                        $status[] = Transaction::MSWDO_HEAD_DECLINED;
                    }
                }
                break;

            case Transaction::MSWDO_CLERK_APPROVED:
            case Transaction::MSWDO_HEAD_PROCESSING:
            case Transaction::TREASURER_PROCESSING:
                if ($transaction->isSeniorCitizenIdApplication) {
                    if ($transaction->canPaymentCompleted || $this->template == 'separate') {
                        if ($user->role_id == Role::TREASURER) {
                            $status[] = Transaction::PAYMENT_COMPLETED;
                        }
                    }
                }

                if ($transaction->isEmergencyWelfareProgram || $transaction->isSocialPension || $transaction->isDeathAssistance) {
                    if ($transaction->canMswdoHeadApproved || $this->template == 'separate') {
                        if ($user->role_id == Role::MSWDO_HEAD) {
                            $status[] = Transaction::MSWDO_HEAD_APPROVED;
                        }
                    }

                    if ($transaction->canMswdoHeadDeclined || $this->template == 'separate') {
                        if ($user->role_id == Role::MSWDO_HEAD) {
                            $status[] = Transaction::MSWDO_HEAD_DECLINED;
                        }
                    }
                }
                break;

            case Transaction::PAYMENT_COMPLETED:
                if ($transaction->canIdReleased || $this->template == 'separate') {
                    if ($user->role_id == Role::MSWDO_CLERK) {
                        $status[] = Transaction::ID_RELEASED;
                    }
                }
                break;

            case Transaction::MSWDO_HEAD_APPROVED:
            case Transaction::MAYOR_PROCESSING:
                if ($transaction->isSocialPension) {
                    if ($transaction->canMswdoHeadDeclined || $this->template == 'separate') {
                        // $status[] = Transaction::MSWDO_HEAD_DECLINED;
                        $status = [];
                    }
                    else {
                        $status = [];
                    }
                }
                else {
                    if ($transaction->canMayorApproved || $this->template == 'separate') {
                        if ($user->role_id == Role::MAYOR) {
                            $status[] = Transaction::MAYOR_APPROVED;
                        }
                    }

                    if ($transaction->canMayorDeclined || $this->template == 'separate') {
                        if ($user->role_id == Role::MAYOR) {
                            $status[] = Transaction::MAYOR_DECLINED;
                        }
                    }
                }
                break;

            case Transaction::MAYOR_APPROVED:
            case Transaction::BUDGET_OFFICER_PROCESSING:
                if ($transaction->canBudgetOfficerCertified || $this->template == 'separate') {
                    if ($user->role_id == Role::BUDGET_OFFICER) {
                        $status[] = Transaction::BUDGET_OFFICER_CERTIFIED;
                    }
                }
                break;

            case Transaction::BUDGET_OFFICER_CERTIFIED:
            case Transaction::ACCOUNTING_OFFICER_PROCESSING:
                if ($transaction->canAccountingCompleted || $this->template == 'separate') {
                    if ($user->role_id == Role::ACCOUNTING_OFFICER) {
                        $status[] = Transaction::ACCOUNTING_COMPLETED;
                    }
                }
                break;

            case Transaction::ACCOUNTING_COMPLETED:
            case Transaction::DISBURSING_OFFICER_PROCESSING:
                if ($transaction->canDisbursed || $this->template == 'separate') {
                    if ($user->role_id == Role::DISBURSING_OFFICER) {
                        $status[] = Transaction::DISBURSED;
                    }
                }
                break;
                
            case Transaction::DISBURSED:
            case Transaction::ACCOUNTING_OFFICER_PROOFING:
                if ($transaction->canCompleted || $this->template == 'separate') {
                    if ($user->role_id == Role::ACCOUNTING_OFFICER) {
                        $status[] = Transaction::COMPLETED;
                    }
                }
                break;

            default:
                // code...
                break;
        }



        if ($user->role_id == Role::MAYOR) {
            if (! in_array(Transaction::MAYOR_DECLINED, $status)) {
                if ($transaction->status != Transaction::MAYOR_DECLINED) {
                    $status[] = Transaction::MAYOR_DECLINED;
                }
            }
        }

        if (! $status) {
            $this->template = 'blank';

            // $_status = [
            //     Transaction::NEW_TRANSACTION,
            //     Transaction::COMPLETED,
            //     Transaction::ID_RELEASED,
            //     Transaction::SOCIAL_PENSION_RECEIVED,
            // ];
            // if (in_array($transaction->status, $_status)) {
            //     $this->template = 'blank';
            // }
        }

        $this->status = $status;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render("transaction-status/{$this->template}", [
            'model' => $this->model,
            'status' => $this->status,
            'formModel' => $this->formModel,
        ]);
    }
}