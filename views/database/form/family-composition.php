<?php

use app\helpers\Html;
use app\models\CivilStatus;
use app\models\Database;
use app\models\Relation;

$this->registerJs(<<< JS
	$('.datepickers').datepicker({
		rtl: false,
		todayBtn: "linked",
		clearBtn: true,
		todayHighlight: true,
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		},
		endDate: "today",
		autoclose: true
	});


	function computeAge(dateString) {
		var ageInMilliseconds = new Date() - new Date(dateString);
		return Math.floor(ageInMilliseconds/1000/60/60/24/365); // convert to years
	}

	$('.datepickers').on('change', function() {
		let birthDate = $(this).val(),
			id = $(this).data('key');

		$('#age-' + id).val(computeAge(birthDate));
	});
JS);

?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Family Composition</h3>
			<hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table">
				<thead>
					<tr>
						<th>NO</th>
						<th>NAME</th>
						<th>BIRTHDATE</th>
						<th>AGE</th>
						<th>CIVIL STATUS</th>
						<th>RELATIONSHIP</th>
						<th>OCCUPATION</th>
						<th>INCOME</th>
					</tr>
				</thead>
				<tbody>
					<?= Html::foreach($model->family_composition, function($data, $key) {
						return <<< HTML
							<tr>
								<td> 
									{$data['no']}
									<input class="form-control" type="hidden" value="{$data['no']}" name="Database[family_composition][{$key}][no]">
								</td>

								<td> 
									<input class="form-control" type="text" value="{$data['name']}" name="Database[family_composition][{$key}][name]"> 
								</td>

								<td> 
									<input class="form-control datepickers" type="text" value="{$data['birth_date']}" data-key="{$key}" name="Database[family_composition][{$key}][birth_date]"> 
								</td>

								<td> 
									<input readonly="readonly" class="form-control" type="text" value="{$data['age']}" id="age-{$key}" name="Database[family_composition][{$key}][age]"> 
								</td>
								<td> 
									<input class="form-control" type="text" value="{$data['civil_status']}" list="datalist-civil-status" name="Database[family_composition][{$key}][civil_status]"> 
								</td>
								<td> 
									<input class="form-control" type="text" value="{$data['relationship']}" list="datalist-relationship" name="Database[family_composition][{$key}][relationship]"> 
								</td>
								<td> 
									<input class="form-control" type="text" value="{$data['occupation']}" list="datalist-occupation" name="Database[family_composition][{$key}][occupation]"> 
								</td>
								<td> 
									<input class="form-control" type="number" value="{$data['income']}" name="Database[family_composition][{$key}][income]"> 
								</td>
							</tr>
						HTML;
					}) ?>
				</tbody>
			</table>
		</div>
	</div>
</section>

<datalist id="datalist-civil-status">
	<?= Html::foreach(CivilStatus::filter('label'), function($o) {
		return Html::tag('option', $o);
	}) ?>
</datalist>

<datalist id="datalist-relationship">
	<?= Html::foreach(Relation::filter('label'), function($o) {
		return Html::tag('option', $o);
	}) ?>
</datalist>

<datalist id="datalist-occupation">
	<?= Html::foreach(Database::filter('occupation'), function($o) {
		return Html::tag('option', $o);
	}) ?>
</datalist>