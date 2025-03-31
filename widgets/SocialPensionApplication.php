<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Member;

class SocialPensionApplication extends BaseWidget
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
            '[PRINTED_NAME]' => strtoupper($this->model->fullname),
            '[EDUCATIONAL_ATTAINMENT]' => $this->model->educationalAttainmentLabel ?: 'N/A',
            '[DATE]' => date('m/d/Y', strtotime($currentDate)),
            '[FULLNAME]'  => ucwords(strtolower($this->model->fullname)),
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
            '[FOUR_PS]' => $this->fourPs
        ];

        $this->content = str_replace(array_keys($replace), array_values($replace), $template->social_pension_application_form);
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

    public function getRecommendedServicesAssistance()
    {
        $transaction = $this->transaction;
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
                                {$transaction->rsa}
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
        if ($transaction 
            && $transaction->client_category 
            && is_array($transaction->client_category)) {
            $tr = '';
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

            return <<< HTML
                <table style="border-collapse: collapse; width: 100%;" border="1">
                    <tbody>
                        {$tr}
                    </tbody>
                </table>
            HTML;
        }
    }

    public function getProblemPresented()
    {
        $transaction = $this->transaction;

        if ($transaction) {
           $replace = [
                '[CLIENT_NAME]' => ucwords(strtolower($transaction->claimant)),
                '[HER/HIS]' => $this->model->isMale ? 'his': 'her',
                '[RELATION]' => $transaction->relation_to_patient,
                '[PATIENT_NAME]' => $transaction->patient_name,
                '[DIAGNOSIS]' => $transaction->diagnosis,
                '[REMARKS]' => $transaction->remarks,
                '[RECOMMENDED_SERVICES_ASSISTANCE]' => strtolower($transaction->recommended_services_assistance),
                '[CAUSED_OF_DEATH]' => $transaction->caused_of_death
            ];

            if ($transaction->isMedicalTransaction) {
                return str_replace(array_keys($replace), array_values($replace), <<< HTML
                    [CLIENT_NAME] sought [RECOMMENDED_SERVICES_ASSISTANCE] from the office for the medications of [HER/HIS] [RELATION] [PATIENT_NAME] due to diagnosed [DIAGNOSIS].
                HTML);
            }

            if ($transaction && $transaction->isDeathAssistance) {
                return str_replace(array_keys($replace), array_values($replace), <<< HTML
                    [CLIENT_NAME] sought [RECOMMENDED_SERVICES_ASSISTANCE] from the office for the burial of [HER/HIS] [RELATION] [PATIENT_NAME] due to [CAUSED_OF_DEATH].
                HTML);
            }
        }
    }

    public function run()
    {
        if ($this->contentOnly) {
            return  $this->content;
        }
        
        return $this->render('social-pension-application/index', [
            'content' => $this->content,
            'model' => $this->model,
        ]);
    }
}
