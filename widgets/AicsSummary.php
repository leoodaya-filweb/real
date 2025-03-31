<?php

namespace app\widgets;

use app\helpers\App;
use app\models\search\ReportSearch;
 
class AicsSummary extends BaseWidget
{
    public $data;
    public $title;
    public $tableClass;
    public $withHeader = false;
    public $searchModel;
    public $default = 0;

    public $bt = 'border-top: 1px solid transparent !important;';
    public $bb = 'border-bottom: 1px solid transparent !important;';
    public $bl = 'border-left: 1px solid transparent !important;';
    public $br = 'border-right: 1px solid transparent !important;';
    public $bgy = 'background-color: #fff;';
    // public $bgy = 'background-color: #fccc00;';
    public $bgg = 'background-color: none;';
    // public $bgg = 'background-color: #70ad46;';
    public $bggrey = 'background-color: #fff;';
    // public $bggrey = 'background-color: #d9d9d9;';
    public $cgrey = 'color: transparent !important;';
    public $td = 'border: 1px solid #000;padding: 3px 5px;color: #000;';
    public $tc = 'text-align:center;';
    public $fwb = 'font-weight:500;';
    public $fwbr = 'font-weight:600;';
    public $my10 = 'margin: 10px 0px;';
    public $lead = 'font-size: 2rem !important;font-weight: 300;text-transform:uppercase;';
    public $w25p = 'width:25%;';
    public $w5p = 'width:5%;';
    public $tr = 'text-align:right;';

    public function init()
    {
        parent::init();
        $this->searchModel = $this->searchModel ?: new ReportSearch();

        $this->data = $this->data ?: $this->searchModel->aics_data();
    }

    public function run()
    {
        return $this->render('aics-summary', [
            'data' => $this->data,
            'default' => $this->default,
            
            'title' => $this->title,
            'tableClass' => $this->tableClass,
            'searchModel' => $this->searchModel,

            'bt' => $this->bt,
            'bb' => $this->bb,
            'bl' => $this->bl,
            'br' => $this->br,
            'bgy' => $this->bgy,
            'bgg' => $this->bgg,
            'bggrey' => $this->bggrey,
            'cgrey' => $this->cgrey,
            'td' => $this->td,
            'tc' => $this->tc,
            'fwb' => $this->fwb,
            'fwbr' => $this->fwbr,
            'lead' => $this->lead,
            'w25p' => $this->w25p,
            'w5p' => $this->w5p,
            'tr' => $this->tr,

            'medical' => $this->data['medical'],
            'financial' => $this->data['financial'],
            'laboratory_request' => $this->data['laboratory_request'],
            
            'educational_assistance' => $this->data['educational_assistance'],
            'food_assistance' => $this->data['food_assistance'],
            'finacial_and_other_assistance' => $this->data['finacial_and_other_assistance'],

            'bt_bl' => implode('', [$this->bt, $this->bl, $this->td]),
            'tc_fwb' => implode('', [$this->tc, $this->fwb, $this->td]),
            'tc_my10' => implode('', [$this->tc, $this->my10]),
            'lead_fwb' => implode('', [$this->lead, $this->fwb]),
            'bt_br' => implode('', [$this->bt, $this->br, $this->td]),
            'bt_bl_br' => implode('', [$this->bt, $this->bl, $this->br, $this->td]),
            'bt_br_fwb' => implode('', [$this->bt, $this->br, $this->fwb, $this->td]),
            'fwbr_tc_w25p' => implode('', [$this->fwbr, $this->tc, $this->w25p, $this->td]),
            'fwbr_tc' => implode('', [$this->fwbr, $this->tc, $this->td]),
            'fwbr_tc_bgg_w5p' => implode('', [$this->fwbr, $this->tc, $this->bgg, $this->w5p, $this->td]),
            'fwbr_tc_w25p_bgy' => implode('', [$this->fwbr, $this->tc, $this->w25p, $this->bgy, $this->td]),
            'fwbr_tc_bgy' => implode('', [$this->fwbr, $this->tc, $this->bgy, $this->td]),
            'fwbr_tc_bgg_w5p_bgy' => implode('', [$this->fwbr, $this->tc, $this->bgg, $this->w5p, $this->bgy, $this->td]),
            'withHeader' => $this->withHeader
        ]);
    }
}
