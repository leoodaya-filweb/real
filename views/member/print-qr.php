<?php

use app\helpers\Html;

$this->registerJs(<<< JS
	$('#print-qr').click(function() {
		$(".qr-code-container").printThis();	
	})
JS)
?>

<div class="qr-code-container">
	<div id="qr-code-inner-container" style="
		text-align: center;
		display: grid;
		grid-template-columns: 20% 20% 20% 20% 20%;
		">
		<?= Html::foreach($models, function($model) {
			return <<< HTML
				<div class="mx-5">
					{$model->getQrCodeImage(['width' => 150, 'height' => 150, 'style' => 'outline: 2px solid'])}
					<input type="text" class="app-hidden" value="{$model->id}" name="id[]">
					<p class="font-weight-bolder mt-2">{$model->name}<br>
						<small>{$model->qr_id}</small>
					</p>
				</div>
			HTML;
		}) ?>
	</div>
</div>

<button type="button" id="print-qr" class="btn btn-secondary font-weight-bolder">
	PRINT
</button>
