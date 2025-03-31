<?php

use app\models\Region;
use app\models\Country;
use app\models\Province;
use app\widgets\ActiveForm;
use app\models\Municipality;
use app\widgets\BootstrapSelect;

$this->registerJs(<<< JS
	let showLoading = function() {
		KTApp.block('#address-setting-form', {
			overlayColor: '#000000',
			state: 'warning', // a bootstrap color
			size: 'lg' //available custom sizes: sm|lg
		});
	}

	let hideLoading = function() {
		KTApp.unblock('#address-setting-form');
	}

	$('#addresssettingform-region_id').on('change', function() {
		var region_id = $(this).val();
		showLoading();

		$.ajax({
			url: app.baseUrl + 'setting/get-provinces',
			method: 'get',
			data: {region_id: region_id},
			dataType: 'json',
			success: function(s) {
				if (s.status == 'success') {
					var html = '';
					for(province_id in s.models) {
						html += '<option value="'+ province_id +'">'+ s.models[province_id] +'</option>';
					}
					$('#addresssettingform-province_id')
						.html(html)
						.selectpicker("refresh")
						.trigger('change');
				}
				else {
					Swal.fire("Error", s.error, "error");
				}
				hideLoading();
			},
			error: function(e) {
				Swal.fire("Error", e.responseText, "error");
				hideLoading();
			}
		});
	});

	$('#addresssettingform-province_id').on('change', function() {
		var province_id = $(this).val();
		showLoading();

		$.ajax({
			url: app.baseUrl + 'setting/get-municipalities',
			method: 'get',
			data: {province_id: province_id},
			dataType: 'json',
			success: function(s) {
				if (s.status == 'success') {
					var html = '';
					for(province_id in s.models) {
						html += '<option value="'+ province_id +'">'+ s.models[province_id] +'</option>';
					}
					$('#addresssettingform-municipality_id')
						.html(html)
						.selectpicker("refresh");
				}
				else {
					Swal.fire("Error", s.error, "error");
				}
				hideLoading();
			},
			error: function(e) {
				Swal.fire("Error", e.responseText, "error");
				hideLoading();
			}
		});
	});
JS);
?>
<?php $form = ActiveForm::begin(['id' => 'address-setting-form']); ?>
	<h4 class="mb-10 font-weight-bold text-dark">Address</h4>
	<div class="row">
		<div class="col-md-4">
			<?= BootstrapSelect::widget([
	            'attribute' => 'region_id',
	            'model' => $model,
	            'form' => $form,
                'label' => 'Region',
				'prompt' => false,
	            'data' => Region::dropdown('id', 'name', [
                    'country_id' => Country::getPhilippinesId()
                ]),
	        ]) ?>
		</div>
		<div class="col-md-4">
            <?= BootstrapSelect::widget([
	            'attribute' => 'province_id',
	            'model' => $model,
	            'form' => $form,
				'prompt' => false,
                'label' => 'Province',
	            'data' => Province::dropdown('id', 'name', [
                    'region_id' => $model->region_id
                ]),
	        ]) ?>
		</div>
		<div class="col-md-4">
            <?= BootstrapSelect::widget([
	            'attribute' => 'municipality_id',
	            'model' => $model,
	            'form' => $form,
				'prompt' => false,
                'label' => 'Municipality',
	            'data' => Municipality::dropdown('id', 'name', [
                    'province_id' => $model->province_id
                ]),
	        ]) ?>
		</div>
	</div>
    
	<div class="form-group"> <br>
		<?= ActiveForm::buttons() ?>
	</div>
<?php ActiveForm::end(); ?>