<?php

use app\helpers\Html;
use app\helpers\Url;

$this->registerJs(<<< JS
    $('.btn-update-member').on('click', function() {
        var id = $(this).data('key');
        var self = this;

        KTApp.blockPage({
            overlayColor: '#000000',
            state: 'warning',
            message: 'Loading Form...'
        });

        $.ajax({
            url: app.baseUrl + 'household/update-member',
            data: {id: id},
            dataType: 'json',
            success: function(s) {
                if(s.status == 'success') {
                    $('#modal-family-composition .modal-body').html(s.form);
                    $('#modal-family-composition').modal('show');
                    $('.kt-selectpicker').selectpicker();
                    $('input[datepicker="true"]').datepicker({
                        rtl: KTUtil.isRTL(),
                        todayHighlight: true,
                        orientation: "bottom left",
                        templates: {
                            leftArrow: '<i class="la la-angle-right"></i>',
                            rightArrow: '<i class="la la-angle-left"></i>'
                        }
                    });
                }
                else {
                    Swal.fire("Error!", s.error, "error");
                }
                KTApp.unblockPage();
                $('[data-toggle="popover"]').popover();
            },
            error: function(e) {
                Swal.fire("Error!", e.responseText, "error");
                KTApp.unblockPage();
            }
        });
    });

    $('.btn-delete-member').on('click', function() {
        var id = $(this).data('key');
        var self = this;

        Swal.fire({
            title: "Delete Member?",
            text: "You won\"t be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        }).then(function(result) {
            if (result.value) {
                KTApp.blockPage({
                    overlayColor: '#000000',
                    state: 'warning',
                    message: 'Processing...'
                });
                $.ajax({
                    url: app.baseUrl + 'household/delete-member',
                    data: {id: id},
                    dataType: 'json',
                    success: function(s) {
                        if(s.status == 'success') {
                            Swal.fire("Deleted!", s.message, "success");
                            $(self).closest('.member-container-column').remove();
                        }
                        else {
                            Swal.fire("Error!", s.error, "error");
                        }

                        KTApp.unblockPage();
                    },
                    error: function(e) {
                        Swal.fire("Error!", e.responseText, "error");
                        KTApp.unblockPage();
                    }
                });
            }
        });
    });
JS);
?>

