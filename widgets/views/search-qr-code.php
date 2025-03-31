<?php

use app\helpers\Html;
use app\helpers\Url;
use app\models\Member;
use app\widgets\ActiveForm;
use app\widgets\Autocomplete;

$this->registerWidgetJs($widgetFunction, <<< JS
	let viewMember = function() {
		KTApp.block('body', {
			overlayColor: '#000000',
			state: 'warning',
			message: 'Please wait...'
		});
		$.ajax({
			url: app.baseUrl + 'member/detail',
			data: {
				qr_id: $('input[name="qr_id-{$widgetId}"]').val(),
				template: '{$template}'
			},
			dataType: 'json',
			success: function(s) {
				if (s.status == 'success') {
					$('#modal-search-qr-id-{$widgetId} .modal-body').html(s.detailView);
					$('#modal-search-qr-id-{$widgetId} .modal-title').html('{$modalTitle} ' + s.model.fullname + '<a target="_blank" href="'+ s.model.viewUrl +'" class="btn btn-outline-primary btn-sm font-weight-bolder ml-2">View Profile</a>');
					$('#modal-search-qr-id-{$widgetId}').modal('show');
				}
				else {
					Swal.fire('Error', s.error, 'error');
				}
				KTApp.unblock('body');
    			$('[data-toggle="popover"]').popover();
			},
			error: function(e) {
				Swal.fire('Error', e.responseText, 'error');
				KTApp.unblock('body');
			}
		});
	}

	let viewMemberByName = function() {
		KTApp.block('body', {
			overlayColor: '#000000',
			state: 'warning',
			message: 'Please wait...'
		});
		$.ajax({
			url: app.baseUrl + 'member/detail-by-name',
			data: {
				name: $('input[name="search-name-{$widgetId}"]').val(),
				template: '{$template}'
			},
			dataType: 'json',
			success: function(s) {
				if (s.status == 'success') {
					$('#modal-search-qr-id-{$widgetId} .modal-body').html(s.detailView);
					$('#modal-search-qr-id-{$widgetId} .modal-title').html('{$modalTitle} ' + s.model.fullname + '<a target="_blank" href="'+ s.model.viewUrl +'" class="btn btn-outline-primary btn-sm font-weight-bolder ml-2">View Profile</a>');
					$('#modal-search-qr-id-{$widgetId}').modal('show');
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
	}

	$('#btn-{$widgetId}').click(function() {
		viewMember();
	});

	$('#btn-search-name-{$widgetId}').click(function() {
		viewMemberByName();
	});
JS);

$this->registerCss(<<< CSS
	.modal-dialog.modal-xl {
	    max-width: 1400px;
	}
	.search-qr-code-container .card {
		outline: 2px solid #337ab7;
	}
	.search-qr-code-container .card-header {
		background: #337ab7;
		background-color: #337ab7 !important;
	}
	.search-qr-code-container .card-label, .search-qr-code-container i {
		color: #fff !important;
	}
CSS);

$memberCreateUrl = (new Member())->createUrl;
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
	'title' => $title,
	'stretch' => true,
	'class' => 'search-qr-code-container'
]); ?>
	<div class="search-qr-code">
		<?= Autocomplete::widget([
			'url' => Url::to(['member/find-name-by-keywords']),
            'submitOnclickJs' => <<< JS
				KTApp.block('body', {
					overlayColor: '#000000',
					state: 'warning',
					message: 'Please wait...'
				});
				$.ajax({
					url: app.baseUrl + 'member/detail-by-name',
					data: {
						name: inp.value,
						template: '{$template}'
					},
					dataType: 'json',
					success: function(s) {
						if (s.status == 'success') {
							$('#modal-search-qr-id-{$widgetId} .modal-body').html(s.detailView);
							$('#modal-search-qr-id-{$widgetId} .modal-title').html('{$modalTitle} ' + s.model.fullname + '<a target="_blank" href="'+ s.model.viewUrl +'" class="btn btn-outline-primary btn-sm font-weight-bolder ml-2">View Profile</a>');
							$('#modal-search-qr-id-{$widgetId}').modal('show');
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
			'input' => <<< HTML
				<div class="input-group">
					<input type="text" class="form-control form-control-lg" name="search-name-{$widgetId}" placeholder="Type Name or Household no" autofocus="on">
					<div class="input-group-append">
						<button class="btn btn-primary btn-lg" type="button" id="btn-search-name-{$widgetId}">
							Search
						</button>
					</div>
				</div>
				<label class="font-weight-bold mt-3">
					For transactions requiring Brgy. Clearance, input name shown in the Brgy. Clearance presented
				</label>
			HTML,
        ]) ?>

        <div class="my-10"></div>

		<?= Autocomplete::widget([
			'submitOnclickJs' => <<< JS
				KTApp.block('body', {
					overlayColor: '#000000',
					state: 'warning',
					message: 'Please wait...'
				});
				$.ajax({
					url: app.baseUrl + 'member/detail',
					data: {
						qr_id: inp.value,
						template: '{$template}'
					},
					dataType: 'json',
					success: function(s) {
						if (s.status == 'success') {
							$('#modal-search-qr-id-{$widgetId} .modal-body').html(s.detailView);
							$('#modal-search-qr-id-{$widgetId} .modal-title').html('{$modalTitle} ' + s.model.fullname + '<a target="_blank" href="'+ s.model.viewUrl +'" class="btn btn-outline-primary btn-sm font-weight-bolder ml-2">View Profile</a>');
							$('#modal-search-qr-id-{$widgetId}').modal('show');
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
			'input' => <<< HTML
				<div class="input-group">
					<input type="text" class="form-control form-control-lg" name="qr_id-{$widgetId}" placeholder="Type QR ID" autofocus="on">
					<div class="input-group-append">
						<button class="btn btn-primary btn-lg" type="button" id="btn-{$widgetId}">
							Search
						</button>
					</div>
				</div>
				<p class="mt-4 font-weight-bold">
					Member not found? 
					<a href="{$memberCreateUrl}" class="search-by-name-{$widgetId}" target="_blank">
						Create Here
					</a>
				</p>
			HTML,
	        'url' => Url::to(['member/find-qr-by-keywords'])
	    ]) ?>
	</div>
<?php $this->endContent(); ?>


<div class="modal fade" id="modal-search-qr-id-<?= $widgetId ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                	Create Transaction
                </h5> 
                <button type="button" class="close btn-close-search-qr-id" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold btn-close-search-qr-id" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>