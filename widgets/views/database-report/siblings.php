<?php

use app\helpers\Html;
?>

<table class="table table-bordered table-head-solid">
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

			return $data['name'] == null ? '': <<< HTML
				<tr>
					<td> {$data['name']}</td>
					<td> {$data['gender']}</td>
					<td> {$data['age']}</td>
					<td> {$data['grade']}</td>
					<td> {$data['isy']}</td>
					<td> {$data['osy']}</td>
				</tr>
			HTML;
		}) ?>
	</tbody>
</table>