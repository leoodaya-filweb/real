<?php

use app\helpers\App;
use app\helpers\Html;
?>
<table style="border-collapse: collapse; width: 100%; height: 25px;" border="1">
	<tbody>
		<tr style="height: 22px;" data-mce-style="height: 22px;">
		  <td style="width: 6.67715%; text-align: center; line-height: 16px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">No</span></td>
		  <td style="width: 24.1237%; text-align: center; line-height: 16px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Name</span></td>
		  <td style="width: 7.13773%; text-align: center; line-height: 16px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Birthdate</span></td>
		  <td style="width: 5.93455%; text-align: center; line-height: 16px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Age</span></td>
		  <td style="width: 12.5626%; text-align: center; line-height: 16px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Civil Status</span></td>
		  <td style="width: 12.5626%; text-align: center; line-height: 16px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Educational Attainment</span></td>
		  <td style="width: 9.6168%; text-align: center; line-height: 16px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Relationship</span></td>
		  <td style="width: 18.1508%; text-align: center; line-height: 16px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Occupation</span></td>
		  <td style="width: 9.28106%; text-align: center; line-height: 16px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Monthly Income</span></td>
		</tr>

		<?= Html::foreach($model->family_composition, function($data, $key) {
			return <<< HTML
				<tr>
					<td style="width: 6.67715%;text-align: center;line-height: 16px;font-size: 10pt;">
						<span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">{$data['no']}</span>
					</td>
					<td style="width: 24.1237%;text-align: center;line-height: 16px;font-size: 10pt;">
						{$data['name']}
					</td>
					<td style="width: 7.13773%;text-align: center;line-height: 16px;font-size: 10pt;">
						{$data['birth_date']}
					</td>
					<td style="width: 5.93455%;text-align: center;line-height: 16px;font-size: 10pt;">
						{$data['age']}
					</td>
					<td style="width: 12.5626%;text-align: center;line-height: 16px;font-size: 10pt;">
						{$data['civil_status']}
					</td>
					<td style="width: 12.5626%;text-align: center;line-height: 16px;font-size: 10pt;">
						{$data['educational_attainment']}
					</td>
					<td style="width: 9.6168%;text-align: center;line-height: 16px;font-size: 10pt;">
						{$data['relationship']}
					</td>
					<td style="width: 18.1508%;text-align: center;line-height: 16px;font-size: 10pt;">
						{$data['occupation']}
					</td>
					<td style="width: 9.28106%;text-align: center;line-height: 16px;font-size: 10pt;">
						{$data['income']}
					</td>
				</tr>
			HTML;
		}) ?>

	

		</tbody>
</table>