<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Url;
use app\models\File;

class Map extends BaseWidget
{
    public $template = 'household-create';
    public $longitude;
    public $latitude;

    public $api_url;
    public $api_param;
    public $api_key;

    public $api;

    public $onClick;

    public $householdIcon;

    public $apiOnly = false;

    public function init()
    {
        parent::init();

        $this->setDefaults();
    }

    public function setDefaults()
    {
        $map = App::setting('map');

        if ($this->api_url == null) {$this->api_url = $map->url;}
        if ($this->api_param == null) {$this->api_param = $map->param_name;}
        if ($this->api_key == null) {$this->api_key = $map->key;}
        if ($this->longitude == null) {$this->longitude = $map->longitude;}
        if ($this->latitude == null) {$this->latitude = $map->latitude;}

        $this->setHouseholdIcon();
    }

    public function setHouseholdIcon()
    {
        $image = App::setting('image');

        $file = File::findByToken($image->household_map_icon);

        $this->householdIcon = $file ? App::baseUrl($file->location): '';
    }

    public function getApi()
    {
        if ($this->api == null) {
            $this->api = "{$this->api_url}?{$this->api_param}={$this->api_key}&libraries=places";
        }

        return $this->api;
    }

    public function run()
    {

        if ($this->apiOnly) {
            return $this->api;
        }
        
        return $this->render("map/{$this->template}", [
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'onClick' => $this->onClick,
            'householdIcon' => $this->householdIcon,
            'api' => $this->getApi(),
        ]);
    }
}
