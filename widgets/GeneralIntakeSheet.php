<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Member;
use app\models\Transaction;

class GeneralIntakeSheet extends BaseWidget
{
    public $transaction;
    public $model;
    public $content;

    public $contentOnly = false;

    public function init()
    {
        parent::init();
        $template = App::setting('reportTemplate');
        $template->setData();
        $currentDate = App::formatter()->asDateToTimezone();
        
        $replace = [
            '[PROBLEM_PRESENTED]' => $this->problemPresented,
           //'[PREPARED_BY]' => '<strong>'.(App::identity()->id==20 && App::identity()->fullname?App::identity()->fullname:'LEO JAMES M. PORTALES').', RSW</strong><br /> &nbsp; &nbsp; &nbsp; &nbsp;'.(App::identity()->id==20 && App::identity()->profile->position?App::identity()->profile->position:'&nbsp; &nbsp; MSWDO'),
            '[PREPARED_BY]' => '<strong>LEO JAMES M. PORTALES, RSW</strong><br /> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; MSWDO',
            '[PRINTED_NAME]' => strtoupper($this->model->fullnameinitial),
            '[EDUCATIONAL_ATTAINMENT]' => $this->model->educationalAttainmentLabel ?: 'N/A',
            '[DATE]' => date('m/d/Y', strtotime($currentDate)),
            '[FULLNAME]'  => ucwords(strtolower($this->model->fullnameinitial)), //ucwords(strtolower($this->model->fullname)),
            '[AGE]' => $this->model->currentAge,
            '[HE/SHE]' => $this->model->isMale ? 'He': 'She',
            '[OCCUPATION]' => ucwords(strtolower($this->model->occupation)) ?: '[OCCUPATION]',
            '[SEX]' => $this->model->genderName,
            '[DATE_OF_BIRTH]' => $this->model->birthDate,
            '[PLACE_OF_BIRTH]' => $this->model->birth_place ?: 'Not Set',
            '[ADDRESS]' => ucfirst($this->model->address),
            '[CIVIL_STATUS]' => $this->model->civilStatusName,
            '[OCCUPATION]' => $this->model->occupation ?: 'None',
            '[MONTHLY_INCOME]' => $this->model->monthlyIncome,
            '[INCOME]' => $this->model->monthlyIncome,
            '[CP_NO]' => $this->model->contact_no ?: 'None',
            '[FAMILY_COMPOSITION]' => $this->render('general-intake-sheet/family-composition', [
                'model' => $this->model
            ]),
            '[CLIENT_CATEGORY]' => $this->clientCategory,
            '[RECOMMENDED_SERVICES_ASSISTANCE]' => $this->recommendedServicesAssistance,
            '[SKILLS]' => $this->model->skills ? implode(', ', $this->model->skills): 'None',
            '[FOUR_PS]' => $this->fourPs,
            '[SECTOR]' => $this->sector
        ];

        $this->content = str_replace(array_keys($replace), array_values($replace), $template->general_intake_sheet);
    }

    public function getFourPs()
    {
        if ($this->model->fourPs == Member::YES_4PS) {
            return <<< HTML
                <label>
                    <input type="checkbox"> No
                </label>
                <label>
                    <input type="checkbox" checked> Yes
                </label>
            HTML;
        }
        else {
            return <<< HTML
                <label>
                    <input type="checkbox" checked> No
                </label>
                <label>
                    <input type="checkbox"> Yes
                </label>
            HTML;
        }
    }
    
    
    
     public function getSector()
    {
          $client_category = $this->transaction->client_category;
       // ["Person with disability","Senior citizen","Solo Parent"]

          $output='<label>
                    <input type="checkbox" '.(is_array($client_category) && in_array("Person with disability", $client_category)?'checked':null).' > PWD
                </label>
                <label>
                    <input type="checkbox" '.(is_array($client_category) && in_array("Senior citizen", $client_category)?'checked':null).' > Senior Citizen
                </label>
                <label>
                    <input type="checkbox" '.(is_array($client_category) && in_array("Solo Parent", $client_category)?'checked':null).' > Solo Parent
                </label>';
                
            return $output;

    }
    

   public function getRecommendedServicesAssistance()
    {
        $transaction = $this->transaction;
        $transaction->amount>0?$amount=  ' - P'.number_format($transaction->amount, 2, '.', ','):null;
       
        
        if ($transaction && $transaction->recommended_services_assistance) {
            return <<< HTML
                <table style="border-collapse: collapse; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 3.92928%;">
                                <label>
                                    <input type="checkbox" checked="checked">
                                </label>
                            </td>
                            <td style="width: 106.758%;font-size: 13pt;">
                                {$transaction->rsa}{$amount}
                            </td>
                        </tr>
                    </tbody>
                </table>
            HTML;
        }
    }

    public function getClientCategory()
    {
        $transaction = $this->transaction;
        $tr = '';
        if ($transaction 
            && $transaction->client_category 
            && is_array($transaction->client_category)) {
            foreach ($transaction->client_category as $key => $category) {
                $tr .= <<< HTML
                    <tr>
                        <td style="width: 3.92928%;">
                            <label>
                                <input type="checkbox" checked="checked">
                            </label>
                        </td>
                        <td style="width: 106.758%;font-size: 13pt;">
                            {$category}
                        </td>
                    </tr>
                HTML;
            }
        }

        if ($tr == null) {
            $tr = <<< HTML
                <tr>
                    <td style="width: 3.92928%;">
                        <label>
                            <input type="checkbox" checked="checked">
                        </label>
                    </td>
                    <td style="width: 106.758%;font-size: 13pt;">
                        None
                    </td>
                </tr>
            HTML;
        }

        return <<< HTML
            <table style="border-collapse: collapse; width: 100%;" border="1">
                <tbody>
                    {$tr}
                </tbody>
            </table>
        HTML;
    }

