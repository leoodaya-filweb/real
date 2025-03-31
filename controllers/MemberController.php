<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\jobs\ImportMemberJob;
use app\models\Event;
use app\models\EventMember;
use app\models\Household;
use app\models\Member;
use app\models\Queue;
use app\models\Transaction;
use app\models\form\BulkImportMemberForm;
use app\models\form\ImportMemberForm;
use app\models\form\TransferToNewHouseholdForm;
use app\models\form\export\ExportPdfForm;
use app\models\form\member\MemberDocumentForm;
use app\models\search\MemberSearch;
use app\models\search\TransactionSearch;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class MemberController extends Controller 
{
    public function actionFindByKeywordsEvent($event_id, $keywords='')
    {
        return $this->asJson(
            Member::findByKeywordsEvent($event_id, $keywords, [
                'h.no', 
                'm.qr_id', 
                'm.last_name', 
                'm.middle_name', 
                'm.first_name',
                'CONCAT_WS(" ", `m`.`first_name`,  `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`,  `m`.`first_name`)',  
                'CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`, `m`.`middle_name`, `m`.`first_name`)',  
                'b.name',
            ])
        );
    }

    public function actionFindNameQrKeywords($keywords='', $suggestionOnly = 'true')
    {
        if ($suggestionOnly == 'true') {
            return $this->asJson(
                Member::findByKeywords($keywords, [
                    'CONCAT_WS(" - ", CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`),`m`.`qr_id`)'
                ])
            );
        }

        $model = Member::find()
            ->where([
                'CONCAT_WS(" - ", CONCAT_WS(" ", first_name, middle_name, last_name), qr_id)' => $keywords
            ])
            ->asArray()
            ->one();

        if ($model) {
            return $this->asJson([
                'status' => 'success',
                'model' => $model
            ]);
        }
        else {
            return $this->asJson([
                'status' => 'failed',
                'error' => 'No member found'
            ]);
        }
    }

    public function actionFindByKeywords($keywords='', $social_pension_status = '')
    {
        return $this->asJson(
            Member::findByKeywords(trim($keywords), [
                'h.no', 
                'm.qr_id', 
                'm.last_name', 
                'm.middle_name', 
                'm.first_name',
                // 'b.name',
                'CONCAT_WS(" ", `m`.`first_name`,  `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`,  `m`.`first_name`)',
                'CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`)',  
                'CONCAT_WS(" ", `m`.`last_name`, `m`.`middle_name`, `m`.`first_name`)',
                'CONCAT_WS(" ", `m`.`last_name`, `m`.`first_name`, `m`.`middle_name`)',  
            ], 10, [
                'social_pension_status' => $social_pension_status,
                'm.new_cbms'=>1
            ])
        );
    }

    public function actionFindByName($keywords='', $type='json')
    {
        if ($type == 'html') {
            $models = Member::find()->alias('m')
               // ->where(['CONCAT_WS(" ", `first_name`, `middle_name`, `last_name`)' => $keywords ]) suffix
               ->andWhere(['or', 
                ['CONCAT_WS(" ", `m`.`first_name`,  `m`.`last_name`)' => $keywords], 
                ['CONCAT_WS(" ", `m`.`last_name`,  `m`.`first_name`)' => $keywords], 
                ['CONCAT_WS(" ", `first_name`, `middle_name`, `last_name`)' => $keywords], 
                ['CONCAT_WS(" ", `first_name`, `suffix`, `middle_name`, `last_name`)' => $keywords], 
                ['CONCAT_WS(" ", `m`.`last_name`, `m`.`middle_name`, `m`.`first_name`)' => $keywords], 
                ['CONCAT_WS(" ", `m`.`last_name`, `m`.`first_name`, `m`.`middle_name`)' => $keywords], 
               ])
                ->andWhere(['new_cbms'=>1])
                ->groupBy('birth_date')
                ->all();

            if ($models) {
                return $this->asJson([
                    'status' => 'success',
                    'multiple' => count($models) > 1,
                    'models' => $models,
                    'model' => $models[0],
                ]);
            }
            else {
                return $this->asJson([
                    'status' => 'failed',
                    'error' => 'No member found'
                ]);
            }
            
        }
        return $this->asJson(
            Member::findByKeywords($keywords, [
                'CONCAT_WS(" ", `m`.`first_name`, `m`.`last_name`)',
                'CONCAT_WS(" ", `m`.`last_name`, `m`.`first_name`)',
                'CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`)',
                'CONCAT_WS(" ", `m`.`last_name`, `m`.`first_name`, `m`.`middle_name`)',
                'CONCAT_WS(" ", `m`.`last_name`, `m`.`middle_name`, `m`.`first_name`)', 
            ], 10, ['social_pension_status' => $social_pension_status,'m.new_cbms' => 1])
        );
    }

    public function actionFindNameByKeywords($keywords='', $social_pension_status='')
    {
        return $this->asJson(
            Member::findByKeywords($keywords, [
                // 'CONCAT_WS(" - ", `h`.`no`,  `m`.`last_name`)',  
                // 'CONCAT_WS(" - ", `h`.`no`,  `m`.`middle_name`)',  
                // 'CONCAT_WS(" - ", `h`.`no`,  `m`.`first_name`)',  

                // 'CONCAT_WS(" - ", `h`.`no`, CONCAT_WS(" ", `m`.`first_name`,  `m`.`last_name`), `m`.`birth_date`)',  
                // 'CONCAT_WS(" - ", `h`.`no`, CONCAT_WS(" ", `m`.`last_name`,  `m`.`first_name`), `m`.`birth_date`)',  
                'CONCAT_WS(" - ", CONCAT_WS(" ", `m`.`first_name`, `m`.`last_name`), `h`.`no`, `m`.`qr_id`)',  
                'CONCAT_WS(" - ", CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`), `h`.`no`, `m`.`qr_id`)',  

                'CONCAT_WS(" - ", CONCAT_WS(" ", `m`.`last_name`, `m`.`middle_name`, `m`.`first_name`), `h`.`no`, `m`.`qr_id`)',  
                'CONCAT_WS(" - ", CONCAT_WS(" ", `m`.`last_name`, `m`.`first_name`), `h`.`no`, `m`.`qr_id`)',  
            ], 10, [
                'social_pension_status' => $social_pension_status,
                'm.new_cbms' => 1
            ])
        );
    }

    /**
     * Lists all Member models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->search(['MemberSearch' => App::queryParams()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Member model.
     * @param integer $qr_id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($qr_id, $tab='overview')
    {
        $model = Member::controllerFind($qr_id, 'qr_id');

        if ($tab == 'transactions') {
            $searchModel = new TransactionSearch([
                'member_id' => $model->id,
            ]);
            $searchModel->pagination = 10;
            $dataProvider = $searchModel->search(['TransactionSearch' => App::queryParams()]);

            $data['searchModel'] = $searchModel;
            $data['dataProvider'] = $dataProvider;
        }

        $data['tab'] = $tab;
        $data['model'] = $model;

        $data['tabData'] = $model::VIEW_TABS[ArrayHelper::map($model::VIEW_TABS, 'slug', 'id')[$tab]];

        return $this->render('view', $data);
    }

    /**
     * Creates a new Member model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Member([
            'pensioner' => Member::NOT_PENSIONER,
            'scenario' => 'family-head',
            'record_status' => Member::RECORD_ACTIVE
        ]);
        // $model->qr_id = $model->createQRId();

        if ($model->load(App::post()) ) {
            
            $model->new_cbms=1;
            if($model->save()) {
            App::success('Successfully Created');
            return $this->redirect($model->viewUrl);
            }
        }

        return $this->render('create', [
            'model' => $model,

        ]);
    }

    /**
     * Duplicates a new Member model.
     * If duplication is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDuplicate($qr_id)
    {
        $originalModel = Member::controllerFind($qr_id, 'qr_id');
        $model = new Member();
        $model->attributes = $originalModel->attributes;

        if ($model->load(App::post()) && $model->save()) {
            App::success('Successfully Duplicated');

            return $this->redirect($model->viewUrl);
        }

        return $this->render('duplicate', [
            'model' => $model,
            'originalModel' => $originalModel,
        ]);
    }

    /**
     * Updates an existing Member model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $qr_id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($qr_id)
    {
        $model = Member::controllerFind($qr_id, 'qr_id');

        if (($post = App::post()) != null) {
            $post['Member']['id_cards'] = $post['Member']['id_cards'] ?? '';

            if ($model->load($post) ) {
                
                $model->living_status==2?$model->record_status=0:null;
                
                if($model->save()){
                App::success('Successfully Updated');
                return $this->redirect($model->viewUrl);
                }
            }
        }
        
        

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Member model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $qr_id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($qr_id)
    {
        $model = Member::controllerFind($qr_id, 'qr_id');

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
        if (($post = App::post()) != null) {
            if (isset($post['process-selected']) && $post['process-selected'] == 'print-id') {
                $this->layout = 'print';
            }
        }

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

    public function actionImport()
    {
        $model = new BulkImportMemberForm();
        $member = new Member();

        if ($model->load(App::post())) {
            if ($model->validate()) {
                Queue::push(new ImportMemberJob([
                    'file_token' => $model->file_token,
                    'user_id' => App::identity('id')
                ]));
                App::success('The member data will be imported in the queue.');
            }
            else {
                App::danger(Html::errorSummary($model));
            }

            return $this->redirect(['import']);
        }

        return $this->render('import', [
            'member' => $member,
            'model' => $model,
        ]);
    }

    public function actionValidateFile($file_token='')
    {
        $model = new BulkImportMemberForm([
            'scenario' => 'contentValidation',
            'file_token' => $file_token
        ]);
        if ($model->validate()) {
            return $this->asJson([
                'status' => 'success',
                'message' => 'Valid'
            ]);
        }
        else {
            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => Html::errorSummary($model)
            ]);
        }
    }

    public function actionDownloadQrCode($qr_id)
    {
        $model = Member::controllerFind($qr_id, 'qr_id');
        $model->downloadQrCode();
    }

    public function actionComputeAge()
    {
        if (($post = App::post()) != null) {
            $model = new Member(['birth_date' => $post['birth_date']]);

            return $this->asJson([
                'status' => 'success',
                'age' => $model->currentAge
            ]);
        }

        return $this->asJson([
            'status' => 'failed'
        ]);
    }

    public function actionFindHouseholdNo($keywords, $html=false)
    {
        if ($html) {
            $arr = explode(' - ', $keywords);

            $model = Household::findOne(['no' => reset($arr)]);

            if($model) {
                return $this->asJson([
                    'status' => 'success',
                    'model' => $model,
                    'qr_id' => (new Member(['household_id' => $model->id]))
                        ->createQRId(),
                    'html' => $this->renderAjax('create/household', [
                        'model' => $model
                    ]),
                    'message' => 'Household found'
                ]);
            }
            else {
                return $this->asJson([
                    'status' => 'failed',
                    'error' => 'No household found'
                ]);
            }
        }


        return $this->asJson(
            Household::findByMemberNoKeywords($keywords, [ 
                'CONCAT_WS(" - ", `h`.`no`, CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`))', 
                'CONCAT_WS(" - ", `h`.`no`, CONCAT_WS(" ", `m`.`first_name`,  `m`.`last_name`))',
                'CONCAT_WS(" - ", `h`.`no`, CONCAT_WS(" ",  `m`.`last_name`, `m`.`first_name`))',
                ])
                
        );
    }

    public function actionFindQrByKeywords($keywords='')
    {
        return $this->asJson(
           // Member::findByKeywords($keywords, ['m.qr_id'])
            
            Member::findByKeywords($keywords, [
                    'CONCAT_WS(" - ", `m`.`qr_id`,CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`))'
                ])
        );
    }

    public function actionDetail($qr_id='')
    {
          
          $result = explode(' - ',$qr_id);   // remove if full name included
          $result[0]?$qr_id=$result[0]:null;
       
       
        
        $model = Member::findOne(['qr_id' => trim($qr_id) ]);

        if ($model) {
            $template = App::get('template') ?: '_detail';
            $transaction = Transaction::findOne(App::get('transaction_id'));
            $event = Event::findOne(App::get('event_id'));
            $eventMember = new eventMember();
            if ($event) {
                $eventMember = EventMember::findOne([
                    'event_id' => $event->id,
                    'member_id' => $model->id,
                ]) ?: $eventMember;
            }
            

            return $this->asJson([
                'status' => 'success',
                'model' => $model,
                'event' => $event,
                'detailView' => $this->renderAjax($template, [
                // 'detailView' => $this->renderPartial($template, [
                    'model' => $model,
                    'event' => $event,
                    'eventMember' => $eventMember,
                    'transaction' => $transaction,
                ])
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'error' => "No Member found using this QR ID .  ".$qr_id.". " . Html::a('Create Here', (new Member())->createUrl),
            'model' => $model
        ]);
    }

    public function actionTransferToNewHousehold($member_id)
    {
        $member = Member::controllerFind($member_id);
        $model = $member->transferToNewHouseholdModel;

        if (App::get('ajaxValidate')) {
            return $this->_ajaxValidate($model);
        }

        if (($post = App::post()) != null) {
            $post['TransferToNewHouseholdForm']['files'] = $post['TransferToNewHouseholdForm']['files'] ?? null;
            if ($model->load($post)) {
                if (($data = $model->save()) != null) {
                    App::success('Transferred to new household.');
                }
                else {
                    App::danger(Html::errorSummary($model));
                }

                return $this->redirect(App::referrer());
                // return $this->redirect($data['member']->viewUrlHouseholdTab);
            }
        }

        return $this->asJson([
            'status' => 'success',
            'form' => $this->renderAjax('_transfer-to-new-household', [
                'model' => $model
            ])
        ]);
    }

    public function actionTransferToExistingHousehold($member_id)
    {
        $member = Member::controllerFind($member_id);
        $model = $member->transferToExistingHouseholdModel;

        if ($model->load(App::post())) {
            if (($data = $model->save()) != null) {
                App::success('Transferred to existing household.');
            }
            else {
                App::danger(Html::errorSummary($model));
            }

            return $this->redirect(App::referrer());
            // return $this->redirect($data['member']->viewUrlHouseholdTab);
        }
    }

    public function actionDetailByName($name='')
    {
        $explodedName = explode(' - ', $name);
        $qr_id = end($explodedName);

        $model = Member::findOne(['qr_id' => $qr_id]);

        if ($model) {
           
            $template = App::get('template') ?: '_detail';
            $event = Event::findOne(App::get('event_id'));
            $eventMember = new eventMember();
            if ($event) {
                $eventMember = EventMember::findOne([
                    'event_id' => $event->id,
                    'member_id' => $model->id,
                ]) ?: $eventMember;
            }

            return $this->asJson([
                'status' => 'success',
                'model' => $model,
                'detailView' => $this->renderAjax($template, [
                // 'detailView' => $this->renderPartial($template, [
                    'model' => $model,
                    'event' => $event,
                    'eventMember' => $eventMember,
                ])
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'error' => "No Member found. Please type another name or household no. Or " . Html::a('Create Here', (new Member())->createUrl),
            'model' => $model
        ]);
    }

    public function actionUploadSeniorCitizenId()
    {
        $response = [];

        if (($post = App::post()) != null) {
            if (isset($post['id']) && isset($post['token'])) {
                if(($model = Member::findone($post['id'])) != null) {
                    $model->senior_citizen_id = $post['token'];

                    if ($model->save()) {
                        $response['status'] = 'success';
                        $response['badge'] = $model->seniorCitizenBagdeStatus;
                        $response['downloadBtn'] = Html::a('Download', $model->downloadSeniorCitizenIdUrl, [
                                'class' => 'btn btn-outline-success font-weight-bolder'
                            ]);
                        $response['viewBtn'] = Html::a('View', $model->viewUrlSeniorCitizenId, [
                                'class' => 'btn btn-outline-primary font-weight-bolder',
                                'target' => '_blank'
                            ]);
                        $response['message'] = 'Uploaded';
                    }
                    else {
                        $response['status'] = 'failed';
                        $response['error'] = $model->errorSummary;
                    }
                }
                else {
                    $response['status'] = 'failed';
                    $response['error'] = 'Member not found';
                }
            }
            else {
                $response['status'] = 'failed';
                $response['error'] = 'post data is incomplete';
            }
        }
        else {
            $response['status'] = 'failed';
            $response['error'] = 'No post data';
        }

        return $this->asJson($response);
    }

    public function actionDownloadSeniorCitizenId($qr_id)
    {
        $model = Member::controllerFind($qr_id, 'qr_id');

        $file = $model->seniorCitizenIdFile;

        $file->download();
    }

    public function actionAddDocument()
    {
        $response = [];

        if (($post = App::post()) != null) {
            $model = new MemberDocumentForm([
                'member_id' => $post['id'] ?? 0,
                'file_token' => $post['token'] ?? '',
                'attribute' => $post['attribute'] ?? 'documents',
            ]);

            if ($model->save()) {
                $response['status'] = 'success';
                $response['message'] = 'Document Added';
                $response['model'] = $model;
                $response['transaction'] = $model->getMember();
                $response['file'] = $model->getFile();
                $response['row'] = $this->renderPartial('/file/_row', [
                    'model' => $model->getFile()
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
            $model = new MemberDocumentForm([
                'member_id' => $post['id'] ?? 0,
                'file_token' => $post['token'] ?? '',
                'attribute' => $post['attribute'] ?? 'documents',
            ]);
            $model->scenario = 'remove';

            if ($model->save()) {
                $response['status'] = 'success';
                $response['message'] = 'Document Removed';
                $response['model'] = $model;
                $response['transaction'] = $model->getMember();
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
}