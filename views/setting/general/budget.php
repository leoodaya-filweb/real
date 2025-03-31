<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Budget;
use app\widgets\AppBudget;
use app\widgets\AppIcon;
use app\widgets\Grid;

$this->registerCss(<<< CSS
	.app-iconbox {box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px 0 !important;}
    .app-iconbox .card-body {padding: 0rem 0.25rem !important;}
    label.radio a {
        color: #3F4254;
    }
    label.checked a {
        color: #3699FF;
    }
CSS);

$this->registerJs(<<< JS
    let loadForm = function(url, title) {
        KTApp.block('body', {
            overlayColor: '#000000',
            state: 'warning',
            message: 'Please wait...'
        });
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json',
            success: function(s) {
                $('#modal-entry .modal-title').html(title);
                $('#modal-entry .modal-body').html(s.form);
                $('.kt-selectpicker').selectpicker();

                $('#modal-entry').modal('show');
                KTApp.unblock('body');

            },
            error: function(e) {
                Swal.fire("Error", e.responseText, "error");
                KTApp.unblock('body');
            }
        });
    }

    $('.btn-add-initial-budget').click(function() {
        loadForm($(this).data('url'), 'Add Initial Budget');
        $('#modal-entry .modal-dialog').removeClass('modal-xl').addClass('modal-md');
    });

    $('.btn-add-budget').click(function() {
        loadForm($(this).data('url'), 'Add Budget');
        $('#modal-entry .modal-dialog').removeClass('modal-xl').addClass('modal-md');
    });

    $('.btn-disburse-budget').click(function() {
        loadForm($(this).data('url'), 'Disbursed Budget');
        $('#modal-entry .modal-dialog').removeClass('modal-md').addClass('modal-xl');
    });

    $('.btn-update-initial-budget').click(function() {
        loadForm($(this).data('url'), 'Update Initial Budget');
        $('#modal-entry .modal-dialog').removeClass('modal-xl').addClass('modal-md');
    });

    $('.btn-update-additional-budget').click(function() {
        loadForm($(this).data('url'), 'Update Additional Budget');
        $('#modal-entry .modal-dialog').removeClass('modal-xl').addClass('modal-md');
    });

    $('.btn-update-disbursed-budget').click(function() {
        loadForm($(this).data('url'), 'Update Disbursed Budget');
        $('#modal-entry .modal-dialog').removeClass('modal-md').addClass('modal-xl');
    });

    $('input[name="action[]"]').click(function() {
        window.location.href = $(this).data('url');
    })
JS);


$initial = Budget::initial();
?>
<div class="d-flex justify-content-between">
	<div class="d-flex">
		<h4 class="font-weight-bold text-dark mt-4">Budget </h4>
		<div class="btn-group ml-5">
		    <button class="btn btn-light-primary font-weight-bold btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Year: <?= $model->year ?>
		    </button>
		    <div class="dropdown-menu">
		        <?= Html::foreach(Budget::filter('year'), function($year) {
                    return Html::a($year, Url::current(['year' => $year]), [
                        'class' => 'dropdown-item'
                    ]);
		    	}) ?>
		    </div>
		</div>
	</div>
    <?= Html::ifElse($initial, <<< HTML
        <div class="btn-group">
            <button data-url="{$model->createAdditionalUrl}" class="btn btn-lg btn-light-success btn-add-budget" title="Add Budget" data-toggle="tooltip">
                <i class="fa fa-plus"></i>
            </button>
            <button data-url="{$model->createDisburseUrl}" class="btn btn-lg btn-light-danger btn-disburse-budget" title="Disbursed Budget" data-toggle="tooltip">
                <i class="fa fa-minus"></i>
            </button>
        </div>
        HTML, Html::a('Set Initial Budget', '#', [
            'class' => 'btn btn-success btn-lg btn-add-initial-budget',
            'data-url' => $model->createUrl
        ])) ?>
</div>

<div class="mt-10">
    <?= AppBudget::widget([
        'template' => 'blank'
    ]) ?>
</div>

<div class="mt-10">
    <div class="form-group">
        <label>Actions</label>
        <div class="radio-inline">
            <label class="radio <?= App::get('action') === '' ? 'checked': '' ?>">
                <input type="radio" name="action[]" value="all" <?= App::get('action') === '' ? 'checked': '' ?> data-url="<?= Url::current(['action' => '']) ?>">
                <span></span> <a href="<?= Url::current(['action' => '']) ?>">All</a>
            </label>
            <?= Html::foreach(App::params('budget_actions'), function($action) {
                $selected = ($action['id'] ==  App::get('action')) ? 'checked': '';
                $selected = App::get('action') === '' ? '': $selected;
                $url = Url::current(['action' => $action['id']]);
                return <<< HTML
                    <label class="radio {$selected}">
                        <input data-url="{$url}" type="radio" name="action[]" value="{$action['id']}" {$selected}>
                        <span></span> <a href="{$url}">{$action['label']}</a>
                    </label>
                HTML;
            }) ?>
        </div>
    </div>
    <?= Grid::widget([
        'searchModel' => $model->searchModel,
        'dataProvider' => $model->dataProvider,
        'withActionColumn' => false,
        // 'withFilterModel' => true
    ]); ?>
</div>

<div class="modal fade" id="modal-entry" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Add Budget
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>