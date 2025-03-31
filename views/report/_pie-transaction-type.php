<?php

use app\widgets\PieChart;
?>
<div class="d-flex justify-content-around">
	<div class="">
		<?= PieChart::widget([
			'width' => 450,
			'data' => [
				'Emergency Welfare Program' => $data['total_emergency_welfare_program'],
				'Senior Citizen ID Application' => $data['total_senior_citizen_id_application'],
				'Social Pension' => $data['total_social_pension'],
				'Death Assistance' => $data['total_death_assistance'],
				'Certificate of Indigency' => $data['total_certificate_of_indigency'],
				'Financial Certification' => $data['total_financial_certification'],
				'Social Case Study Report' => $data['total_social_case_study_report'],
				'Certificate of Marriage Counseling' => $data['total_certificate_of_marriage_counseling'],
				'Certificate of Counseling' => $data['total_certificate_of_compliance'],
				'Certificate of Apparent Disability' => $data['total_certificate_of_apparent_disability'],
			]
		]) ?>
	</div>
	<div class="">
		<?= PieChart::widget([
			'width' => 325,
			'data' => [
				'Male' => $data['total_male'],
				'Female' => $data['total_female'],
			]
		]) ?>
	</div>
</div>