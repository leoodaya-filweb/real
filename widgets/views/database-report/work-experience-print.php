<?php

use app\helpers\Html;
?>

<table style="border-collapse: collapse; width: 100%; height: 60px;" border="1">
    <tbody>
        <tr style="height: 13px;" data-mce-style="height: 13px;">
            <td style="text-align: center;line-height: 20px;width: 25%;padding: 0px 0.4rem;height: 13px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Taon at Buwan</span></td>
            <td style="text-align: center;line-height: 20px;width: 25%;padding: 0px 0.4rem;"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Titulo sa Trabaho</span></td>
            <td style="text-align: center; line-height: 20px; width: 25%; padding: 0px 0.4rem; height: 13px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Buwanang kita</span></td>
            <td style="text-align: center; line-height: 20px; width: 25%; padding: 0px 0.4rem; height: 13px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Dahilan ng pag-alis</span></td>
        </tr>

        <?= Html::foreach($model->work_experience, function($data, $key) {
			return <<< HTML
				<tr style="height: 20px;" data-mce-style="height: 20px;">
					<td style="width: 12.5%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem; height: 10px;">
						{$data['year_month']}
					</td>
					<td style="width: 12.5%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem;">
						{$data['job_title']}
					</td>
					<td style="width: 25%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem; height: 10px;">
						{$data['monthly_income']}
					</td>
					<td style="width: 25%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem; height: 10px;">
						{$data['reason_for_leaving']}
					</td>
				</tr>
			HTML;
		}) ?>
    </tbody>
</table>