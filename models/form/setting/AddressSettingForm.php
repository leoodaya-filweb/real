<?php

namespace app\models\form\setting;

use Yii;
use app\models\Barangay;
use app\models\Country;
use app\models\Household;
use app\models\Member;
use app\models\Municipality;
use app\models\Province;
use app\models\Region;

class AddressSettingForm extends SettingForm
{
    const NAME = 'address-settings';

    /* EMAIL */
    public $region_id;
    public $province_id;
    public $municipality_id;

    public $_region;
    public $_province;
    public $_municipality;
    public $_barangays;

    public $_totalMembers;
    public $_totalHouseholds;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['region_id', 'province_id', 'municipality_id'], 'required'],
            [['region_id', 'province_id', 'municipality_id'], 'integer'],
            ['region_id', 'exist', 'targetClass' => 'app\models\Region', 'targetAttribute' => 'id'],
            ['province_id', 'exist', 'targetClass' => 'app\models\Province', 'targetAttribute' => 'id'],
            ['municipality_id', 'exist', 'targetClass' => 'app\models\Municipality', 'targetAttribute' => 'id'],

            ['region_id', 'validateRegionId'],
            ['province_id', 'validateProvinceId'],
            ['municipality_id', 'validateMunicipalityId'],
        ];
    }

    public function validateMunicipalityId($attribute, $params)
    {
        $municipalityIds = array_keys(Municipality::dropdown('id', 'id', [
            'province_id' => $this->province_id
        ]));

        if (! in_array($this->municipality_id, $municipalityIds)) {
            $this->addError($attribute, 'Invalid Province');
        }
    }

    public function validateProvinceId($attribute, $params)
    {
        $provinceIds = array_keys(Province::dropdown('id', 'id', [
            'region_id' => $this->region_id
        ]));

        if (! in_array($this->province_id, $provinceIds)) {
            $this->addError($attribute, 'Invalid Province');
        }
    }

    public function validateRegionId($attribute, $params)
    {
        $regionIds = array_keys(Region::dropdown('id', 'id', [
            'country_id' => Country::getPhilippinesId()
        ]));

        if (! in_array($this->region_id, $regionIds)) {
            $this->addError($attribute, 'Invalid Region');
        }
    }

    public function default()
    {
        return [
            'region_id' => [
                'name' => 'region_id',
                'default' => Region::getRegion4aId()
            ],
            'province_id' => [
                'name' => 'province_id',
                'default' => Province::getCalabarzonId()
            ],
            'municipality_id' => [
                'name' => 'municipality_id',
                'default' => Municipality::getRealId()
            ],
        ];
    }

    public function getRegion()
    {
        if ($this->_region == null) {
            $this->_region = Region::findOne($this->region_id);
        }

        return $this->_region;
    }

    public function getRegionName()
    {
        if (($region = $this->region) != null) {
            return $region->name;
        }
    }

    public function getProvince()
    {
        if ($this->_province == null) {
            $this->_province = Province::findOne($this->province_id);
        }

        return $this->_province;
    }

    public function getProvinceName()
    {
        if (($province = $this->province) != null) {
            return $province->name;
        }
    }

    public function getMunicipality()
    {
        if ($this->_municipality == null) {
            $this->_municipality = Municipality::findOne($this->municipality_id);
        }

        return $this->_municipality;
    }

    public function getMunicipalityName()
    {
        if (($municipality = $this->municipality) != null) {
            return $municipality->name;
        }
    }

    public function getBarangays()
    {
        if ($this->_barangays == null) {
            $this->_barangays = Barangay::findAll(['municipality_id' => $this->municipality_id]);
        }
        
        return $this->_barangays;
    }

    public function getHouseholds()
    {
        return Household::find()
            ->where(['municipality_id' => $this->municipality_id])
            ->all();
    }

    public function getTotalHouseholds()
    {
        if ($this->_totalHouseholds === null) {
            $this->_totalHouseholds = Household::find()
                ->where(['municipality_id' => $this->municipality_id])
                ->count();
        }

        return $this->_totalHouseholds;
    }

    public function getMembers()
    {
        return Member::find()
            ->alias('m')
            ->joinWith('household h')
            ->where(['h.municipality_id' => $this->municipality_id])
            ->groupBy('m.id')
            ->all();
    }

    public function getTotalMembers()
    {
        if ($this->_totalMembers === null) {
            $this->_totalMembers = Member::find()
                ->alias('m')
                ->joinWith('household h')
                ->where(['h.municipality_id' => $this->municipality_id])
                ->groupBy('m.id')
                ->count();
        }
        return $this->_totalMembers;
    }
}