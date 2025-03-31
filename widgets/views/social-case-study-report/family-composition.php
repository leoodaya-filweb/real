<?php

use app\helpers\Html;
?>

<table style="width: 100%;min-width: 100%;" border="1">
	<thead>
		<tr>
			<th style="text-align: left;">NO.</th>
			<th style="text-align: left;">NAME</th>
			<th style="text-align: left;">SEX</th>
			<th style="text-align: left;">AGE</th>
			<th style="text-align: left;">CIVIL STATUS</th>
			<th style="text-align: left;">RELATIONSHIP</th>
			<th style="text-align: left;">OCCUPATION</th>
			<th style="text-align: left;">INCOME</th>
		</tr>
	</thead>
	<tbody>
		<?= Html::foreach($model->myFamilyCompositions, function($member, $index) use($model) {
			$key = $index + 1;
			return <<< HTML
				<tr>
					<td>{$key}</td>
					<td>{$member->name}</td>
					<td>{$member->genderName}</td>
					<td>{$member->currentAge}</td>
					<td>{$member->civilStatusName}</td>
					<td>{$model->relationTo($member)}</td>
					<td>{$member->occupationName}</td>
					<td>{$member->monthlyIncome}</td>
				</tr>
			HTML;
		}) ?>
	</tbody>
</table>