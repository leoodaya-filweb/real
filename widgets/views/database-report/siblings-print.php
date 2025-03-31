<?php

use app\helpers\Html;
?>

<table style="border-collapse: collapse; width: 100%; height: 98px;" border="1">
    <tbody>
        <tr style="height: 10px;" data-mce-style="height: 22px;">
            <td style="width: 24.1237%; text-align: center; line-height: 20px; height: 20px; padding: 0 0.4rem;" rowspan="2"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Pangalan</span><br></td>
            <td style="width: 7.13773%; text-align: center; line-height: 20px; height: 10px; padding: 0 0.4rem;" colspan="2"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Kasarian</span></td>
            <td style="width: 12.5626%; text-align: center; line-height: 20px; height: 20px; padding: 0 0.4rem;" rowspan="2"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Edad</span><br></td>
            <td style="width: 9.6168%; text-align: center; line-height: 20px; height: 20px; padding: 0 0.4rem;" rowspan="2"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Grade / Year</span><br></td>
            <td style="width: 18.1508%; text-align: center; line-height: 20px; height: 20px; padding: 0 0.4rem;" rowspan="2"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">ISY</span><br></td>
            <td style="width: 9.28106%; text-align: center; line-height: 20px; height: 20px; padding: 0 0.4rem;" rowspan="2"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">OSY</span><br></td>
        </tr>
        <tr style="height: 10px;" data-mce-style="height: 10px;">
            <td style="width: 7.13773%; text-align: center; line-height: 20px; font-size: 12pt; height: 10px; padding: 0 0.4rem;"><span style="font-size: 12pt;" data-mce-style="font-size: 12pt;">Lalake</span></td>
            <td style="width: 5.93455%; text-align: center; line-height: 20px; font-size: 12pt; height: 10px; padding: 0 0.4rem;"><span style="font-size: 12pt;" data-mce-style="font-size: 12pt;">Babae</span></td>
        </tr>

        <?= Html::foreach($model->family_composition, function($data, $key) {
        	$male = Html::ifElse($data['gender'] == 'Male', Html::checkbox('Male', true), '');
        	$female = Html::ifElse($data['gender'] == 'Female', Html::checkbox('Female', true), '');

			return <<< HTML
				<tr style="height: 19px;" data-mce-style="height: 20px;">
					<td style="width: 24.1237%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem; height: 19px;">
						{$data['name']}
					</td>
					<td style="width: 7.13773%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem; height: 19px;">
						{$male}
					</td>
					<td style="width: 5.93455%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem; height: 19px;">
						{$female}
					</td>
					<td style="width: 12.5626%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem; height: 19px;">
						{$data['age']}
					</td>
					<td style="width: 9.6168%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem; height: 19px;">
						{$data['grade']}
					</td>
					<td style="width: 18.1508%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem; height: 19px;">
						{$data['isy']}
					</td>
					<td style="width: 9.28106%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem; height: 19px;">
						{$data['osy']}
					</td>
				</tr>
			HTML;
		}) ?> 
    </tbody>
</table>