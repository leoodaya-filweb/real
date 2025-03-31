<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;

$bg = Url::image(App::setting('image')->id_template);
 
$this->registerCss(<<< CSS
	.table th, .table td {
		padding: 0.35rem !important;
	}
CSS);

$this->registerJs(<<< JS
	if($('.municipal-container').length) {
		window.print();
	}
JS);

?>

<div class="municipal-container">
	<div id="qr-code-inner-container">
		<?= Html::foreach($models, function($model) use($bg) {
			$date = date('m/d/Y');
			return <<< HTML
				<div class="mx-5 id-template" style="max-width: 600px">
					<img src="{$bg}" style="max-width: 600px">
					<table class="table table-bordered" style="
						width: 100%;
					    margin-top: -247px;
					    position: absolute;
					    max-width: 600px;
						">
						<tbody>
							<tr>
								<td rowspan="6" width="100" class="text-center">
									{$model->getImage(100, ['style' => 'margin-left: 6px;margin-top: 2px;'])}
									<b>{$model->qr_id}</b>
								</td>
								<td colspan="4">Last Name, First Name, M.I.</td>
							</tr>
							<tr>
								<td colspan="4">
									<b>{$model->name}</b>
								</td>
							</tr>
							<tr>
								<td width="110">Sex</td>
								<td>Date of Birth</td>
								<td>Civil Status</td>
								<td rowspan="4" class="text-center" width="120">
									{$model->getQrCodeImage(['width' => 120, 'height' => 120, 'style' => 'outline: 2px solid'])}
								</td>
							</tr>
							<tr>
								<td><b>{$model->sexLabel}</b></td>
								<td><b>{$model->birth_date}</b></td>
								<td><b>{$model->civilStatusName}</b></td>
							</tr>
							<tr>
								<td>Household No</td>
								<td colspan="2">Date Issued</td>
							</tr>
							<tr>
								<td><b>{$model->householdNo}</b></td>
								<td colspan="2"><b>{$date}</b></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="mt-10"></div>
			HTML;
		}) ?>
	</div>
</div>