<?php

namespace app\models\form\setting;

use Yii;
use app\helpers\App;
use app\helpers\Url;
use app\models\Setting;
use app\widgets\ReportTemplate;

class ReportTemplateForm extends SettingForm
{
    const NAME = 'report-template';

    public $certificate_of_indigency;
    public $financial_certification;
    public $certificate_of_marriage_counseling;
    public $certificate_of_compliance;
    public $certificate_of_apparent_disability;

    public $social_case_study_report;
    public $white_card;
    public $general_intake_sheet;
    public $obligation_request;
    public $petty_cash_voucher;

    public $senior_citizen_intake_sheet;
    public $social_pension_application_form;

    public $dafac;

    public $senior_citizen_application_form;
    public $solo_parent_application_form;

    public $pyap_form;

    public $pwd_form;

    public $baktom_id;

    public $survey_form;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [
                [
                    'certificate_of_indigency', 
                    'financial_certification', 
                    'social_case_study_report', 
                    'white_card', 
                    'general_intake_sheet', 
                    'obligation_request', 
                    'petty_cash_voucher', 
                    'senior_citizen_intake_sheet', 
                    'social_pension_application_form',
                    'certificate_of_marriage_counseling',
                    'certificate_of_compliance',
                    'certificate_of_apparent_disability',
                    'dafac',
                    'senior_citizen_application_form',
                    'solo_parent_application_form',
                    'pyap_form',
                    'pwd_form',
                    'baktom_id',
                    'survey_form',
                ], 
                'required'
            ],

	        [
                [
                    'certificate_of_indigency', 
                    'financial_certification', 
                    'social_case_study_report', 
                    'white_card', 
                    'general_intake_sheet', 
                    'obligation_request', 
                    'petty_cash_voucher', 
                    'senior_citizen_intake_sheet', 
                    'social_pension_application_form',
                    'certificate_of_marriage_counseling',
                    'certificate_of_compliance',
                    'certificate_of_apparent_disability',
                    'dafac',
                    'senior_citizen_application_form',
                    'solo_parent_application_form',
                    'pyap_form',
                    'pwd_form',
                    'baktom_id',
                    'survey_form',
                ], 
                'string'
            ],
        ];
    }

    public function setData()
    {
        $image = App::setting('image');
        $address = App::setting('address');
        $personnel = App::setting('personnel');

        $replace = [
            '[MUNICIPALITY_LOGO]' => Url::image($image->municipality_logo, ['w' => 150], true),
            '[PRIMARY_LOGO]' => Url::image($image->primary_logo, ['w' => 150], true),
            '[SECONDARY_LOGO]' => Url::image($image->secondary_logo, ['w' => 150], true),
            '[OTHER_LOGO]' => Url::image($image->other_logo, ['w' => 150], true),
            '[REGION_NAME]' => $address->regionName,
            '[PROVINCE_NAME]' => ucwords(strtolower($address->provinceName)),
            '[PN]' => ucwords(strtolower($address->provinceName)),
            '[MN]' => ucwords(strtolower($address->municipalityName)),
            '[MUNICIPALITY_NAME]' => ucwords(strtolower($address->municipalityName)),
            '[SOCIAL_WELFARE_LOGO]' => Url::image($image->social_welfare_logo, ['w' => 150], true),
            '[MSWDO]' => $personnel->mswdo,
            '[MAYOR]' => $personnel->mayor,
            '[MHO]' => $personnel->mho,
            '[BUDGET_OFFICER]' => $personnel->budget_officer,
            '[DISBURSING_OFFICER]' => $personnel->disbursing_officer,
            '[SENIOR_CITIZEN_PRESIDENT]' => $personnel->senior_citizen_president,
            '[OSCA_CHAIRPERSON]' => $personnel->osca_chairperson,
            '[BAKTOM_LOGO]' => Url::image($image->baktom_logo, ['w' => 100], true),

            '[HEADER]' => ReportTemplate::widget([
                'image' => $image,
                'address' => $address,
                'personnel' => $personnel,
            ]),
            '[HEADER_SENIOR_CITIZEN]' => ReportTemplate::widget([
                'image' => $image,
                'address' => $address,
                'personnel' => $personnel,
                'template' => 'header_senior_citizen'
            ]),
            '[HEADER_SOLO_PARENT]' => ReportTemplate::widget([
                'image' => $image,
                'address' => $address,
                'personnel' => $personnel,
                'template' => 'header_solo_parent'
            ]),
            '[HEADER_PYAP]' => ReportTemplate::widget([
                'image' => $image,
                'address' => $address,
                'personnel' => $personnel,
                'template' => 'header_pyap'
            ]),
            '[HEADER_PWD]' => ReportTemplate::widget([
                'image' => $image,
                'address' => $address,
                'personnel' => $personnel,
                'template' => 'header_pwd'
            ]),
            '[FOOTER]' => ReportTemplate::widget([
                'template' => 'footer',
                'image' => $image,
                'address' => $address,
                'personnel' => $personnel,
            ]),
        ];

        $exist = Setting::findByName(self::NAME);
        foreach ($this->attributes as $attribute => $value) {
            if (isset($this->default()[$attribute])) {
                $this->{$attribute} = ($exist)? $value: str_replace(
                    array_keys($replace), 
                    array_values($replace), 
                    $this->default()[$attribute]['default']
                );
            }
        }
    }

    public function default()
    {
        $arr = [];
        foreach ($this->attributes as $attribute => $value) {
            $arr[$attribute] = [
                'name' => $attribute,
                'default' => ReportTemplate::widget([
                    'template' => $attribute
                ])
            ];

            switch ($attribute) {
                case 'certificate_of_indigency':
                case 'financial_certification':
                case 'certificate_of_marriage_counseling':
                case 'certificate_of_compliance':
                case 'certificate_of_apparent_disability':
                case 'senior_citizen_application_form':
                case 'solo_parent_application_form':
                case 'pyap_form':
                case 'pwd_form':
                case 'baktom_id':
                case 'survey_form':
                    $arr[$attribute]['size'] = 'A4';
                    break;
                
                default:
                    $arr[$attribute]['size'] = '8.5in 13in';
                    break;
            }
        }

        return $arr;
    }
}