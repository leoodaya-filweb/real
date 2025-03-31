<?php

use app\widgets\PieChart;
?>
<div class="d-flex justify-content-around">
	<div class="">
		<?= PieChart::widget([
			'width' => 480,
			'data' => [
				'Issuance of ID for Senior Citizens' => $data['total_senior_citizen_id_application'],
				'Issuance of Certificate of Indigency' => $data['total_certificate_of_indigency'],
				'Issuance of Certificate of Financial Capacity' => $data['total_financial_certification'],
				'Social Case Study Report' => $data['total_social_case_study_report'],
				'Certificate of Marriage Counseling' => $data['total_certificate_of_marriage_counseling'],
				'Certificate of Counseling' => $data['total_certificate_of_compliance'],
				'Certificate of Apparent Disability' => $data['total_certificate_of_apparent_disability'],
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