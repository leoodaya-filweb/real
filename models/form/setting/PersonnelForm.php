<?php

namespace app\models\form\setting;

use Yii;

class PersonnelForm extends SettingForm
{
    const NAME = 'personnel';

    public $mswdo;
    public $mayor;
    public $mho;
    public $budget_officer;
    public $disbursing_officer;
    public $senior_citizen_president;
    public $osca_chairperson;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['mswdo', 'mayor', 'mho', 'budget_officer', 'disbursing_officer', 'senior_citizen_president', 'osca_chairperson'], 'required'],
            [['mswdo', 'mayor', 'mho', 'budget_officer', 'disbursing_officer', 'senior_citizen_president', 'osca_chairperson'], 'string'],
        ];
    }

    public function default()
    {
        return [
            'mswdo' => [
                'name' => 'mswdo',
                'default' => 'LEO JAMES M. PORTALES, RSW'
            ],
            'mayor' => [
                'name' => 'mayor',
                'default' => 'DIANA ABIGAIL D. AQUINO'
            ],
            'mho' => [
                'name' => 'mho',
                'default' => 'Maricris M. Uy, RN, MD'
            ],
            'budget_officer' => [
                'name' => 'budget_officer',
                'default' => 'CYNTHIA P. DIAMANTE-CPA'
            ],
            'disbursing_officer' => [
                'name' => 'disbursing_officer',
                'default' => 'FE P. CORALDE'
            ],
            'senior_citizen_president' => [
                'name' => 'senior_citizen_president',
                'default' => '[SENIOR_CITIZEN_PRESIDENT]'
            ],
            'osca_chairperson' => [
                'name' => 'osca_chairperson',
                'default' => 'VIRGILIO M. CALZADO'
            ],

        ];
    }
}