<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\BootstrapSelect;
use app\widgets\Checkbox;


$this->registerJs(<<< JS
	let generateReason = function(reason) {
		let html = '<div class="input-group mb-2">';
			html += '<input placeholder="Enter a reason" type="text" class="form-control" name="Database[reasons][]" value="'+ reason +'">';
			html += '<div class="input-group-append">';
				html += '<button class="btn btn-danger btn-icon btn-remove-reason" type="button">';
					html += '<i class="fa fa-trash"></i>';
				html += '</button>';
			html += '</div>';
		html += '</div>';

		$('.reasons-container').prepend(html);
		$('#database-reasons').val('').focus();
	}

	$(document).on('keydown', '#database-reasons', function(e) {
		if(e.key == 'Enter') {
            e.preventDefault();
			generateReason($(this).val());
        }
	});

	$(document).on('click', '.btn-add-reason', function() {
		let reason = $('#database-reasons').val();

		if(reason) {
			generateReason(reason);
		}
		else {
			Swal.fire('Warning', 'Please enter a reason!', 'warning');
		}
	});

	$(document).on('click', '.btn-remove-reason', function() {
		$(this).closest('.input-group').remove();
	});
JS);

?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Others</h3>
			<hr/>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<label>
				<?= $model->getAttributeLabel('client_categories') ?>
			</label>
			<?= Checkbox::widget([
			    'data' => App::keyMapParams('client_categories', 'label', 'label'),
			    'name' => 'Database[client_category][]',
			    'inputClass' => 'checkbox',
			    'checkedFunction' => function($key, $value) use ($model) {
			        return isset($model->client_category) && in_array($key, $model->client_category) ? 'checked': '';
			    }
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'remarks')->textarea([
				'rows' => 8
			]) ?>
		</div>
		<div class="col-md-4">
			<label>Reasons</label>
			<div class="input-group">
				<input id="database-reasons" type="text" name="" class="form-control" placeholder="Enter a reason">
				<div class="input-group-append">
					<button class="btn btn-success btn-add-reason btn-icon" type="button">
						<i class="fa fa-plus-circle"></i>
					</button>
				</div>
			</div>

			<div class="reasons-container mt-2">
				<?= Html::foreach($model->reasons, function($reason) {
					return <<< HTML
						<div class="input-group mb-2">
							<input placeholder="Enter a reason" type="text" class="form-control" name="Database[reasons][]" value="{$reason}">
							<div class="input-group-append">
								<button class="btn btn-danger btn-icon btn-remove-reason" type="button">
									<i class="fa fa-trash"></i>
								</button>
							</div>
						</div>
					HTML;
				}) ?>
			</div>
		</div>
	</div>
</section>