<?php

use app\helpers\Html;
use app\models\Database;
use app\models\EducationalAttainment;
use app\widgets\DataList;

$this->registerJs(<<< JS
	let generateSkill = function(skill) {
		let html = '<div class="input-group mb-2">';
			html += '<input placeholder="Enter a Skill" type="text" class="form-control" name="Database[skills][]" value="'+ skill +'">';
			html += '<div class="input-group-append">';
				html += '<button class="btn btn-danger btn-icon btn-remove-skill" type="button">';
					html += '<i class="fa fa-trash"></i>';
				html += '</button>';
			html += '</div>';
		html += '</div>';

		$('.skills-container').prepend(html);
		$('#database-skills').val('').focus();
	}

	$(document).on('keydown', '#database-skills', function(e) {
		if(e.key == 'Enter') {
            e.preventDefault();
			generateSkill($(this).val());
        }
	});

	$(document).on('click', '.btn-add-skill', function() {
		let skill = $('#database-skills').val();

		if(skill) {
			generateSkill(skill);
		}
		else {
			Swal.fire('Warning', 'Please enter a skill!', 'warning');
		}
	});

	$(document).on('click', '.btn-remove-skill', function() {
		$(this).closest('.input-group').remove();
	});
JS);
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Education & Source of Income</h3>
			<hr/>
		</div>
	</div>

	<div class="row">
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-6">
					<?= DataList::widget([
						'form' => $form,
						'model' => $model,
						'attribute' => 'educ_attainment',
						'data' => EducationalAttainment::filter('label'),
						'sort' => false
					]) ?>
				</div>
				<div class="col-md-6">
					<?= DataList::widget([
						'form' => $form,
						'model' => $model,
						'attribute' => 'occupation',
						'data' => Database::filter('occupation'),
					]) ?>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<?= $form->field($model, 'source_of_income')->textInput([
						'maxlength' => true,
					]) ?>
				</div>
				<div class="col-md-6">
					<?= $form->field($model, 'monthly_income')->textInput([
						'type' => 'number'
					]) ?>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<?= $form->field($model, 'other_source_income')->textInput([
						'maxlength' => true,
					]) ?>
				</div>
				<div class="col-md-6">
					<?= $form->field($model, 'other_income_source_amount')->textInput([
						'type' => 'number'
					]) ?>
				</div>
			</div>
		</div>

		<div class="col-md-4">

			<label>Skill</label>
			<div class="input-group">
				<input id="database-skills" type="text" name="" class="form-control" placeholder="Enter a Skill">
				<div class="input-group-append">
					<button class="btn btn-success btn-add-skill btn-icon" type="button">
						<i class="fa fa-plus-circle"></i>
					</button>
				</div>
			</div>

			<div class="skills-container mt-2">
				<?= Html::foreach($model->skills, function($skill) {
					return <<< HTML
						<div class="input-group mb-2">
							<input placeholder="Enter a Skill" type="text" class="form-control" name="Database[skills][]" value="{$skill}">
							<div class="input-group-append">
								<button class="btn btn-danger btn-icon btn-remove-skill" type="button">
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