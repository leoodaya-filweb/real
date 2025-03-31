<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;

class ObligationRequest extends BaseWidget
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
        //'.$deceased->getCurrentAge().'
    
        if($this->transaction->transaction_type==4){
           $deceased =  $this->transaction->deceased;
           
          $deceased?$age=$deceased->getCurrentAge():null;
           
           $label_text = '<br/>Payment for financial death benefits of Realeños in the amount of Php'.number_format($this->transaction->amount, 2);
           $label_text .= '<br/>Name of Deceased: <strong>'.ucwords(strtolower($deceased->fullname?$deceased->fullname:$this->transaction->name_of_deceased)).'</strong> - '.($age?:0).' Years Old, <br/>'.ucfirst($member->address);
           $label_text .= '<br/>Cause of Death: '.$this->transaction->caused_of_death;
           
        }else{
            
            
           // $label_text .= '<br/>Name of Deceased: <strong>'.$this->transaction->patient_name.'</strong> - '.($age?:0).' Years Old, <br/>'.ucfirst($member->address);
            
        }

        $replace = [
            '[AMOUNT]' => number_format($this->transaction->amount, 2),
            '[FULLNAME]'  => ucwords(strtolower($member->fullnameinitial)),
            '[ADDRESS]' => ucfirst($member->address),
            '[ACCOUNT_CODE]' => $member->isSeniorAge || $senior ? '5-02-99-990':'5-02-99-080',
            '[FINANCIAL_ASSISTANCE]' => ($member->isSeniorAge || $senior ? 'FINANCIAL ASSISTANCE - SC': 'FINANCIAL ASSISTANCE - AICS').$label_text,
            '[NO]' => '100-'.date('y-m').'-',
            '[Printed_Name1]' => ($this->transaction->transaction_type==4?'DIANA ABIGAIL DIESTRO-AQUINO':'LEO JAMES M. PORTALES, RSW'),
            '[Position1]' => ($this->transaction->transaction_type==4?'Municipal Mayor':'MSWDO'),
            '[DATE]' => ($this->transaction->transaction_type==4?null:date('m/d/Y') ),
            '[FUND]' => ($this->transaction->transaction_type==4?"Death Benefits of Reale&#241;os": ($member->isSeniorAge || $senior?'Medical Assistance for Senior Citizen': 'AICS')   ),
            '[RCFPP]' => ($this->transaction->transaction_type==4?'1011':'7611'),
            
            
        ];

        $this->content = str_replace(array_keys($replace), array_values($replace), $template->obligation_request);
    }


    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('obligation-request/index', [
            'content' => $this->content,
            'model' => $this->model,
        ]);
    }
}
