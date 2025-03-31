<?php

use app\widgets\PieChart;
?>
<div class="d-flex justify-content-around">
	<div class="">
		<?= PieChart::widget([
			'width' => 450,
			'data' => [
				'Medical Assistance Program' => $data['total_medical'],
				'Medical Assistance Program (Laboratory)' => $data['total_laboratory_request'],
				'Balik Probinsya' => $data['total_balik_probinsya'],
				'Death Benefit Assistance' => $data['total_death_assistance'],
				'Social Pension Program' => $data['total_social_pension'],
				'Educational Assistance Program' => $data['total_educational_assistance'],
				'Food Assistance Program' => $data['total_food_assistance'],
				'Finacial and Other Assistance Program' => $data['total_finacial_and_other_assistance'],
			]
		]) ?>
	</div>
	<div class="">
		<?= PieChart::widget([
			'data' => [
				'Male' => $data['total_male'],
				'Female' => $data['total_female'],
			]
		]) ?>
	</div>
</div>