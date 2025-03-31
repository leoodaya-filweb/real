<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;
use yii\helpers\Inflector;

class PWDApplicationForm extends BaseWidget
{
    public $model;
    public $content;
    public $contentOnly = false;

    public function init()
    {
        parent::init();
        $template = App::setting('reportTemplate');
        $address = App::setting('address');
        $template->setData();

        $database = $this->model;

        $replace = [
            '[PWD_ID_NO]' => $database->sector_id,
            '[DATE_APPLIED]' => $database->date_of_application,
            '[PHOTO]' => Html::image($database->photo, ['w' => 200], [
                'style' => 'width: 100%;height:auto'
            ]),
            '[LAST_NAME]' => $database->last_name,
            '[FIRST_NAME]' => $database->first_name,
            '[MIDDLE_NAME]' => $database->middle_name,
            '[SUFFIX]' => $database->name_suffix,
            '[BIRTHDATE]' => $database->date_of_birth,
            '[HOUSE_NO]' => $database->house_no,
            '[STREET]' => $database->street,
            '[BARANGAY]' => $database->barangay,
            '[MUNICIPALITY]' => $database->municipality,
            '[PROVINCE]' => $address->provinceName,
            '[REGION]' => $address->regionName,
            '[LANDLINE_NO]' => $database->other_contact_no,
            '[MOBILE_NO]' => $database->contact_no,
            '[EMAIL]' => $database->email,
            '[ORGANIZATION_AFFILIATED]' => $database->org_affiliated,
            '[CONTACT_PERSON]' => $database->org_contact_person,
            '[OFFICE_ADDRESS]' => $database->org_office_address,
            '[TEL_NO]' => $database->org_tel_no,
            '[SSS]' => $database->sss_no,
            '[GSIS]' => $database->gsis_no,
            '[OFFICE_ADDRESS]' => $database->pagibig_no,
            '[PSN_NO]' => $database->psn_no,
            '[PHILHEALTH_NO]' => $database->philhealth_no,
            '[F_LAST_NAME]' => $database->father_lastname,
            '[F_FIRST_NAME]' => $database->father_firstname,
            '[F_MIDDLE_NAME]' => $database->father_middlename,
            '[M_LAST_NAME]' => $database->mother_lastname,
            '[M_FIRST_NAME]' => $database->mother_firstname,
            '[M_MIDDLE_NAME]' => $database->mother_middlename,
            '[G_LAST_NAME]' => $database->guardian_lastname,
            '[G_FIRST_NAME]' => $database->guardian_firstname,
            '[G_MIDDLE_NAME]' => $database->guardian_middlename,
            '[AG_LAST_NAME]' => $database->guardian_lastname,
            '[AG_FIRST_NAME]' => $database->guardian_firstname,
            '[AG_MIDDLE_NAME]' => $database->guardian_middlename,
            '[A_LAST_NAME]' => $database->last_name,
            '[A_FIRST_NAME]' => $database->first_name,
            '[A_MIDDLE_NAME]' => $database->middle_name,
            '[R_LAST_NAME]' => $database->representative_lastname,
            '[R_FIRST_NAME]' => $database->representative_firstname,
            '[R_MIDDLE_NAME]' => $database->representative_middlename,
            '[LICENSE_NO]' => $database->license_no,
            '[P_LAST_NAME]' => $database->certifying_physician_lastname,
            '[P_FIRST_NAME]' => $database->certifying_physician_firstname,
            '[P_MIDDLE_NAME]' => $database->certifying_physician_middlename,
            '[PO_LAST_NAME]' => $database->processing_officer_lastname,
            '[PO_FIRST_NAME]' => $database->processing_officer_firstname,
            '[PO_MIDDLE_NAME]' => $database->processing_officer_middlename,
            '[AO_LAST_NAME]' => $database->approving_officer_lastname,
            '[AO_FIRST_NAME]' => $database->approving_officer_firstname,
            '[AO_MIDDLE_NAME]' => $database->approving_officer_middlename,
            '[E_LAST_NAME]' => $database->encoder_lastname,
            '[E_FIRST_NAME]' => $database->encoder_firstname,
            '[E_MIDDLE_NAME]' => $database->encoder_middlename,
            '[REPORTING_UNIT]' => $database->reporting_unit,
            '[CONTROL_NO]' => $database->control_no,
            '[REVISED_DATE]' => App::formatter()->asDateToTimezone($database->updated_at, 'F d, Y'),
        ];

        $replace = $this->replace($replace, 'pwd_type');
        $replace = $this->replace($replace, 'gender');
        $replace = $this->replace($replace, 'civil_status');
        $replace = $this->replace($replace, 'educ_attainment');
        $replace = $this->replace($replace, 'status_of_employment');
        $replace = $this->replace($replace, 'types_of_employment');
        $replace = $this->replace($replace, 'category_of_employment');
        $replace = $this->replace($replace, 'accomplished_by');


        if (in_array($database->occupation, App::params('pwd_form')['occupation'])) {
            $replace = $this->replace($replace, 'occupation');
        }
        else {
            $replace = $this->replace($replace, 'occupation', 'others');
            $replace['[OTHERS]'] = $database->occupation;
        }

        foreach ($database->pwd_type_of_disability as $disability) {
            $replace = $this->replace($replace, 'type_of_disability', $disability);
        }

        foreach ($database->cause_of_disability as $key => $cause) {
            if (is_array($cause)) {
                foreach ($cause as $c) {
                    $replace = $this->replace($replace, 'cause_of_disability', implode('-', [
                        Inflector::slug($key),
                        Inflector::slug($c)
                    ]));
                }
            }
        }

        $this->content = str_replace(
            array_keys($replace), 
            array_values($replace), 
            $template->pwd_form
        );
    }


    public function replace($replace, $attr, $value='')
    {
        $slug = Inflector::slug($value ?: $this->model->{$attr});

        $replace['id="'. $attr .'-'.$slug.'"'] = 'id="'. $attr .'-'.$slug.'" checked';

        return $replace;
    }

    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('pwd-application-form', [
            'content' => $this->content,
            'model' => $this->model,
        ]);
    }
}
