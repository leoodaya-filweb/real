<?php

use app\helpers\App;
use app\helpers\Html;
?>
<table class="table table-bordered table-head-solid">
	<thead>
		<tr>
			<th class="text-right">NO</th>
			<th>NAME</th>
			<th class="text-right">BIRTHDATE</th>
			<th class="text-right">AGE</th>
			<th>CIVIL STATUS</th>
			<th>RELATIONSHIP</th>
			<th>OCCUPATION</th>
			<th class="text-right">INCOME</th>
		</tr>
	</thead>
	<tbody>
		<?= Html::foreach($model->family_composition, function($data, $key) {
			return $data['name'] == null ? '': <<< HTML
				<tr>
					<td class="text-right"> {$data['no']} </td>
					<td> {$data['name']} </td>
					<td class="text-right"> {$data['birth_date']} </td>
					<td class="text-right"> {$data['age']} </td>
					<td> {$data['civil_status']} </td>
					<td> {$data['relationship']} </td>
					<td> {$data['occupation']} </td>
					<td class="text-right"> {$data['income']} </td>
				</tr>
			HTML;
		}) ?>
	</tbody>
</table>