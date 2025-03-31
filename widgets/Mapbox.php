<?php

namespace app\widgets;

use app\helpers\App;
use app\models\File;

class Mapbox extends BaseWidget
{
    public $access_token = 'pk.eyJ1Ijoicm9lbGZpbHdlYiIsImEiOiJjbGh6am1tankwZzZzM25yczRhMWhhdXRmIn0.aLWnLb36hKDFVFmKsClJkg';
    public $height = '70vh';
    public $lnglat = [121.557727, 14.549610];
    public $lnglat_default = [121.557727, 14.549610];
    public $enableDrawing = false;
    public $enableGeocoder = true;
    public $draggableMarker = true;
    public $enableClick = true;
    public $showMarker = true;
    public $enableNavigationController = false;
    public $mapLoadScript;
    public $initLoadScript;
    public $dataloadingScript;
    public $sourcedataScript;
    public $styleUrl = 'mapbox://styles/roelfilweb/clzw38zek000q01pe80m76cz0'; // 'mapbox://styles/roelfilweb/clzw38zek000q01pe80m76cz0'; //'mapbox://styles/roelfilweb/cljcaizjr004b01qu6lm244pe';
    public $zoom = 10;
    public $pitch=75; // pitch in degrees
    public $bearing= -170; // bearing in degrees
    public $householdIcon = 'https://api.mapbox.com/styles/v1/mapbox/streets-v12/icons/marker-15.png';
    public $onClickScript;
    public $markerDragEndScript;
    public $customContent;

    public function setHouseholdIcon()
    {
        $image = App::setting('image');

        $file = File::findByToken($image->household_map_icon);

        $householdIcon = ($file ? App::baseUrl($file->location): '');

        $this->householdIcon = $householdIcon ?: $this->householdIcon;
    }

    public function init() 
    {
        parent::init();
    }

    public function run()
    {
        
        !$this->lnglat[0] || !$this->lnglat[1]?$this->lnglat=$this->lnglat_default:null;
        
       // return json_encode($this->lnglat);
        
        return $this->render('mapbox/index', [
            'access_token' => $this->access_token,
            'lnglat' => json_encode($this->lnglat),
            'height' => $this->height,
            'enableGeocoder' => $this->enableGeocoder ? 'true': 'false',
            'enableNavigationController' => $this->enableNavigationController ? 'true': 'false',
            'enableDrawing' => $this->enableDrawing ? 'true': 'false',
            'draggableMarker' => $this->draggableMarker ? 'true': 'false',
            'enableClick' => $this->enableClick ? 'true': 'false',
            'showMarker' => $this->showMarker ? 'true': 'false',
            'mapLoadScript' => $this->mapLoadScript,
            'initLoadScript' => $this->initLoadScript,
            'dataloadingScript' => $this->dataloadingScript,
            'sourcedataScript' => $this->sourcedataScript,
            'styleUrl' => $this->styleUrl,
            'zoom' => $this->zoom,
            'pitch'=>$this->pitch,
            'bearing'=>$this->bearing,
            'householdIcon' => $this->householdIcon,
            'onClickScript' => $this->onClickScript,
            'markerDragEndScript' => $this->markerDragEndScript,
            'customContent'=>$this->customContent,
        ]);    
    }
}