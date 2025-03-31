<?php

use app\helpers\App;
use app\helpers\Html;

$this->registerCss(<<< CSS
	.table-summary td {
		{$td}
	}
CSS);
?>

<?= Html::if($title, <<< HTML
	<div style="{$tc_my10}">
		<p style="{$lead_fwb}">{$title}</p>
	</div>
HTML) ?>
<div class="">
	<table class="table-summary <?= $tableClass ?>" style="width: 100%;">
		<tbody>
			<tr>
				<td style="<?= $bt_bl_br ?>"></td> 
				<td colspan="3" style="<?= $bt_bl_br ?>"> </td>
				<td style="<?= $bt_br ?>"></td> 
				<td style="<?= $bt_bl_br ?>"></td> 
				<td style="<?= $bt_bl_br ?>"></td> 
				<td style="<?= $bt_bl_br ?>"></td> 
				<td style="<?= $bt_bl_br ?>"></td> 
				<td style="<?= $bt_bl_br ?>"></td> 
				<td style="<?= $bt_br_fwb ?>" colspan="3">
					As of: <?= $searchModel->currentWeek('F d', 'end') ?>,
					<?= App::formatter()->asDateToTimezone('', 'Y'); ?>
				</td>
			</tr>

			<tr style="<?= $bgy ?>">
				<td rowspan="3" style="<?= $fwbr_tc_w25p_bgy ?>">
					<span>CERTIFICATION</span>
				</td>
				<td colspan="3" style="<?= $fwbr_tc_bgy ?>"> Current Week </td>
				<td colspan="3" style="<?= $fwbr_tc_bgy ?>"> Month to date </td>
				<td colspan="3" style="<?= $fwbr_tc_bgy ?>"> Current Quarter </td>
				<td colspan="3" style="<?= $fwbr_tc_bgy ?>"> Year to date </td>
			</tr>
			<tr>
				<td colspan="3" class="text-center">
					<em><?= $searchModel->currentWeek('F d') ?></em>
				</td>
				<td colspan="3" class="text-center">
					<em><?= $searchModel->currentMonth('F d') ?></em>
				</td>
				<td colspan="3" class="text-center">
					<em><?= $searchModel->currentQuarter('F d') ?></em>
				</td>
				<td colspan="3" class="text-center">
					<em><?= $searchModel->currentYear('F d') ?></em>
				</td>
			</tr>
			<tr>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">M</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">F</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">Total</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">M</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">F</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">Total</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">M</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">F</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">Total</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">M</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">F</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">Total</td>
			</tr>

			<tr>
				<td style="<?= $fwbr_tc ?>">Issuance of ID for Senior Citizens</td>
				<td style="<?= $tr ?>"><?= Html::number($senior_citizen_id_application['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($senior_citizen_id_application['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($senior_citizen_id_application['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($senior_citizen_id_application['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($senior_citizen_id_application['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($senior_citizen_id_application['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($senior_citizen_id_application['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($senior_citizen_id_application['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($senior_citizen_id_application['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($senior_citizen_id_application['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($senior_citizen_id_application['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($senior_citizen_id_application['current_year']['Total']) ?: $default ?></td>
			</tr>

			<tr>
				<td style="<?= $fwbr_tc ?>">Issuance of Certificate of Indigency</td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_indigency['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_indigency['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_indigency['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_indigency['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_indigency['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_indigency['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_indigency['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_indigency['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_indigency['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_indigency['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_indigency['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_indigency['current_year']['Total']) ?: $default ?></td>
			</tr>

			<tr>
				<td style="<?= $fwbr_tc ?>">Issuance of Certificate of Financial Capacity</td>
				<td style="<?= $tr ?>"><?= Html::number($financial_certification['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($financial_certification['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($financial_certification['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($financial_certification['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($financial_certification['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($financial_certification['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($financial_certification['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($financial_certification['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($financial_certification['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($financial_certification['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($financial_certification['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($financial_certification['current_year']['Total']) ?: $default ?></td>
			</tr>

			<tr>
				<td style="<?= $fwbr_tc ?>">Social Case Study Report</td>
				<td style="<?= $tr ?>"><?= Html::number($social_case_study_report['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_case_study_report['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_case_study_report['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_case_study_report['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_case_study_report['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_case_study_report['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_case_study_report['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_case_study_report['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_case_study_report['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_case_study_report['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_case_study_report['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_case_study_report['current_year']['Total']) ?: $default ?></td>
			</tr>

			<tr>
				<td style="<?= $fwbr_tc ?>">Certificate of Marriage Counseling</td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_marriage_counseling['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_marriage_counseling['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_marriage_counseling['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_marriage_counseling['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_marriage_counseling['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_marriage_counseling['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_marriage_counseling['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_marriage_counseling['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_marriage_counseling['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_marriage_counseling['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_marriage_counseling['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_marriage_counseling['current_year']['Total']) ?: $default ?></td>
			</tr>

			<tr>
				<td style="<?= $fwbr_tc ?>">Certificate of Counseling</td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_compliance['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_compliance['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_compliance['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_compliance['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_compliance['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_compliance['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_compliance['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_compliance['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_compliance['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_compliance['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_compliance['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_compliance['current_year']['Total']) ?: $default ?></td>
			</tr>

			<tr>
				<td style="<?= $fwbr_tc ?>">Certificate of Apparent Disability</td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_apparent_disability['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_apparent_disability['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_apparent_disability['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_apparent_disability['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_apparent_disability['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_apparent_disability['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_apparent_disability['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_apparent_disability['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_apparent_disability['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_apparent_disability['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_apparent_disability['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($certificate_of_apparent_disability['current_year']['Total']) ?: $default ?></td>
			</tr>

			<tr>
				<td colspan="13"><br></td>
			</tr>

			<!--  -->
			<tr>
				<td class="text-center">
					<b>TOTAL</b>
				</td>
				<td class="text-right">
					<?= Html::number(
						($senior_citizen_id_application['current_week']['Male'] ?: $default)
						+ ($certificate_of_indigency['current_week']['Male'] ?: $default)
						+ ($financial_certification['current_week']['Male'] ?: $default)
						+ ($social_case_study_report['current_week']['Male'] ?: $default)
						+ ($certificate_of_marriage_counseling['current_week']['Male'] ?: $default)
						+ ($certificate_of_compliance['current_week']['Male'] ?: $default)
						+ ($certificate_of_apparent_disability['current_week']['Male'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($senior_citizen_id_application['current_week']['Female'] ?: $default)
						+ ($certificate_of_indigency['current_week']['Female'] ?: $default)
						+ ($financial_certification['current_week']['Female'] ?: $default)
						+ ($social_case_study_report['current_week']['Female'] ?: $default)
						+ ($certificate_of_marriage_counseling['current_week']['Female'] ?: $default)
						+ ($certificate_of_compliance['current_week']['Female'] ?: $default)
						+ ($certificate_of_apparent_disability['current_week']['Female'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($senior_citizen_id_application['current_week']['Total'] ?: $default)
						+ ($certificate_of_indigency['current_week']['Total'] ?: $default)
						+ ($financial_certification['current_week']['Total'] ?: $default)
						+ ($social_case_study_report['current_week']['Total'] ?: $default)
						+ ($certificate_of_marriage_counseling['current_week']['Total'] ?: $default)
						+ ($certificate_of_compliance['current_week']['Total'] ?: $default)
						+ ($certificate_of_apparent_disability['current_week']['Total'] ?: $default)
					) ?>
				</td>

				<!--  -->
				<td class="text-right">
					<?= Html::number(
						($senior_citizen_id_application['current_month']['Male'] ?: $default)
						+ ($certificate_of_indigency['current_month']['Male'] ?: $default)
						+ ($financial_certification['current_month']['Male'] ?: $default)
						+ ($social_case_study_report['current_month']['Male'] ?: $default)
						+ ($certificate_of_marriage_counseling['current_month']['Male'] ?: $default)
						+ ($certificate_of_compliance['current_month']['Male'] ?: $default)
						+ ($certificate_of_apparent_disability['current_month']['Male'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($senior_citizen_id_application['current_month']['Female'] ?: $default)
						+ ($certificate_of_indigency['current_month']['Female'] ?: $default)
						+ ($financial_certification['current_month']['Female'] ?: $default)
						+ ($social_case_study_report['current_month']['Female'] ?: $default)
						+ ($certificate_of_marriage_counseling['current_month']['Female'] ?: $default)
						+ ($certificate_of_compliance['current_month']['Female'] ?: $default)
						+ ($certificate_of_apparent_disability['current_month']['Female'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($senior_citizen_id_application['current_month']['Total'] ?: $default)
						+ ($certificate_of_indigency['current_month']['Total'] ?: $default)
						+ ($financial_certification['current_month']['Total'] ?: $default)
						+ ($social_case_study_report['current_month']['Total'] ?: $default)
						+ ($certificate_of_marriage_counseling['current_month']['Total'] ?: $default)
						+ ($certificate_of_compliance['current_month']['Total'] ?: $default)
						+ ($certificate_of_apparent_disability['current_month']['Total'] ?: $default)
					) ?>
				</td>

				<!--  -->
				<td class="text-right">
					<?= Html::number(
						($senior_citizen_id_application['current_quarter']['Male'] ?: $default)
						+ ($certificate_of_indigency['current_quarter']['Male'] ?: $default)
						+ ($financial_certification['current_quarter']['Male'] ?: $default)
						+ ($social_case_study_report['current_quarter']['Male'] ?: $default)
						+ ($certificate_of_marriage_counseling['current_quarter']['Male'] ?: $default)
						+ ($certificate_of_compliance['current_quarter']['Male'] ?: $default)
						+ ($certificate_of_apparent_disability['current_quarter']['Male'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($senior_citizen_id_application['current_quarter']['Female'] ?: $default)
						+ ($certificate_of_indigency['current_quarter']['Female'] ?: $default)
						+ ($financial_certification['current_quarter']['Female'] ?: $default)
						+ ($social_case_study_report['current_quarter']['Female'] ?: $default)
						+ ($certificate_of_marriage_counseling['current_quarter']['Female'] ?: $default)
						+ ($certificate_of_compliance['current_quarter']['Female'] ?: $default)
						+ ($certificate_of_apparent_disability['current_quarter']['Female'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($senior_citizen_id_application['current_quarter']['Total'] ?: $default)
						+ ($certificate_of_indigency['current_quarter']['Total'] ?: $default)
						+ ($financial_certification['current_quarter']['Total'] ?: $default)
						+ ($social_case_study_report['current_quarter']['Total'] ?: $default)
						+ ($certificate_of_marriage_counseling['current_quarter']['Total'] ?: $default)
						+ ($certificate_of_compliance['current_quarter']['Total'] ?: $default)
						+ ($certificate_of_apparent_disability['current_quarter']['Total'] ?: $default)
					) ?>
				</td>

				<!--  -->
				<td class="text-right">
					<?= Html::number(
						($senior_citizen_id_application['current_year']['Male'] ?: $default)
						+ ($certificate_of_indigency['current_year']['Male'] ?: $default)
						+ ($financial_certification['current_year']['Male'] ?: $default)
						+ ($social_case_study_report['current_year']['Male'] ?: $default)
						+ ($certificate_of_marriage_counseling['current_year']['Male'] ?: $default)
						+ ($certificate_of_compliance['current_year']['Male'] ?: $default)
						+ ($certificate_of_apparent_disability['current_year']['Male'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($senior_citizen_id_application['current_year']['Female'] ?: $default)
						+ ($certificate_of_indigency['current_year']['Female'] ?: $default)
						+ ($financial_certification['current_year']['Female'] ?: $default)
						+ ($social_case_study_report['current_year']['Female'] ?: $default)
						+ ($certificate_of_marriage_counseling['current_year']['Female'] ?: $default)
						+ ($certificate_of_compliance['current_year']['Female'] ?: $default)
						+ ($certificate_of_apparent_disability['current_year']['Female'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($senior_citizen_id_application['current_year']['Total'] ?: $default)
						+ ($certificate_of_indigency['current_year']['Total'] ?: $default)
						+ ($financial_certification['current_year']['Total'] ?: $default)
						+ ($social_case_study_report['current_year']['Total'] ?: $default)
						+ ($certificate_of_marriage_counseling['current_year']['Total'] ?: $default)
						+ ($certificate_of_compliance['current_year']['Total'] ?: $default)
						+ ($certificate_of_apparent_disability['current_year']['Total'] ?: $default)
					) ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>



