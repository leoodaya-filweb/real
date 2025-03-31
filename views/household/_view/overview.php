<?php

use app\widgets\Mapbox;
use app\helpers\Html;
use app\widgets\Value;
use app\widgets\AppIcon;
use app\widgets\Iconbox;

$this->registerCss(<<< CSS
    .a-member-container {
        cursor: pointer;
    }
    .l-border:hover {
        outline: 1px solid #337ab7;
    }
CSS);
$this->registerJs(<<< JS
    $('.a-member-container').click(function() {
        window.location.href = $(this).data('url');
    });
JS);
?>
<div class="row">
    <div class="col">
        <?= Iconbox::widget([
            'title' => 'Total Members',
            'iconContent' => Html::tag(
                'div', AppIcon::widget(['icon' => 'add-user']), [
                'class' => 'svg-icon svg-icon-warning svg-icon-4x',
            ]) . $model->totalMembersTag,
            'content' => 'Total numbers of this household members. Includes head of the family',
            'wrapperClass' => 'wave wave-animate-slower'
        ]) ?>
    </div>
    <div class="col">
        <?= Iconbox::widget([
            'title' => 'Total Transactions',
            'iconContent' => Html::tag(
                'div', AppIcon::widget(['icon' => 'chart']), [
                'class' => 'svg-icon svg-icon-primary svg-icon-4x',
            ]) . $model->totalTransactionsTag,
            'content' => 'Overall transaction recorded with this household members.',
            'wrapperClass' => 'wave wave-animate-slow wave-primary'
        ]) ?>
    </div>
    <div class="col">
        <?= Iconbox::widget([
            'title' => 'Total Assistance',
            'iconContent' => Html::tag(
                'div', AppIcon::widget(['icon' => 'money']), [
                'class' => 'svg-icon svg-icon-success svg-icon-4x',
            ]) . $model->totalAmountTransactionsTag,
            'content' => 'Total cash assistance recorded with this household members.',
            'wrapperClass' => 'wave wave-animate-slower wave-success'
        ]) ?>
    </div>
</div>

<div class="row mt-7">
    <div class="col-md-4">
        <div class="scroll scroll-pull" data-scroll="true" data-wheel-propagation="true" style="height: 500px">
            <p class="lead font-weight-bold">Household Members (<?= $model->totalMembers ?>)</p>
            <?= Html::foreach($model->members, function($member) {
                $img = Html::image($member->photo, ['w' => 40]);
               
                return Value::widget([
                    'content' => <<< HTML
                        <div class="d-flex a-member-container" data-url="{$member->viewUrl}">
                            <div class="symbol symbol-40 symbol-sm flex-shrink-0">
                                {$img}
                            </div>
                            <div class="ml-4">
                                <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">
                                    {$member->fullname}
                                </div>
                                <p class="font-weight-bold mb-0">
                                    {$member->tags}
                                </p>
                            </div>
                        </div>
                    HTML
                ]) . '<div class="my-2"></div>';
            }) ?>
            <?= Html::a('ADD MEMBER', '#', [
                'data-toggle' => 'tooltip',
                'title' => 'Add member',
                'class' => 'btn btn-primary btn-block font-weight-bold mt-3 btn-add-member'
        ]) ?>
        </div>
    </div>
    <div class="col">
        <?= Mapbox::widget([
            'enableClick' => false,
            'draggableMarker' => false,
           // 'styleUrl'=> 'mapbox://styles/roelfilweb/clzw38zek000q01pe80m76cz0',
            'lnglat' => [$model->longitude, $model->latitude],
        ]) ?>
    </div>
</div>

<?= Html::if(($imageFiles = $model->imageFiles) != null, function() use($imageFiles) {
    $images =  Html::foreach($imageFiles, function($file) {
        $image = Html::image($file, ['w' => 200], [
            'class' => 'img-thumbnail'
        ]);
        return <<< HTML
            <div class="col-md-3">
                <a href="{$file->viewerUrl}" target="_blank">
                    {$image}
                </a>
            </div>
        HTML;
    });

    return <<< HTML
        <p class="lead font-weight-bold mt-10">Photos</p>
        <div class="row">{$images}</div>
    HTML;
}) ?>


<?php

$this->registerJs(<<< JS
    $('.btn-add-member').on('click', function() {
        KTApp.blockPage({
            overlayColor: '#000000',
            state: 'warning',
            message: 'Loading form...'
        });
 
        $.ajax({
            url: app.baseUrl + 'household/add-family-composition',
            data: {no: {$model->no}},
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