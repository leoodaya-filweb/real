<?php

use app\helpers\Html;
use app\models\File;
use app\widgets\Dropzone;
use app\widgets\Webcam;

$member = $model->member;
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
    'title' => 'Senior Citizen ID',
]); ?>
    <div class="row senior-citizen-id-container">
        <div class="col-md-5">
            <?= Webcam::widget([
                'tag' => 'Member',
                'withInput' => false,
                'inputValue' => "{$member->cleanName} Senior Citizen ID",
                'model' => $member,
                'attribute' => 'senior_citizen_id',
                'ajaxSuccess' => <<< JS
                    $('.img-senior-citizen-id').attr('src', s.src);
                    $('.senior-citizen-id-container .model-name-input').val("{$member->cleanName} Senior Citizen ID");
                    KTApp.block('body', {
                        overlayColor: '#000',
                        state: 'warning',
                        message: 'Please wait...'
                    })
                    $.ajax({
                        url: app.baseUrl + 'member/upload-senior-citizen-id',
                        data: {
                            id: {$model->member_id},
                            token: s.file.token
                        },
                        method: 'post',
                        dataType: 'json',
                        success: function(s) {
                            if(s.status == 'success') {
                                Swal.fire({
                                    icon: "success",
                                    title: s.message,
                                    showConfirmButton: false,
                                    timer: 1000
                                });

                                $('li.navi-item[data-key="senior-citizen-id"] span.document-badge').replaceWith(s.badge);
                                $('.btn-ids').html([s.downloadBtn, s.viewBtn].join(' '));
                            }
                            else {
                                Swal.fire('Error', s.error, 'error');
                            }
                            KTApp.unblock('body');
                        },
                        error: function(e) {
                            Swal.fire('Error', e.responseText, 'error');
                            KTApp.unblock('body');
                        }
                    });
                JS
            ]) ?>
           
            <hr>
            <?= Dropzone::widget([
                'model' => $member,
                'tag' => 'Member',
                'title' => 'Upload ID Here',
                'attribute' => 'senior_citizen_id',
                'inputName' => 'hidden',
                'success' => <<< JS
                    $('.img-senior-citizen-id').attr('src', s.src);
                    var self = this;
                    KTApp.block('body', {
                        overlayColor: '#000',
                        state: 'warning',
                        message: 'Please wait...'
                    })
                    $.ajax({
                        url: app.baseUrl + 'member/upload-senior-citizen-id',
                        data: {
                            id: {$model->member_id},
                            token: s.file.token
                        },
                        method: 'post',
                        dataType: 'json',
                        success: function(s) {
                            if(s.status == 'success') {
                                Swal.fire({
                                    icon: "success",
                                    title: s.message,
                                    showConfirmButton: false,
                                    timer: 1000
                                });
                                self.removeFile(file);
                                $('.btn-ids').html([s.downloadBtn, s.viewBtn].join(' '));
                            }
                            else {
                                Swal.fire('Error', s.error, 'error');
                            }
                            KTApp.unblock('body');
                        },
                        error: function(e) {
                            Swal.fire('Error', e.responseText, 'error');
                            KTApp.unblock('body');
                        }
                    });
                JS,
                'maxFiles' => 1,
                'acceptedFiles' => array_map(
                    function($val) { 
                        return ".{$val}"; 
                    }, File::EXTENSIONS['image']
                )
            ]) ?>
        </div>
        <div class="col-md-7">
            <div class="text-center">
                <?= Html::image($member->senior_citizen_id, ['w' => 500], [
                    'class' => 'img-fluid img-senior-citizen-id'
                ]) ?>
                <p class="mt-10 btn-ids">
                    <?= Html::if($member->senior_citizen_id, function() use($member) {
                        return implode(' ', [
                            Html::a('Download', $member->downloadSeniorCitizenIdUrl, [
                                'class' => 'btn btn-outline-success font-weight-bolder'
                            ]),
                            Html::a('View', $member->viewUrlSeniorCitizenId, [
                                'class' => 'btn btn-outline-primary font-weight-bolder',
                                'target' => '_blank'
                            ])
                        ]);
                    }) ?>
                </p>
            </div>
        </div>
    </div>

<?php $this->endContent(); ?>