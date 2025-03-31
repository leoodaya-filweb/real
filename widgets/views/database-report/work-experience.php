<?php

use app\helpers\Html;
?>

<table class="table table-bordered table-head-solid">
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
			return $data['year_month'] == null ? '': <<< HTML
				<tr>
					<td> {$data['year_month']} </td>
					<td> {$data['job_title']} </td>
					<td> {$data['monthly_income']} </td>
					<td> {$data['reason_for_leaving']} </td>
				</tr>
			HTML;
		}) ?>
	</tbody>
</table>