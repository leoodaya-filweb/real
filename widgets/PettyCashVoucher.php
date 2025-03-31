<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;

class PettyCashVoucher extends BaseWidget
{
    public $transaction;
    public $content;

    public $contentOnly = false;

    public function init()
    {
        parent::init();
        $template = App::setting('reportTemplate');
        $template->setData();
        $member = $this->transaction->member;
        
          $client_category = $this->transaction->client_category;
        $senior=false;
        //Senior citizen
        if(is_array($client_category) && in_array('Senior citizen',$client_category) ){
	     $senior=true;
         }
        

        $profile = App::identity('profile');
        $payee = $profile ? ($profile->fullname ?: '[PAYEE]'): '[PAYEE]';
        
        $replace = [
            '[AMOUNT]' => number_format($this->transaction->amount, 2),
            '[FULLNAME]'  => strtoupper($member->fullnameinitial),
            '[FINANCIAL_ASSISTANCE]' => $member->isSeniorAge || $senior ? 'FINANCIAL ASSISTANCE - SC': 'FINANCIAL ASSISTANCE - AICS',
            '[ADDRESS]'  => 'BRGY. ' . strtoupper($member->barangayName) . ' ' . App::setting('address')->municipalityName . ', ' .  App::setting('address')->provinceName,
        ];

        $this->content = str_replace(array_keys($replace), array_values($replace), $template->petty_cash_voucher);
    }


    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('petty-cash-voucher/index', [
            'content' => $this->content,
            'model' => $this->model,
        ]);
    }
}
