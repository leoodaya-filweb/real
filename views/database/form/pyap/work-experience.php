<?php

use app\helpers\Html;
use app\models\Database;

$this->registerJs(<<< JS
	$('.year-month-datepickers').datepicker({
		format: "mm/yyyy",
		rtl: false,
		// todayBtn: "linked",
		clearBtn: true,
		todayHighlight: true,
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		},
		endDate: "today",
		autoclose: true
	});
JS);
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">VI. Karanasan sa Paggawa o Trabaho</h3>
			<hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table">
				<thead>
					<tr>
						<th>Taon at Buwan</th>
						<th>Titulo sa Trabaho</th>
						<th>Buwanang kita</th>
						<th>Dahilan ng pag-alis</th>
					</tr>
				</thead>
				<tbody>
					<?= Html::foreach($model->work_experience, function($data, $key) {
						return <<< HTML
							<tr>
								<td> 
									<input class="form-control" type="hidden" value="{$data['no']}" name="Database[work_experience][{$key}][no]">

									<input class="form-control year-month-datepickers" type="text" value="{$data['year_month']}" name="Database[work_experience][{$key}][year_month]">
								</td>

								<td> 
									<input class="form-control" type="text" value="{$data['job_title']}"name="Database[work_experience][{$key}][job_title]" list="datalist-occupation"> 
								</td>
								<td> 
									<input class="form-control" type="number" value="{$data['monthly_income']}"name="Database[work_experience][{$key}][monthly_income]"> 
								</td>
								<td> 
									<input class="form-control" type="text" value="{$data['reason_for_leaving']}" name="Database[work_experience][{$key}][reason_for_leaving]"> 
								</td>
							</tr>
						HTML;
					}) ?>
				</tbody>
			</table>
		</div>
	</div>
</section>


<datalist id="datalist-occupation">
	<?= Html::foreach(Database::filter('occupation'), function($o) {
		return Html::tag('option', $o);
	}) ?>
</datalist>
