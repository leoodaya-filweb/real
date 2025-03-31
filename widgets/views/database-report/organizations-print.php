<?php

use app\helpers\Html;
?>

<table style="border-collapse: collapse; width: 100%; height: 66px;" border="1">
    <tbody>
        <tr style="height: 22px;" data-mce-style="height: 22px;">
            <td style="text-align: center;line-height: 20px;width: 33.33%;padding: 0px 0.4rem;height: 16px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Pangalan ng Organisasyon</span></td>
            <td style="text-align: center;line-height: 20px;width: 33.33%;padding: 0px 0.4rem;"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Posisyong hinawakan kung mayroon</span></td>
            <td style="text-align: center;line-height: 20px;width: 33.33%;padding: 0px 0.4rem;height: 16px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;" data-mce-style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Taon</span></td>
        </tr>

		<?= Html::foreach($model->organizations, function($data, $key) {
			return <<< HTML
				<tr style="height: 20px;" data-mce-style="height: 20px;">
					<td style="width: 12.5%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem; height: 10px;">
						{$data['name']}
					</td>
					<td style="width: 12.5%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem;">
						{$data['position']}
					</td>
					<td style="width: 25%; text-align: center; line-height: 20px; font-size: 12pt; padding: 0px 0.4rem; height: 10px;">
						{$data['year']}
					</td>
				</tr>
			HTML;
		}) ?>
    </tbody>
</table>