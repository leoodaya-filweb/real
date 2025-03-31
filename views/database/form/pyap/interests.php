<?php

use app\helpers\Html;

$this->registerJs(<<< JS
	let generateInterest = function(interest) {
		let html = '<div class="input-group mb-2">';
			html += '<input placeholder="Enter an interest" type="text" class="form-control" name="Database[interests][]" value="'+ interest +'">';
			html += '<div class="input-group-append">';
				html += '<button class="btn btn-danger btn-icon btn-remove-interest" type="button">';
					html += '<i class="fa fa-trash"></i>';
				html += '</button>';
			html += '</div>';
		html += '</div>';

		$('.interests-container').prepend(html);
		$('#database-interests').val('').focus();
	}

	$(document).on('keydown', '#database-interests', function(e) {
		if(e.key == 'Enter') {
            e.preventDefault();
			generateInterest($(this).val());
        }
	});

	$(document).on('click', '.btn-add-interest', function() {
		let interest = $('#database-interests').val();

		if(interest) {
			generateInterest(interest);
		}
		else {
			Swal.fire('Warning', 'Please enter a interest!', 'warning');
		}
	});

	$(document).on('click', '.btn-remove-interest', function() {
		$(this).closest('.input-group').remove();
	});
JS);
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">V. Mga interes at libangan</h3>
			 <hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">

			<label>Interest at Libangan</label>
			<div class="input-group">
				<input id="database-interests" type="text" name="" class="form-control" placeholder="Enter an interest">
				<div class="input-group-append">
					<button class="btn btn-success btn-add-interest btn-icon" type="button">
						<i class="fa fa-plus-circle"></i>
					</button>
				</div>
			</div>

			<div class="interests-container mt-2">
				<?= Html::foreach($model->interests, function($interest) {
					return <<< HTML
						<div class="input-group mb-2">
							<input placeholder="Enter an interest" type="text" class="form-control" name="Database[interests][]" value="{$interest}">
							<div class="input-group-append">
								<button class="btn btn-danger btn-icon btn-remove-interest" type="button">
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