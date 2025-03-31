<?php

use app\helpers\Html;
?>

<table class="table table-bordered table-head-solid">
	<thead>
		<tr>
			<?= Html::foreach($th, function($h) {
				return Html::tag('th', $h);
			}) ?>
		</tr>
	</thead>
	<tbody>
		<?= Html::foreach($td, function($d) {
			return Html::tag('tr', Html::foreach($d, function($data) {
				return Html::tag('td', $data);
			}));
		}) ?>
	</tbody>
</table>