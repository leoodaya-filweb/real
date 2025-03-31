<?php

use app\helpers\App;
use app\helpers\Html;
?>
<p class="font-weight-bold">
	Date: (<?= $start ?> - <?= $end ?>)
</p>
<table class="table table-bordered table-head-solid">
	<thead>
		<tr>
			<th>STATUS</th>
			<th class="text-right">MALE</th>
			<th class="text-right">FEMALE</th>
			<th class="text-right">TOTAL</th>
		</tr>
	</thead>
	<tbody>
		<?= Html::foreach($data, function($d, $status) use($default) {
			$total = isset($d['total'])? Html::number($d['total']) : $default;
			$male = isset($d['Male'])? Html::number($d['Male']) : $default;
			$female = isset($d['Female'])? Html::number($d['Female']) : $default;
			return <<< HTML
				<tr>
					<td>{$status}</td>
					<td class="text-right">{$male}</td>
					<td class="text-right">{$female}</td>
					<td class="text-right">{$total}</td>
				</tr>
			HTML;
		}) ?>
	</tbody>
	<thead>
		<tr>
			<th>TOTAL</th>
			<th class="text-right"><?= $total_male ?: $default ?></th>
			<th class="text-right"><?= $total_female ?: $default ?></th>
			<th class="text-right"><?= $total_record ?: $default ?></th>
		</tr>
	</thead>
</table>