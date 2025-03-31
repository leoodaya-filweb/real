<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\models\Medicine;
use app\models\Member;
use app\models\Role;
use app\models\Sex;
use app\models\Transaction;
use app\models\form\export\ExportCsvForm;
use app\models\form\export\ExportExcelForm;
use app\models\form\export\ExportPdfForm;
use app\models\form\transaction\CertificationForm;
use app\models\form\transaction\ChangeTransactionStatusForm;
use app\models\form\transaction\DeathAssistanceForm;
use app\models\form\transaction\DocumentForm;
use app\models\form\transaction\EmergencyWelfareProgramForm;
use app\models\form\transaction\GeneralIntakeSheetForm;
use app\models\form\transaction\MarriageCertificationForm;
use app\models\form\transaction\ObligationRequestForm;
use app\models\form\transaction\PettyCashVoucherForm;
use app\models\form\transaction\SeniorCitizenIdForm;
use app\models\form\transaction\SeniorCitizenIntakeSheetForm;
use app\models\form\transaction\SocialCaseStudyReportForm;
use app\models\form\transaction\SocialPensionApplicationForm;
use app\models\form\transaction\SocialPensionForm;
use app\models\form\transaction\WhiteCardForm;
use app\models\search\TransactionSearch;
use app\widgets\TransactionSummaryReport;
use yii\helpers\ArrayHelper;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller 
{
    public function actionFindByKeywords($keywords='')
    {
        return $this->asJson(
            Transaction::findByKeywords($keywords, [
                'm.qr_id',
                'm.first_name',
                'm.middle_name',
                'm.last_name',
                'CONCAT_WS(" ", `m`.`first_name`,  `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`,  `m`.`first_name`)',  
                'CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`, `m`.`middle_name`, `m`.`first_name`)',  
                't.remarks'
            ])
        );
    }

    /**
     * Lists all Transaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search(['TransactionSearch' => App::queryParams()]);
          
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transaction model.
     * @param integer $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($token, $tab='details')
    {
        $model = Transaction::controllerFind($token, 'token');

        $model->process();

        if (App::isAjax()) {
            return $this->asJson([
                'status' => 'success',
                'model' => $model
            ]);
        }

        switch ($model->transaction_type) {
            case Transaction::CERTIFICATE_OF_INDIGENCY:
            case Transaction::FINANCIAL_CERTIFICATION:
            case Transaction::SOCIAL_CASE_STUDY_REPORT:
            case Transaction::CERTIFICATE_OF_APPARENT_DISABILITY:
            case Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING:
            case Transaction::CERTIFICATE_OF_COMPLIANCE:
                $template = 'view/certificate';
                break;

            case Transaction::EMERGENCY_WELFARE_PROGRAM:
                $template = 'view/emergency-welfare-program';
                break;

            case Transaction::DEATH_ASSISTANCE:
                $template = 'view/death-assistance';
                break;

            case Transaction::SENIOR_CITIZEN_ID_APPLICATION:
                $template = 'view/senior-citizen-id-application';
                break;

            case Transaction::SOCIAL_PENSION:
                $template = 'view/social-pension';
                break;
            
            default:
                $template = 'view';
                break;
        }

        return $this->render($template, [
            'model' => $model,
            'tab' => $tab,
        ]);
    }

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($qr_id='', $type='index', $dup='')
    {
        $member = Member::findByQr($qr_id);

        switch ($type) {
            case 'social-case-study-report':
                $model = new SocialCaseStudyReportForm(['member_id' => $member->id]);
                   if($dup){
                       $modelcopy = Transaction::controllerFind($dup, 'token');
                       if($modelcopy){
                        $model->content=$modelcopy->content;
                       }
                   }
                break;

            case 'marriage-certification':
                $model = new MarriageCertificationForm(['member_id' => $member->id]);
                break;

            case 'certification':
                $model = new CertificationForm(['member_id' => $member->id]);
                break;
            
            case 'emergency-welfare-program':
                $model = new EmergencyWelfareProgramForm(['member_id' => $member->id]);
                $model->setTheClaimant();
                break;

            case 'death-assistance':
                $model = new DeathAssistanceForm(['member_id' => $member->id]);
                $model->recommended_services_assistance = Transaction::BURIAL_ASSISTANCE;
                $model->setTheClaimant();
                break;

            case 'senior-citizen-id-application':
                $model = new SeniorCitizenIdForm(['member_id' => $member->id]);
                if (! $model->validate('member_id')) {
                    App::warning(Html::errorSummary($model));
                }
                break;

            case 'social-pension':
                $model = new SocialPensionForm(['member_id' => $member->id]);
                if (! $model->validate('member_id')) {
                    App::warning(Html::errorSummary($model));
                }
                break;

            default:
                $model = new Transaction([
                    'member_id' => $member ? $member->id: 0
                ]);

                break;
        }

        if ($model->load(App::post())) {
            if (($transaction = $model->save()) != null) {

                if (App::isAjax()) {
                    return $this->asJson([
                        'status' => 'success',
                        'model' => $model,
                        'transaction' => $transaction,
                        // 'grid' => (($model->hasMethod('grid'))? $model->grid(): '')
                    ]);
                }
                App::success('Transaction successfully created');

                switch ($type) {
                    case 'emergency-welfare-program':
                    case 'death-assistance':
                    case 'senior-citizen-id-application':
                    case 'social-pension':
                        return $this->redirect($transaction->viewUrl);
                        break;
                    
                    default:
                        return $this->refresh();
                        break;
                }
            }
            else {
                if (App::isAjax()) {
                    return $this->asJson([
                        'status' => 'error',
                        'model' => Html::errorSummary($model),
                    ]);
                }

                App::danger(Html::errorSummary($model));
            }
        }

        return $this->render("create/{$type}", [
            'model' => $model,
            'member' => $member,
            'withSlug' => $member,
            'type' => $type,
        ]);
    }

    /**
     * Updates an existing Transaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($token, $type='index')
    {
        $model = Transaction::controllerFind($token, 'token');

        switch ($type) {

            case 'emergency-welfare-program':
                $model = new EmergencyWelfareProgramForm([
                    'transaction_id' => $model->id,
                    'scenario' => 'update'
                ]);
                break;

            case 'death-assistance':
                $model = new DeathAssistanceForm([
                    'transaction_id' => $model->id,
                    'scenario' => 'update'
                ]);
                break;

            case 'senior-citizen-id-application':
                $model = new SeniorCitizenIdForm([
                    'transaction_id' => $model->id,
                    'scenario' => 'update'
                ]);
                break;

            case 'social-pension':
                $model = new SocialPensionForm([
                    'transaction_id' => $model->id,
                    'scenario' => 'update'
                ]);
                break;

            default:
                // $model = Transaction::findOne($token);
                break;
        }

        if ($model->load(App::post())) {
            $model->client_category = App::post(App::className($model))['client_category'] ?? '';
            if (($transaction = $model->save()) != null) {
                App::success('Transaction successfully updated');

                if (is_object($transaction)) {
                    return $this->redirect($transaction->viewUrl);
                }

                return $this->redirect($model->viewUrl);
            }

            App::danger(Html::errorSummary($model));
        }

        return $this->render("update/{$type}", [
            'model' => $model,
            'member' => $model->member,
            'withSlug' => false,
            'type' => $type,
        ]);
    }

    /**
     * Deletes an existing Transaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
     
    
     public function actionCancel($token)
     {
         $model = Transaction::controllerFind($token, 'token');
          $model->status=29;
         if (($transaction = $model->save()) != null) {
              App::success('Successfully Cancelled');
             $this->redirect(['transaction/index', 'status' => 29]);
         }
       
        return $this->redirect($model->indexUrl);
    } 
     
     
     
    public function actionDelete($token)
    {
        $model = Transaction::controllerFind($token, 'token');

        if($model->delete()) {
            App::success('Successfully Deleted');
        }
        else {
            App::danger(json_encode($model->errors));
        }

        return $this->redirect($model->indexUrl);
    }

    public function actionChangeRecordStatus()
    {
        return $this->changeRecordStatus();
    }

    public function actionBulkAction()
    {
        return $this->bulkAction();
    }

    public function actionPrint()
    {
        return $this->exportPrint();
    }

    public function actionExportPdf()
    {
        return $this->exportPdf();
    }

    public function actionExportCsv()
    {
        return $this->exportCsv();
    }

    // public function _ctionExportXls()
    // {
    //     return $this->exportXls();
    // }

    public function actionExportXlsx()
    {
        return $this->exportXlsx();
    }

    public function actionInActiveData()
    {
        # dont delete; use in condition if user has access to in-active data
    }

    public function actionTransactionType($member_id, $transaction_type)
    {
        $response = [];

        switch ($transaction_type) {
            case Transaction::CERTIFICATE_OF_INDIGENCY:
            case Transaction::FINANCIAL_CERTIFICATION:
            case Transaction::CERTIFICATE_OF_APPARENT_DISABILITY:
                $response['model'] = new CertificationForm([
                    'member_id' => $member_id,
                    'transaction_type' => $transaction_type,
                ]);
                break;

            case Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING:
            case Transaction::CERTIFICATE_OF_COMPLIANCE:
                $response['model'] = new MarriageCertificationForm([
                    'member_id' => $member_id,
                    'transaction_type' => $transaction_type,
                ]);
                break;
            
            default:
                // code...
                break;
        }

        return $this->asJson($response);
    }

    public function actionCreateWhiteCard($transaction_id)
    {
        $model = new WhiteCardForm(['transaction_id' => $transaction_id]);

        if ($model->load(App::post()) && ($transaction = $model->save()) != null) {
            App::success('White Card Created.');
            return $this->redirect($transaction->viewUrlWhiteCard);
        }
        else {
            App::danger(Html::errorSummary($model));
            return $this->redirect(App::referrer());
        }
    }

    public function actionCreateGeneralIntakeSheet($transaction_id)
    {
        $model = new GeneralIntakeSheetForm(['transaction_id' => $transaction_id]);

        if ($model->load(App::post()) && ($transaction = $model->save()) != null) {
            App::success('General Intake Sheet Created.');
            return $this->redirect($transaction->viewUrlGeneralIntakeSheet);
        }
        else {
            App::danger(Html::errorSummary($model));
            return $this->redirect(App::referrer());
        }
    }

    public function actionCreateSocialPensionApplicationForm($transaction_id)
    {
        $model = new SocialPensionApplicationForm(['transaction_id' => $transaction_id]);

        if ($model->load(App::post()) && ($transaction = $model->save()) != null) {
            App::success('Social Pension Application Form Created.');
            return $this->redirect($transaction->viewUrlSocialPensionApplicationForm);
        }
        else {
            App::danger(Html::errorSummary($model));
            return $this->redirect(App::referrer());
        }
    }

    public function actionCreateSeniorCitizenIntakeSheet($transaction_id)
    {
        $model = new SeniorCitizenIntakeSheetForm(['transaction_id' => $transaction_id]);

        if ($model->load(App::post()) && ($transaction = $model->save()) != null) {
            App::success('Intake Sheet Created.');
            return $this->redirect($transaction->viewUrlSeniorCitizenIntakeSheet);
        }
        else {
            App::danger(Html::errorSummary($model));
            return $this->redirect(App::referrer());
        }
    }

    public function actionCreateObligationRequest($transaction_id)
    {
        $model = new ObligationRequestForm(['transaction_id' => $transaction_id]);

        if ($model->load(App::post()) && ($transaction = $model->save()) != null) {
            App::success('Obligation Request Created.');
            return $this->redirect($transaction->viewUrlObligationRequest);
        }
        else {
            App::danger(Html::errorSummary($model));
            return $this->redirect(App::referrer());
        }
    }

    public function actionCreatePettyCashVoucher($transaction_id)
    {
        $model = new PettyCashVoucherForm(['transaction_id' => $transaction_id]);

        if ($model->load(App::post()) && ($transaction = $model->save()) != null) {
            App::success('Petty Cash Voucher Created.');
            return $this->redirect($transaction->viewUrlPettyCashVoucher);
        }
        else {
            App::danger(Html::errorSummary($model));
            return $this->redirect(App::referrer());
        }
    }

    public function actionChangeStatus()
    {
        $response = [];
        $model = new ChangeTransactionStatusForm();

        if ($model->load(App::post()) && ($transaction = $model->save()) != null) {
            $response['status'] = 'success';
            $response['message'] = 'Transaction status successfully changed.';
        }
        else {
            $response['status'] = 'failed';
            $response['error'] = Html::errorSummary($model);
        }
       
        return $this->asJson($response);
    }

    public function actionAddDocument()
    {
        $response = [];

        if (($post = App::post()) != null) {
            $model = new DocumentForm([
                'transaction_id' => $post['id'] ?? 0,
                'file_token' => $post['token'] ?? '',
            ]);

            if ($model->save()) {
                $response['status'] = 'success';
                $response['message'] = 'Document Added';
                $response['model'] = $model;
                $response['transaction'] = $model->getTransaction();
                $response['file'] = $model->getFile();
                $response['row'] = $this->renderPartial('/file/_row', [
                    'model' => $model->getFile()
                ]);
                $response['image'] = $this->renderPartial('/transaction/view/tabs/_document', [
                    'file' => $model->getFile()
                ]);
            }
            else {
                $response['status'] = 'failed';
                $response['error'] = Html::errorSummary($model);
            }
        }
        else {
            $response['status'] = 'failed';
            $response['error'] = 'No post data';
        }

        return $this->asJson($response);
    }

    public function actionRemoveDocument()
    {
        $response = [];

        if (($post = App::post()) != null) {
            $model = new DocumentForm([
                'transaction_id' => $post['id'] ?? 0,
                'file_token' => $post['token'] ?? '',
            ]);
            $model->scenario = 'remove';

            if ($model->save()) {
                $response['status'] = 'success';
                $response['message'] = 'Document Removed';
                $response['model'] = $model;
                $response['transaction'] = $model->getTransaction();
                $response['file'] = $model->getFile();
            }
            else {
                $response['status'] = 'failed';
                $response['error'] = Html::errorSummary($model);
            }
        }
        else {
            $response['status'] = 'failed';
            $response['error'] = 'No post data';
        }

        return $this->asJson($response);
    }


    // const MHO_APPROVED = 2;
    // const MHO_DECLINED = 3;
    // const MSWDO_HEAD_APPROVED = 4;
    // const MSWDO_HEAD_DECLINED = 5;
    // const MAYOR_APPROVED = 6;
    // const MAYOR_DECLINED = 7;
    // const BUDGET_OFFICER_CERTIFIED = 8;
    // const DISBURSED = 9;
    // const COMPLETED = 10;
    // const WHITE_CARD_CREATED = 11;
    // const CERTIFICATE_CREATED = 12;
    // const MSWDO_CLERK_APPROVED = 13;
    // const ACCOUNTING_COMPLETED = 14;
    public function actionCanMhoApproved() {}
    public function actionCanMhoDeclined() {}
    public function actionCanMswdoHeadApproved() {}
    public function actionCanMswdoHeadDeclined() {}
    public function actionCanMayorApproved() {}
    public function actionCanMayorDeclined() {}
    public function actionCanBudgetOfficerCertified() {}
    public function actionCanDisbursed() {}

    public function actionCanCompleted() 
    {
        $response = [];

        if (($post = App::post()) != null) {
            $model = Transaction::controllerFind($post['token'] ?? '', 'token');
            $model->status = Transaction::COMPLETED;
            if ($model->save()) {
                $response['status'] = 'success';
                $response['model'] = $model;
                $response['viewUrlDetails'] = $model->viewUrlDetails;
                $response['message'] = 'Transaction Completed';
            }
            else {
                $response['status'] = 'failed';
                $response['error'] = $model->errorSummary;
            }
        }
        else {
            $response['status'] = 'failed';
            $response['error'] = 'No post data';
        }

        return $this->asJson($response);
    }
    public function actionCanMswdoClerkApproved() {}
    public function actionCanAccountingCompleted() {}
    public function actionCanPaymentCompleted() {}
    public function actionCanIdReleased() {}
    public function actionCanClerkDeclined() {}

    public function actionReviewWhiteCard($token) 
    {
        $model = Transaction::controllerFind($token, 'token');
        $model->white_card_status = Transaction::DOCUMENT_REVIEWED;
        if ($model->save()) {
            App::success('Whitecard Reviewed');
        }
        else {
            App::danger($model->errorSummary);
        }

        return $this->redirect($model->viewUrlGeneralIntakeSheet);
    }

    public function actionApproveWhiteCard($token) 
    {
        $model = Transaction::controllerFind($token, 'token');
        $model->white_card_status = Transaction::DOCUMENT_APPROVED;
        if ($model->save()) {
            App::success('Whitecard Approved');
        }
        else {
            App::danger($model->errorSummary);
        }

        return $this->redirect($model->viewUrlGeneralIntakeSheet);
    }

    public function actionReviewGeneralIntakeSheet($token) 
    {
        $model = Transaction::controllerFind($token, 'token');
        $model->general_intake_sheet_status = Transaction::DOCUMENT_REVIEWED;
        if ($model->save()) {
            App::success('General Intake Sheet Reviewed');
        }
        else {
            App::danger($model->errorSummary);
        }

        return $this->redirect($model->viewUrlObligationRequest);
    }

    public function actionApproveGeneralIntakeSheet($token) 
    {
        $model = Transaction::controllerFind($token, 'token');
        $model->general_intake_sheet_status = Transaction::DOCUMENT_APPROVED;
        if ($model->save()) {
            App::success('General Intake Sheet Approved');
        }
        else {
            App::danger($model->errorSummary);
        }

        return $this->redirect($model->viewUrlObligationRequest);
    }

    public function actionReviewObligationRequestForm($token) 
    {
        $model = Transaction::controllerFind($token, 'token');
        $model->obligation_request_status = Transaction::DOCUMENT_REVIEWED;
        if ($model->save()) {
            App::success('Obligation Request Reviewed');
        }
        else {
            App::danger($model->errorSummary);
        }

        return $this->redirect($model->viewUrlPettyCashVoucher);
    }

    public function actionApproveObligationRequestForm($token) 
    {
        $model = Transaction::controllerFind($token, 'token');
        $model->obligation_request_status = Transaction::DOCUMENT_APPROVED;
        if ($model->save()) {
            App::success('Obligation Request Approved');
        }
        else {
            App::danger($model->errorSummary);
        }

        return $this->redirect($model->viewUrlPettyCashVoucher);
    }

    public function actionReviewPettyCashVoucher($token) 
    {
        $model = Transaction::controllerFind($token, 'token');
        $model->petty_cash_voucher_status = Transaction::DOCUMENT_REVIEWED;
        if ($model->save()) {
            App::success('Petty Cash Voucher Reviewed');
        }
        else {
            App::danger($model->errorSummary);
        }

        return $this->redirect($model->viewUrlDetails);
    }

    public function actionApprovePettyCashVoucher($token) 
    {
        $model = Transaction::controllerFind($token, 'token');
        $model->petty_cash_voucher_status = Transaction::DOCUMENT_APPROVED;
        if ($model->save()) {
            App::success('Petty Cash Voucher Approved');
        }
        else {
            App::danger($model->errorSummary);
        }

        return $this->redirect($model->viewUrlDetails);
    }

    public function actionSummaryReport($date_range)
    {
        $response['summary'] = TransactionSummaryReport::widget([
            'date_range' => $date_range
        ]);

        $response['status'] = 'success';

        return $this->asJson($response);
    }

    public function actionExportSummary($date_range, $type='print')
    {
        switch ($type) {
            case 'print':
                $this->layout = 'print';
                return $this->render('/layouts/_print', [
                    'content' => TransactionSummaryReport::widget([
                        'date_range' => $date_range
                    ])
                ]);
                break;

            case 'pdf':
                $model = new ExportPdfForm([
                    'content' => TransactionSummaryReport::widget([
                        'date_range' => $date_range
                    ])
                ]);
                return $model->export();
                break;

            case 'csv':
                $model = new ExportCsvForm([
                    'content' => TransactionSummaryReport::widget([
                        'date_range' => $date_range,
                        'default' => '-0'
                    ])
                ]);
                return $model->export();
                break;

            case 'xlsx':
                $model = new ExportExcelForm([
                    'content' => TransactionSummaryReport::widget([
                        'date_range' => $date_range,
                        'default' => '-0'
                    ]),
                    'type' => 'xlsx'
                ]);
                return $model->export();
                break;
            
            default:
                // code...
                break;
        }
    }

    public function actionUpdateMedicine($id)
    {
        $model = Medicine::findOne($id);

        if (App::get('ajaxValidate')) {
            return $this->_ajaxValidate($model);
        }

        if ($model->load(App::post()) && $model->save()) {
            if (App::isAjax()) {
                return $this->_ajaxCreated($model);
            }

            App::success('Successfully Created');

            return $this->redirect($model->viewUrl);
        }

        if (App::isAjax()) {
            return $this->_ajaxForm($model, '_medicine-form');
        }
    }

    public function actionLoadMedicines($token)
    {
        $model = Transaction::controllerFind($token, 'token');

        return $this->asjson([
            'status' => 'success',
            'totalMedicinePrice' => Html::number($model->totalMedicinePrice),
            'medicines' => $this->renderPartial('_medicines', [
                'model' => $model
            ])
        ]);
    }

    public function actionAddWhitecardFile()
    {
        $response = [];

        if (($post = App::post()) != null) {
            $model = Transaction::findOne($post['id'] ?? 0);

            if ($model) {
                $model->whitecard_file = $post['token'] ?? '';
                $model->white_card_status = Transaction::DOCUMENT_CLERK_CREATED;


                if ($model->save()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Whitecard Uploaded';
                }
                else {
                    $response['status'] = 'failed';
                    $response['error'] = $model->errorSummary;
                }
            }
            else {
                $response['status'] = 'failed';
                $response['error'] = 'No transaction found.';
            }
        }
        else {
            $response['status'] = 'failed';
            $response['error'] = 'No post data.';
        }

        return $this->asJson($response);
    }

    public function actionUpdateProfile($qr_id, $transaction_type)
    {
        $model = Member::findOne(['qr_id' => $qr_id]);

        if (! $model) {
            App::warning('Member not found in CBMS! Create member first');

            return $this->redirect((new Member())->createUrl);
        }
        $household = $model->household;

        if (($post = App::post()) != null) {
            $post['Household']['files'] = $post['Household']['files'] ?? null;
            $post['Member']['id_cards'] = $post['Member']['id_cards'] ?? null;
            $model->load($post);
            $household->load($post);
            
             $model->living_status==2?$model->record_status=0:null;

            if ($model->save() && $household->save()) {
                App::success('Member Profile Updated');

                // return $this->redirect([
                //     'update-profile', 
                //     'qr_id' => $qr_id, 
                //     'transaction_type' => $transaction_type
                // ]);
                return $this->redirect($model->getCreateTransactionLink($transaction_type));
            }
        }

        return $this->render('update-profile', [
            'model' => $model,
            'household' => $household,
            'transaction' => new Transaction(),
            'transaction_type' => $transaction_type
        ]);
    }
}