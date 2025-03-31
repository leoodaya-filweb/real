<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\TechIssue;
use app\models\search\TechIssueSearch;
use app\widgets\ActiveForm;
use app\widgets\Anchors;
use app\widgets\Detail;
use app\widgets\Dropzone;

/* @var $this yii\web\View */
/* @var $model app\models\TechIssue */

$this->title = 'Technical Issue: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Technical Issues', 'url' => App::identity('isDeveloper') ? $model->indexUrl: ['request']];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new TechIssueSearch();
$this->params['showCreateButton'] = true; 
$this->params['wrapCard'] = false;
$tis = App::params('tech_issue_status')[TechIssue::COMPLETED];

$this->addCssFile('css/tech-issue');

$this->registerJs(<<< JS
    $('.change-status').click(function(e) {
        e.preventDefault();
        const el = $(this),
            id = el.data('id'),
            label = el.data('label'),
            modal = $('#modal-change-status'),
            modalTitle = $('.modal-title'),
            statusInp = $('#techissue-status'),
            remarksInp = $('#techissue-remarks'),
            titleValue = 'Change Status to: ' + label;

        modalTitle.html(titleValue);
        statusInp.val(id);
        remarksInp.val(titleValue);

        modal.modal('show');
    });

    $('.btn-save-status').click(function() {
        let form = $('#tech-issue-form');

        KTApp.block('#modal-change-status', {
            overlayColor: '#000000',
            message: 'Submitting...',
            state: 'warning'
        });

        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            method: 'post',
            dataType: 'json',
            success: (s) => {
                if (s.status == 'success') {
                    Swal.fire('Success', 'Successfully changed the status', 'success');
                }
                else {
                    Swal.fire('Error', s.errorSummary, 'error');
                }

                location.reload();
                KTApp.unblock('#modal-change-status');
            },
            error: (e) => {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('#modal-change-status');
            }
        });
    });
JS);

$this->registerJsFile(
    App::publishedUrl('/vue3/vue.global.prod.js', Yii::getAlias('@app/assets')), [

]);
$this->registerJsFile(
    App::publishedUrl('/js/tech-issue.js', Yii::getAlias('@app/assets')), [
    'type' => 'module',
]);
?>
<div class="tech-issue-view-page" id="tech-issue-page" v-cloak data-token="<?= $model->token ?>" data-added_log="<?= $addedLog ? 'addedLog': '' ?>">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= App::if($model->CanClosed, Html::a('Close Issue', ['tech-issue/change-status', 'token' => $model->token], [
        'data-id' => TechIssue::COMPLETED,
        'data-label' => $tis['label'],
        'class' => 'ml-1 change-status btn btn-' . $tis['class'],
    ])) ?>

    <div class="mt-5"></div>
    <div class="row">
        <div class="col-md-6">
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'General Information',
                'stretch' => true
            ]) ?>
                <?= Detail::widget([
                    'model' => $model,
                    'options' => [
                        'class' => 'detail-view table table-bordered table-striped mt-0'
                    ]
                ]) ?>
            <?php $this->endContent() ?>
        </div>
        <div class="col-md-6">
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Logs',
                'stretch' => true
            ]) ?>

                <div class="timeline timeline-3 overflow-auto pr-2" style="height: 65vh" ref="conversationsContainer" @scroll="messageScroll">
                    <div class="timeline-items">
                        <div class="timeline-item" v-for="(log, index) in logs" :key="index" >
                            <div :id="'log-id-' + log.id" ref="logRefs">
                                <div class="timeline-media">
                                    <img :alt="log.createdByEmail" :src="log.creatorImage"/>
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="mr-2">
                                            <a href="#" class="text-dark-75 text-hover-primary font-weight-bold" v-html="log.createdByEmail"></a>
                                            <span class="text-muted ml-2" v-html="log.timeSent"></span>
                                        </div>
                                    </div>
                                    <p class="p-0">
                                        <span v-html="log.remarks"></span>
                                        <div class="mt-3" v-html="log.filePreviews"></div>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div v-if="messageFormState.content.length">
                            <div v-for="(content, index) in messageFormState.content" :key="index" class="timeline-item">
                                <div class="timeline-media">
                                    <img :alt="currentUser.email" :src="currentUser.photoLink"/>
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="mr-2">
                                            <a href="#" class="text-dark-75 text-hover-primary font-weight-bold" v-html="currentUser.email"></a>
                                            <span class="text-muted ml-2"> Sending...</span>
                                        </div>
                                    </div>
                                    <p class="p-0">
                                        <span v-html="content"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-0" v-if="showScrollable">
                    <button @click="scrollToBottom" class="btn btn-outline-primary font-weight-bold btn-sm btn-pill btn-scroller">
                        Scroll to Bottom
                    </button>
                </div>

                <div class="input-group mt-5">
                    <div class="input-group-prepend">
                        <attachment-button :message-form="messageForm" @send-message="saveMessageWithAttachhments"></attachment-button>
                    </div>
                    <textarea placeholder="Enter message here" v-model="messageForm.content" class="form-control" rows="1" @keydown.enter.exact.prevent="handleEnterMessage" @keydown.enter.shift.exact.prevent="messageForm.content += '\n'"></textarea>

                    <div class="input-group-append">
                        <button @click="saveNewMessage()" type="submit" class="btn btn-primary btn-lg text-uppercase font-weight-bold">
                            Send
                        </button>
                    </div>
                </div>
            <?php $this->endContent() ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-change-status" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'tech-issue-form',
                    'action' => ['tech-issue/change-status', 'token' => $model->token]
                ]); ?>
                    <?= $form->field($model, 'remarks')->textarea(['rows' => 10]) ?>

                    <?= Dropzone::widget([
                        'tag' => 'Tech Issue Log',
                        'model' => $model,
                        'attribute' => 'attachments',
                        'inputName' => 'TechIssue[attachments][]'
                    ]) ?>
                    <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success font-weight-bold btn-save-status">Close Issue</button>
            </div>
        </div>
    </div>
</div>