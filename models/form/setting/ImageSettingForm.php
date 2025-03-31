<?php

namespace app\models\form\setting;

use Yii;

class ImageSettingForm extends SettingForm
{
    const NAME = 'image-settings';
    /* EMAIL */
    public $primary_logo;
    public $secondary_logo;
    public $image_holder;
    public $favicon;
    public $household_map_icon;
    public $id_template;
    public $municipality_logo;
    public $social_welfare_logo;
    public $brand_logo;

    public $other_logo;
    public $senior_citizen_logo;
    public $solo_parent_logo;
    public $pyap_logo;
    public $doh_logo;
    public $baktom_logo;
    
    public $province_logo;
    public $philippines_logo;
    public $footer_image;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['primary_logo', 'secondary_logo', 'image_holder', 'favicon', 'household_map_icon', 'id_template', 'municipality_logo', 'social_welfare_logo', 'other_logo', 'senior_citizen_logo', 'solo_parent_logo', 'pyap_logo', 'doh_logo', 'baktom_logo'], 'string'],

            [['baktom_logo', 'senior_citizen_logo', 'solo_parent_logo', 'pyap_logo', 'primary_logo', 'secondary_logo', 'image_holder', 'favicon', 'household_map_icon', 'id_template', 'municipality_logo', 'social_welfare_logo', 'other_logo', 'brand_logo', 'doh_logo','province_logo','philippines_logo','footer_image'], 'safe'],
        ];
    }

    public function default()
    {
        return [
            'primary_logo' => [
                'name' => 'primary_logo',
                'default' => 'token-primary-logo'
            ],
            'secondary_logo' => [
                'name' => 'secondary_logo',
                'default' => 'token-secondary-logo'
            ],
            'image_holder' => [
                'name' => 'image_holder',
                'default' => 'token-default-image_200'
            ],
            'favicon' => [
                'name' => 'favicon',
                'default' => 'token-default-image_200'
            ],
            'household_map_icon' => [
                'name' => 'household_map_icon',
                'default' => 'token-household-map-icon'
            ],
            'id_template' => [
                'name' => 'id_template',
                'default' => 'token-municipal_id-template'
            ],
            'municipality_logo' => [
                'name' => 'municipality_logo',
                'default' => 'token-municipality-logo'
            ],
            'social_welfare_logo' => [
                'name' => 'social_welfare_logo',
                'default' => 'token-social-welfare-logo'
            ],
            'other_logo' => [
                'name' => 'other_logo',
                'default' => 'token-other-logo'
            ],
            'brand_logo' => [
                'name' => 'brand_logo',
                'default' => 'token-brand-logo'
            ],
            'senior_citizen_logo' => [
                'name' => 'senior_citizen_logo',
                'default' => 'token-senior-citizen-logo'
            ],
            'solo_parent_logo' => [
                'name' => 'solo_parent_logo',
                'default' => 'token-solo-parent-logo'
            ],
            'pyap_logo' => [
                'name' => 'pyap_logo',
                'default' => 'token-pyap-logo'
            ],
            'doh_logo' => [
                'name' => 'doh_logo',
                'default' => 'token-doh-logo'
            ],
            'baktom_logo' => [
                'name' => 'baktom_logo',
                'default' => 'token-baktom-logo'
            ],
            
        ];
    }
}