    public function getProblemPresented()
    {
        $transaction = $this->transaction;

        if ($transaction) {
            $claimant = $this->model;
            $patient = $transaction->patient;

            $claimant_his_her = ($claimant->isMale)? 'his': 'her';
            $claimant_he_she = ($claimant->isMale)? 'he': 'she';

            $patient_he_she = '';
            $patient_his_her = '';

            if ($transaction->relation_type == Transaction::CLIENT_IS_PATIENT) {
                if ($patient) {
                    $patient_he_she = ($patient->isMale)? 'he': 'she';
                    $patient_his_her = ($patient->isMale)? 'his': 'her';
                }
            }
            else {
                $patient_he_she = ($claimant->isMale)? 'he': 'she';
                $patient_his_her = ($claimant->isMale)? 'his': 'her';
            }

            $replace = [
                '[RELATION]' => strtolower($transaction->relation_to_patient ?: ''),
                '[CLAIMANT_HIS_HER]' => $claimant_his_her,
                '[PATIENT_HE_SHE]' => $patient_he_she,
                '[DIAGNOSIS]' => $transaction->diagnosis,
                '[PATIENT_HIS_HER]' => $patient_his_her,
                '[MEDICINES]' => $transaction->medicineTags,
                '[MEDICAL_PROCEDURE]' => $transaction->medical_procedure_requested,
                '[LABORATORY_PROCEDURE]' => $transaction->laboratory_procedure_requested,
                '[DESTINATION]' => $transaction->destination,
                '[CAUSED_OF_DEATH]' => $transaction->caused_of_death,
            ];

            $content = '';
            if ($transaction) {
                if ($transaction->relation_type == Transaction::CLIENT_IS_PATIENT) {
                    $replace['[PATIENT_HE_SHE]'] = $claimant_he_she;
                    $replace['[PATIENT_HIS_HER]'] = $claimant_his_her;
                }

                if ($transaction->isMedicalTransaction) {
                    switch ($transaction->emergency_welfare_program) {
                        case Transaction::AICS_MEDICAL_MEDICINE:
                            $content = "Client sought financial assistance from the office to support [CLAIMANT_HIS_HER] [RELATION] medical expenses. Accordingly, [PATIENT_HE_SHE] is suffering from [DIAGNOSIS]. Thus [PATIENT_HE_SHE] was advised by the physician to take [MEDICINES]. However, [PATIENT_HIS_HER] very limited resources could not sustain [PATIENT_HIS_HER] medical needs.";
                            break;

                        case Transaction::AICS_MEDICAL:
                            $content = "Client sought financial assistance from the office to support [CLAIMANT_HIS_HER] [RELATION] medical expenses. Accordingly, [PATIENT_HE_SHE] is suffering from [DIAGNOSIS]. Thus [PATIENT_HE_SHE] was advised by the physician to undergo [MEDICAL_PROCEDURE]. However, [PATIENT_HIS_HER] very limited resources could not sustain [PATIENT_HIS_HER] medical needs.";
                            break;

                        case Transaction::AICS_LABORATORY_REQUEST:
                            $content = "Client sought financial assistance from the office to support [CLAIMANT_HIS_HER] [RELATION] medical expenses. Accordingly, [PATIENT_HE_SHE] is suffering from [DIAGNOSIS]. Thus [PATIENT_HE_SHE] was advised by the physician to undergo [LABORATORY_PROCEDURE]. However, [PATIENT_HIS_HER] very limited resources could not sustain [PATIENT_HIS_HER] medical needs.";
                            break;
                        
                        default:
                            $content = "Client sought financial assistance from the office to support [CLAIMANT_HIS_HER] [RELATION] medical expenses. Accordingly, [PATIENT_HE_SHE] is suffering from [DIAGNOSIS]. Thus [PATIENT_HE_SHE] was advised by the physician to take the necessary medications. However, [PATIENT_HIS_HER] very limited resources could not sustain [PATIENT_HIS_HER] medical needs.";
                            break;
                    }
                }
                elseif ($transaction->emergency_welfare_program == Transaction::BALIK_PROBINSYA_PROGRAM) {
                    $content = "Client sought financial assistance from the office for their transportation expense to go back to [DESTINATION].  The client is hoping to avail of the Balik-Probinsya Program to return and settle in [DESTINATION] for good. However, their very limited resources could not provide for their transportation expenses.";
                }
                else {
                    if ($transaction->isDeathAssistance) {
                        $content = "Client sought financial assistance from the office to support [CLAIMANT_HIS_HER] [RELATION] burial expenses. Accordingly, the patient died due to [CAUSED_OF_DEATH].";
                    }
                }
            }

            return str_replace(array_keys($replace), array_values($replace), $content);
        }
    }


    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('general-intake-sheet/index', [
            'content' => $this->content,
            'model' => $this->model,
        ]);
    }
}
