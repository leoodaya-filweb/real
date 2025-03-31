<?php

use app\helpers\Html;
?>

<table class="table table-bordered table-head-solid">
	<thead>
		<tr>
			<th>Pangalan ng Organisasyon</th>
			<th>Posisyong hinawakan kung mayroon</th>
			<th>Taon</th>
		</tr>
	</thead>
	<tbody>
		<?= Html::foreach($model->organizations, function($data, $key) {
			return $data['name'] == null ? '': <<< HTML
				<tr>
					<td> {$data['name']} </td>
					<td> {$data['position']} </td>
					<td> {$data['year']} </td>
				</tr>
			HTML;
		}) ?>
	</tbody>
</table>
