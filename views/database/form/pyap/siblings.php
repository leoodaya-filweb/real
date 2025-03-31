<?php

use app\helpers\Html;
use app\models\EducationalAttainment;
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">II. Siblings (Mga Kapatid)</h3>
			<hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table">
				<thead>
					<tr>
						<th>Pangalan</th>
						<th>Kasarian</th>
						<th>Edad</th>
						<th>Grade/Year</th>
						<th>ISY</th>
						<th>OSY</th>
					</tr>
				</thead>
				<tbody>
					<?= Html::foreach($model->family_composition, function($data, $key) {
						$male = Html::ifElse($data['gender'] == 'Male', 'selected', '');
        				$female = Html::ifElse($data['gender'] == 'Female', 'selected', '');
						return <<< HTML
							<tr>
								<td> 
									<input class="form-control" type="hidden" value="{$data['no']}" name="Database[family_composition][{$key}][no]">

									<input class="form-control" type="text" value="{$data['name']}" name="Database[family_composition][{$key}][name]">
								</td>

								<td> 
									<select name="Database[family_composition][{$key}][gender]" class="form-control">
										<option value="">- Select - </option>
										<option value="Male" {$male}>Male</option>
										<option value="Female" {$female}>Female</option>
									</select>
								</td>

								<td> 
									<input class="form-control" type="number" value="{$data['age']}"name="Database[family_composition][{$key}][age]"> 
								</td>
								<td> 
									<input class="form-control" type="text" value="{$data['grade']}"name="Database[family_composition][{$key}][grade]" list="datalist-educational-attainment"> 
								</td>
								<td> 
									<input class="form-control" type="text" value="{$data['isy']}" name="Database[family_composition][{$key}][isy]"> 
								</td>
								<td> 
									<input class="form-control" type="text" value="{$data['osy']}" name="Database[family_composition][{$key}][osy]"> 
								</td>
							</tr>
						HTML;
					}) ?>
				</tbody>
			</table>
		</div>
	</div>
</section>

<datalist id="datalist-educational-attainment">
	<?= Html::foreach(EducationalAttainment::filter('label'), function($o) {
		return Html::tag('option', $o);
	}) ?>
</datalist>
