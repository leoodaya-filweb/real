<?php

use app\helpers\Url;
use app\widgets\Autocomplete;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\HouseholdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Search Household';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['showCreateButton'] = true; 
$this->params['showExportButton'] = true;

$this->registerJs(<<< JS
	$('#btn-update-profile').click(function() {

		let no = $('.inp-update-profile').val();

		KTApp.block('body', {
			overlayColor: '#000000',
			state: 'warning',
			message: 'Please wait...'
		});
		$.ajax({
			url: app.baseUrl + 'update-household/detail',
			data: {no: no},
			dataType: 'json',
			success: function(s) {
				if (s.status == 'success') {
					$('#modal-update-household .modal-body').html(s.detailView);
					$('#modal-update-household').modal('show');
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
	});
JS);
?>
<div class="update-household-index-page">
	<div class="row">
		<div class="col-md-6">
			<p class="lead font-weight-bold">Search Household No.</p>
			<?= Autocomplete::widget([
				'submitOnclickJs' => <<< JS
					KTApp.block('body', {
						overlayColor: '#000000',
						state: 'warning',
						message: 'Please wait...'
					});

					$.ajax({
						url: app.baseUrl + 'update-household/detail',
						data: {no: inp.value},
						dataType: 'json',
						success: function(s) {
							if (s.status == 'success') {
								$('#modal-update-household .modal-body').html(s.detailView);
								$('#modal-update-household').modal('show');
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
						<input type="text" class="form-control form-control-lg inp-update-profile" name="autocomplete" placeholder="Type Household No" autofocus="on">
						<div class="input-group-append">
							<button class="btn btn-primary btn-lg" type="button" id="btn-update-profile">
								Search
							</button>
						</div>
					</div>
				HTML,
		        'url' => Url::to(['update-household/find-no-by-keywords'])
		    ]) ?>
		</div>
	</div>
</div>


<div class="modal fade" id="modal-update-household" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-update-householdLabel">
                	Update Household
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>