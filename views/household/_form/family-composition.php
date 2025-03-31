<?php

use app\helpers\Html;
use app\helpers\Url;
use app\models\form\household\FamilyCompositionForm;
use app\widgets\ActiveForm;

$this->registerJs(<<< JS
    $('.btn-add-member').on('click', function() {
        KTApp.blockPage({
            overlayColor: '#000000',
            state: 'warning',
            message: 'Loading form...'
        });
 
        $.ajax({
            url: app.baseUrl + 'household/add-family-composition',
            data: {no: {$household->no}},
            method: 'get',
            dataType: 'json',
            success: function(s) {
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
                KTApp.unblockPage();
                $('[data-toggle="popover"]').popover();
            },
            error: function(e) {
                Swal.fire("Error!", e.responseText, "error");
                KTApp.unblockPage();
            }
        });
    });

    $('.btn-save-family-composition').on('click', function() {
        KTApp.block('#modal-family-composition', {
            overlayColor: '#000000',
            state: 'warning',
            message: 'Saving...'
        });
        $('#add-member-form').submit();

        setTimeout(function() {
            KTApp.unblock('#modal-family-composition');
        }, 2000);
    });

    $(document).on('beforeSubmit', '#add-member-form', function() {
        KTApp.block('#modal-family-composition', {
            overlayColor: '#000000',
            state: 'warning',
            message: 'Saving...'
        });

        return true;
    });

    $('.btn-close-modal').on('click', function() {
        KTApp.blockPage({
            overlayColor: '#000000',
            state: 'warning',
            message: 'Refreshing...'
        });
        location.reload();
    });
JS);

?>

<h4 class="mb-10 font-weight-bold text-dark">
    Family Composition's Information &nbsp;
    <button type="button" class="btn btn-primary btn-pill btn-add-member">
        Add Member
    </button>
</h4>


<div class="row">
    <?= Html::ifElse(($members = $household->familyCompositions) != null, function() use($members) {
        $t = number_format(count($members));

        return implode(' ', [
            "<div class='col-md-12 pb-3 font-weight-bolder'>Total of {$t} members found.</div>",
            Html::foreach($members, function($model, $key) {
                return $this->render('_member', [
                    'model' => $model,
                    'key' => $key,
                ]);
            })
        ]);
    }, '<div class="col-md-12"><h5>No Member yet.</h5></div>') ?>
</div>

<div class="row">
    <div class="col">
        <?= Html::a('Back', Url::current(['step' => 'family-head']), [
            'class' => 'btn btn-secondary btn-lg'
        ]) ?>
        <?= Html::a('Next', Url::current(['step' => 'summary']), [
            'class' => 'btn btn-success btn-lg'
        ]) ?>
    </div>
</div>

<!-- Modal-->
<div class="modal fade" id="modal-family-composition" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Family Composition Form</h5>
                <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" data-scroll="true">
                
            </div>
            <div class="modal-footer">
                <?= Html::a('Close', '#', [
                    'class' => 'btn btn-light-primary font-weight-bold btn-close-modal',
                    'data-dismiss' => 'modal'
                ]) ?>
                <button type="button" class="btn btn-success font-weight-bold btn-save-family-composition">Save</button>
            </div>
        </div>
    </div>
</div>