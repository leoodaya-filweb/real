<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\models\Household;
use app\models\form\HouseholdSummaryForm;
use yii\helpers\ArrayHelper;

class AddHouseholdController extends Controller 
{
    protected function getModel($no, $step, $action='index')
    {
        $household = Household::findOne(['no' => $no]);
        switch ($step) {
            case 'general-information':
                if ($household) {
                    $model = $household;
                }
                else {
                    $model = new Household();
                    $model->setTheNo();
                }
                $model->scenario = 'manual';
                break;
            case 'map':
              
                if($household) {
                    $model = $household;
                    $model->scenario = 'map';
                }
                else {
                    $model = [
                        'message' => 'Please complete Household information first.',
                        'url' => [$action]
                    ];
                }
                break;
            case 'family-head':
                $model = ($household)? $household->familyHeadForm: [
                    'message' => 'Please complete Household information first.',
                    'url' => [$action]
                ];
                break;
            case 'family-composition':
                if ($household) {
                    if ($household->familyHead) {
                        $model = $household->familyCompositionForm;
                    }
                    else {
                        $model = [
                            'message' => 'Please complete Family head information first.',
                            'url' => [$action, 'no' => $household->no, 'step' => 'family-head']
                        ];
                    }
                }
                else {
                    $model = [
                        'message' => 'Please complete Household information first.',
                        'url' => [$action]
                    ];
                }
                break;

            case 'summary':
                if ($household) {
                    if (($head = $household->familyHead) != null) {
                        $model = new HouseholdSummaryForm([
                            'household_no' => $household->no,
                            'head_id' => $head->id,
                            'members_id' => $household->familyCompositionsId
                        ]);
                    }
                    else {
                        $model = [
                            'message' => 'Please complete Family head information first.',
                            'url' => [$action, 'no' => $household->no, 'step' => 'family-head']
                        ];
                    }
                }
                else {
                    $model = [
                        'message' => 'Please complete Household information first.',
                        'url' => [$action]
                    ];
                }
                break;
            default:
              
                break;
        }

        return $model;
    }

    public function actionIndex($no='', $step='general-information')
    {
        $model = $this->getModel($no, $step);
        $step_forms = Household::STEP_FORM;
        $stepId = ArrayHelper::map($step_forms, 'slug', 'id')[$step];

        if (is_array($model)) {
            App::warning($model['message']);
            return $this->redirect($model['url']);
        }

        if (($post = App::post()) != null) {
            if ($step == 'summary') {
                if ($model->save()) {
                    App::success('Successfully Created');
                    return $this->redirect([
                        'household/view', 
                        'no' => $model->householdNo
                    ]);
                }
                else {
                    App::danger($model->errors);
                    return $this->redirect([
                        'index', 
                        'no' => $model->householdNo, 
                        'step' => $step
                    ]);
                }
            }
            
            if ($model->load($post) && $model->save()) {
                App::success('Successfully Created');
                return $this->redirect([
                    'index', 
                    'no' => $model->householdNo,
                    'step' => ArrayHelper::map($step_forms, 'id', 'slug')[$stepId + 1]
                ]);
            }
        }
        return $this->render('/household/create', [
            'household' => Household::findOne(['no' => $model->householdNo]) ?: new Household(),
            'model' => $model,
            'step' => $stepId,
            'step_forms' => $step_forms,
            'action' => 'index'
        ]);
    }
} 