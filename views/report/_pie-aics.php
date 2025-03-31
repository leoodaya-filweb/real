<?php

use app\widgets\PieChart;
?>
<div class="d-flex justify-content-around">
	<div class="">
		<?= PieChart::widget([
			'width' => 400,
			'data' => [
				'Medical Assistance (AICS- Medical Procedure )' => $data['total_medical'],
				'Medical Assistance (AICS - Medicine)' => $data['total_financial'],
				'Medical Assistance (AICS - Laboratory)' => $data['total_laboratory_request'],
				'AICS (Educational Assistance)' => $data['total_educational_assistance'],
				'AICS (Food Assistance)' => $data['total_food_assistance'],
				'AICS (Financial and Other Assistance)' => $data['total_finacial_and_other_assistance'],
			]
		]) ?>
	</div>
	<div class="">
		<?= PieChart::widget([
			'width' => 350,
			'data' => [
				'Male' => $data['total_male'],
				'Female' => $data['total_female'],
			]
		]) ?>
	</div>
</div>