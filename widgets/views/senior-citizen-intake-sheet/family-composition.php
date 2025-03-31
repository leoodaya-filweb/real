<?php

use app\helpers\Html;
?>

<table style="width: 100%;min-width: 100%;" border="1">
	<thead>
		<tr>
			<th style="text-align: center;">NO.</th>
			<th style="text-align: center;">NAME</th>
			<th style="text-align: center;">SEX</th>
			<th style="text-align: center;">DATE OF BIRTH</th>
			<th style="text-align: center;">AGE</th>
			<th style="text-align: center;">CIVIL STATUS</th>
			<th style="text-align: center;">RELATIONSHIP</th>
			<th style="text-align: center;">OCCUPATION</th>
			<th style="text-align: center;">MONTHLY INCOME</th>
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
					<td>{$member->birthDate}</td>
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