<div class="col-md-4 member-container-column">
    <div class="card card-custom gutter-b app-border">
        <div class="card-body pt-4">
            <div class="d-flex justify-content-end">
                <div class="dropdown dropdown-inline">
                    <a href="#" class="btn btn-icon-primary btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="svg-icon svg-icon-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1">
                                    <rect x="14" y="9" width="6" height="6" rx="3" fill="black"></rect>
                                    <rect x="3" y="9" width="6" height="6" rx="3" fill="black" fill-opacity="0.7"></rect>
                                </g>
                            </svg>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right" x-placement="bottom-end" style="white-space: nowrap;width: max-content;">
                        <ul class="navi navi-hover py-5">
                            <li class="navi-item" data-toggle="tooltip" title="Download QR Code" data-placement="left" data-theme="dark">
                                <a href="<?= $model->downloadQrCodeUrl ?>" class="navi-link">
                                    <span class="navi-icon">
                                       <i class="fas fa-qrcode"></i>
                                    </span>
                                    <span class="navi-text font-weight-bolder">
                                        QR Code
                                    </span>
                                </a>
                            </li>
                            <li class="navi-item" data-toggle="tooltip" title="View Complete Profile" data-placement="left" data-theme="dark">
                                <a href="<?= $model->viewUrl ?>" class="navi-link" target="_blank">
                                    <span class="navi-icon">
                                       <i class="far fa-user-circle"></i>
                                    </span>
                                    <span class="navi-text font-weight-bolder">
                                        View Profile
                                    </span>
                                </a>
                            </li>
                            <div class="dropdown-divider"></div>

                            <?= Html::if($model->genderName, <<< HTML
                                <li class="navi-item" data-toggle="tooltip" title="Gender" data-placement="left" data-theme="dark">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                           <i class="fas fa-transgender-alt"></i>
                                        </span>
                                        <span class="navi-text">
                                            {$model->genderName}
                                        </span>
                                    </a>
                                </li>
                            HTML) ?>
                            
                            <?= Html::if($model->civilStatusName, <<< HTML
                                <li class="navi-item" data-toggle="tooltip" title="Civil Status" data-placement="left" data-theme="dark">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="far fa-file-alt"></i>
                                        </span>
                                        <span class="navi-text">
                                            {$model->civilStatusName}
                                        </span>
                                    </a>
                                </li>
                            HTML) ?>

                            <?= Html::if($model->educationalAttainmentLabel, <<< HTML
                                <li class="navi-item" data-toggle="tooltip" title="Educational Attainment" data-placement="left" data-theme="dark">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="fas fa-school"></i>
                                        </span>
                                        <span class="navi-text">
                                            {$model->educationalAttainmentLabel}
                                        </span>
                                    </a>
                                </li>
                            HTML) ?>

                            <?= Html::if($model->birthDate, <<< HTML
                                <li class="navi-item" data-toggle="tooltip" title="Birth Date" data-placement="left" data-theme="dark">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                        <span class="navi-text">
                                            {$model->birthDate}
                                        </span>
                                    </a>
                                </li>
                            HTML) ?>

                            <?= Html::if($model->birth_place, <<< HTML
                                <li class="navi-item" data-toggle="tooltip" title="Birth Place" data-placement="left" data-theme="dark">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="fas fa-map-marked-alt"></i>
                                        </span>
                                        <span class="navi-text">
                                            {$model->birth_place}
                                        </span>
                                    </a>
                                </li>
                            HTML) ?>

                            <?= Html::if($model->occupation, <<< HTML
                                <li class="navi-item" data-toggle="tooltip" title="Occupation" data-placement="left" data-theme="dark">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="fas fa-laptop"></i>
                                        </span>
                                        <span class="navi-text">
                                            {$model->occupation}
                                        </span>
                                    </a>
                                </li>
                            HTML) ?>

                            <?= Html::if($model->email, <<< HTML
                                <li class="navi-item" data-toggle="tooltip" title="Email" data-placement="left" data-theme="dark">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <span class="navi-text">
                                            {$model->email}
                                        </span>
                                    </a>
                                </li>
                            HTML) ?>

                            <?= Html::if($model->contact_no, <<< HTML
                                <li class="navi-item" data-toggle="tooltip" title="Contact Number" data-placement="left" data-theme="dark">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="fas fa-mobile-alt"></i>
                                        </span>
                                        <span class="navi-text">
                                            {$model->contact_no}
                                        </span>
                                    </a>
                                </li>
                            HTML) ?>

                            <?= Html::if($model->monthlyIncome, <<< HTML
                                <li class="navi-item" data-toggle="tooltip" title="Monthly Income" data-placement="left" data-theme="dark">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="fas fa-money-bill-wave-alt"></i>
                                        </span>
                                        <span class="navi-text">
                                            {$model->monthlyIncome}
                                        </span>
                                    </a>
                                </li>
                            HTML) ?>

                            <?= Html::if($model->source_of_income, <<< HTML
                                <li class="navi-item" data-toggle="tooltip" title="Source of Income" data-placement="left" data-theme="dark">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="fas fa-landmark"></i>
                                        </span>
                                        <span class="navi-text">
                                            {$model->source_of_income}
                                        </span>
                                    </a>
                                </li>
                            HTML) ?>

                            <?= Html::if($model->pensionerTag, <<< HTML
                            <li class="navi-item" data-toggle="tooltip" title="Pensioner" data-placement="left" data-theme="dark">
                                <a href="#" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="far fa-star"></i>
                                    </span>
                                    <span class="navi-text">
                                        {$model->pensionerTag}
                                    </span>
                                </a>
                            </li>
                            HTML) ?>

                            <?= Html::if($model->pensioner_from, <<< HTML
                                <li class="navi-item" data-toggle="tooltip" title="Pensioner From" data-placement="left" data-theme="dark">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="fas fa-building"></i>
                                        </span>
                                        <span class="navi-text">
                                            {$model->pensioner_from}
                                        </span>
                                    </a>
                                </li>
                            HTML) ?>

                            <?= Html::if($model->monthlyPensionAmount, <<< HTML
                                <li class="navi-item" data-toggle="tooltip" title="Monthly Pension Amount" data-placement="left" data-theme="dark">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="fas fa-money-check"></i>
                                        </span>
                                        <span class="navi-text">
                                            {$model->monthlyPensionAmount}
                                        </span>
                                    </a>
                                </li> 
                            HTML) ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="symbol symbol-60 symbol-xxl-90 mr-5 align-self-start align-self-xxl-center">
                    <div class="symbol-label" style="background-image:url('<?= Url::image($model->photo, ['w' => 200]); ?>')"></div>
                </div>
                <div>
                    <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary"><?= strtoupper($model->name) ?></a>
                    <div class="text-muted"><?= $model->relationName ?></div>
                    <div class="mt-2">
                        <a href="#" class="btn btn-icon btn-sm btn-light-primary mr-1 btn-update-member"  data-key="<?= $model->id ?>">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>
                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
                                    </g>
                                </svg>
                            </span>
                        </a>

                        <a href="#" class="btn btn-icon btn-sm btn-light-danger mr-1 btn-delete-member" data-key="<?= $model->id ?>">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</div>