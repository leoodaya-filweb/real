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
				<td colspan="7" style="<?= $bt_bl_br ?>"> </td>
				<td style="<?= $bt_br ?>"></td> 
				<td style="<?= $bt_bl_br ?>"></td> 
				<td style="<?= $bt_bl_br ?>"></td> 
				<td style="<?= $bt_bl_br ?>"></td> 
				<td style="<?= $bt_bl_br ?>"></td> 
				<td style="<?= $bt_bl_br ?>"></td> 
				<td style="<?= $bt_br_fwb ?>" colspan="4">
					As of: <?= $searchModel->currentWeek('F d', 'end') ?>,
					<?= App::formatter()->asDateToTimezone('', 'Y'); ?>
				</td>
			</tr>

			<tr style="<?= $bgy ?>">
				<td rowspan="3" style="<?= $fwbr_tc_w25p_bgy ?>">
					<span>EMERGENCY WELFARE PROGRAM</span>
				</td>
				<td colspan="4" style="<?= $fwbr_tc_bgy ?>"> Current Week </td>
				<td colspan="4" style="<?= $fwbr_tc_bgy ?>"> Month to date </td>
				<td colspan="4" style="<?= $fwbr_tc_bgy ?>"> Current Quarter </td>
				<td colspan="4" style="<?= $fwbr_tc_bgy ?>"> Year to date </td>
			</tr>

			<tr>
				<td colspan="4" class="text-center">
					<em><?= $searchModel->currentWeek('F d') ?></em>
				</td>
				<td colspan="4" class="text-center">
					<em><?= $searchModel->currentMonth('F d') ?></em>
				</td>
				<td colspan="4" class="text-center">
					<em><?= $searchModel->currentQuarter('F d') ?></em>
				</td>
				<td colspan="4" class="text-center">
					<em><?= $searchModel->currentYear('F d') ?></em>
				</td>
			</tr>
			<tr>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">M</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">F</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">Total</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">Disbursed</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">M</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">F</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">Total</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">Disbursed</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">M</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">F</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">Total</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">Disbursed</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">M</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">F</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">Total</td>
				<td style="<?= $fwbr_tc_bgg_w5p ?>">Disbursed</td>
			</tr>

			<tr>
				<td style="<?= $fwbr_tc ?>">Medical Assistance Program</td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_week']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_month']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_quarter']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_year']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($medical['current_year']['Total_Amount']) ?: $default ?></td>
			</tr>

			<tr>
				<td style="<?= $fwbr_tc ?>">Medical Assistance Program (Laboratory)</td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_week']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_month']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_quarter']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_year']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($laboratory_request['current_year']['Total_Amount']) ?: $default ?></td>
			</tr>
			<tr>
				<td style="<?= $fwbr_tc ?>">Balik Probinsya</td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_week']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_month']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_quarter']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_year']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($balik_probinsya['current_year']['Total_Amount']) ?: $default ?></td>
			</tr>
			<tr>
				<td style="<?= $fwbr_tc ?>">Death Benefit Assistance</td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_week']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_month']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_quarter']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_year']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($death_assistance['current_year']['Total_Amount']) ?: $default ?></td>
			</tr>
			<tr>
				<td style="<?= $fwbr_tc ?>">Social Pension Program</td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_week']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_month']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_quarter']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_year']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($social_pension['current_year']['Total_Amount']) ?: $default ?></td>
			</tr>
			
			
			
			
				<tr>
				<td style="<?= $fwbr_tc ?>">AICS (Educational Assistance)</td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_week']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_month']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_quarter']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_year']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($educational_assistance['current_year']['Total_Amount']) ?: $default ?></td>
			</tr>
			
			
			<tr>
				<td style="<?= $fwbr_tc ?>">AICS (Food Assistance)</td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_week']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_month']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_quarter']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_year']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($food_assistance['current_year']['Total_Amount']) ?: $default ?></td>
			</tr>
			
				<tr>
				<td style="<?= $fwbr_tc ?>">AICS (Financial and Other Assistance)</td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_week']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_week']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_month']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_month']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_quarter']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_quarter']['Total_Amount']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_year']['Total']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($finacial_and_other_assistance['current_year']['Total_Amount']) ?: $default ?></td>
			</tr>
			
			
			
			
			<tr>
				<td colspan="17"><br></td>
			</tr>
			<tr>
				<td class="text-center">
					<b>TOTAL</b>
				</td>
				<td class="text-right">
					<?= Html::number(
						($medical['current_week']['Male'] ?: $default)
						+ ($laboratory_request['current_week']['Male'] ?: $default)
						+ ($balik_probinsya['current_week']['Male'] ?: $default)
						+ ($death_assistance['current_week']['Male'] ?: $default)
						+ ($social_pension['current_week']['Male'] ?: $default)
						+ ($educational_assistance['current_week']['Male'] ?: $default)
						+ ($food_assistance['current_week']['Male'] ?: $default)
						+ ($finacial_and_other_assistance['current_week']['Male'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($medical['current_week']['Female'] ?: $default)
						+ ($laboratory_request['current_week']['Female'] ?: $default)
						+ ($balik_probinsya['current_week']['Female'] ?: $default)
						+ ($death_assistance['current_week']['Female'] ?: $default)
						+ ($social_pension['current_week']['Female'] ?: $default)
						+ ($educational_assistance['current_week']['Female'] ?: $default)
						+ ($food_assistance['current_week']['Female'] ?: $default)
						+ ($finacial_and_other_assistance['current_week']['Female'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($medical['current_week']['Total'] ?: $default)
						+ ($laboratory_request['current_week']['Total'] ?: $default)
						+ ($balik_probinsya['current_week']['Total'] ?: $default)
						+ ($death_assistance['current_week']['Total'] ?: $default)
						+ ($social_pension['current_week']['Total'] ?: $default)
						+ ($educational_assistance['current_week']['Total'] ?: $default)
						+ ($food_assistance['current_week']['Total'] ?: $default)
						+ ($finacial_and_other_assistance['current_week']['Total'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($medical['current_week']['Total_Amount'] ?: $default)
						+ ($laboratory_request['current_week']['Total_Amount'] ?: $default)
						+ ($balik_probinsya['current_week']['Total_Amount'] ?: $default)
						+ ($death_assistance['current_week']['Total_Amount'] ?: $default)
						+ ($social_pension['current_week']['Total_Amount'] ?: $default)
						+ ($educational_assistance['current_week']['Total_Amount'] ?: $default)
						+ ($food_assistance['current_week']['Total_Amount'] ?: $default)
						+ ($finacial_and_other_assistance['current_week']['Total_Amount'] ?: $default)
					) ?>
				</td>

				<!--  -->
				<td class="text-right">
					<?= Html::number(
						($medical['current_month']['Male'] ?: $default)
						+ ($laboratory_request['current_month']['Male'] ?: $default)
						+ ($balik_probinsya['current_month']['Male'] ?: $default)
						+ ($death_assistance['current_month']['Male'] ?: $default)
						+ ($social_pension['current_month']['Male'] ?: $default)
						+ ($educational_assistance['current_month']['Male'] ?: $default)
						+ ($food_assistance['current_month']['Male'] ?: $default)
						+ ($finacial_and_other_assistance['current_month']['Male'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($medical['current_month']['Female'] ?: $default)
						+ ($laboratory_request['current_month']['Female'] ?: $default)
						+ ($balik_probinsya['current_month']['Female'] ?: $default)
						+ ($death_assistance['current_month']['Female'] ?: $default)
						+ ($social_pension['current_month']['Female'] ?: $default)
						+ ($educational_assistance['current_month']['Female'] ?: $default)
						+ ($food_assistance['current_month']['Female'] ?: $default)
						+ ($finacial_and_other_assistance['current_month']['Female'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($medical['current_month']['Total'] ?: $default)
						+ ($laboratory_request['current_month']['Total'] ?: $default)
						+ ($balik_probinsya['current_month']['Total'] ?: $default)
						+ ($death_assistance['current_month']['Total'] ?: $default)
						+ ($social_pension['current_month']['Total'] ?: $default)
						+ ($educational_assistance['current_month']['Total'] ?: $default)
						+ ($food_assistance['current_month']['Total'] ?: $default)
						+ ($finacial_and_other_assistance['current_month']['Total'] ?: $default)
					) ?>
				</td>
				
				<td class="text-right">
					<?= Html::number(
						($medical['current_month']['Total_Amount'] ?: $default)
						+ ($laboratory_request['current_month']['Total_Amount'] ?: $default)
						+ ($balik_probinsya['current_month']['Total_Amount'] ?: $default)
						+ ($death_assistance['current_month']['Total_Amount'] ?: $default)
						+ ($social_pension['current_month']['Total_Amount'] ?: $default)
						+ ($educational_assistance['current_month']['Total_Amount'] ?: $default)
						+ ($food_assistance['current_month']['Total_Amount'] ?: $default)
						+ ($finacial_and_other_assistance['current_month']['Total_Amount'] ?: $default)
					) ?>
				</td>

				<!--  -->
				<td class="text-right">
					<?= Html::number(
						($medical['current_quarter']['Male'] ?: $default)
						+ ($laboratory_request['current_quarter']['Male'] ?: $default)
						+ ($balik_probinsya['current_quarter']['Male'] ?: $default)
						+ ($death_assistance['current_quarter']['Male'] ?: $default)
						+ ($social_pension['current_quarter']['Male'] ?: $default)
						+ ($educational_assistance['current_quarter']['Male'] ?: $default)
						+ ($food_assistance['current_quarter']['Male'] ?: $default)
						+ ($finacial_and_other_assistance['current_quarter']['Male'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($medical['current_quarter']['Female'] ?: $default)
						+ ($laboratory_request['current_quarter']['Female'] ?: $default)
						+ ($balik_probinsya['current_quarter']['Female'] ?: $default)
						+ ($death_assistance['current_quarter']['Female'] ?: $default)
						+ ($social_pension['current_quarter']['Female'] ?: $default)
						+ ($educational_assistance['current_quarter']['Female'] ?: $default)
						+ ($food_assistance['current_quarter']['Female'] ?: $default)
						+ ($finacial_and_other_assistance['current_quarter']['Female'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($medical['current_quarter']['Total'] ?: $default)
						+ ($laboratory_request['current_quarter']['Total'] ?: $default)
						+ ($balik_probinsya['current_quarter']['Total'] ?: $default)
						+ ($death_assistance['current_quarter']['Total'] ?: $default)
						+ ($social_pension['current_quarter']['Total'] ?: $default)
						+ ($educational_assistance['current_quarter']['Total'] ?: $default)
						+ ($food_assistance['current_quarter']['Total'] ?: $default)
						+ ($finacial_and_other_assistance['current_quarter']['Total'] ?: $default)
					) ?>
				</td>
				
				<td class="text-right">
					<?= Html::number(
						($medical['current_quarter']['Total_Amount'] ?: $default)
						+ ($laboratory_request['current_quarter']['Total_Amount'] ?: $default)
						+ ($balik_probinsya['current_quarter']['Total_Amount'] ?: $default)
						+ ($death_assistance['current_quarter']['Total_Amount'] ?: $default)
						+ ($social_pension['current_quarter']['Total_Amount'] ?: $default)
						+ ($educational_assistance['current_quarter']['Total_Amount'] ?: $default)
						+ ($food_assistance['current_quarter']['Total_Amount'] ?: $default)
						+ ($finacial_and_other_assistance['current_quarter']['Total_Amount'] ?: $default)
					) ?>
				</td>

				<!--  -->
				<td class="text-right">
					<?= Html::number(
						($medical['current_year']['Male'] ?: $default)
						+ ($laboratory_request['current_year']['Male'] ?: $default)
						+ ($balik_probinsya['current_year']['Male'] ?: $default)
						+ ($death_assistance['current_year']['Male'] ?: $default)
						+ ($social_pension['current_year']['Male'] ?: $default)
						+ ($educational_assistance['current_year']['Male'] ?: $default)
						+ ($food_assistance['current_year']['Male'] ?: $default)
						+ ($finacial_and_other_assistance['current_year']['Male'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($medical['current_year']['Female'] ?: $default)
						+ ($laboratory_request['current_year']['Female'] ?: $default)
						+ ($balik_probinsya['current_year']['Female'] ?: $default)
						+ ($death_assistance['current_year']['Female'] ?: $default)
						+ ($social_pension['current_year']['Female'] ?: $default)
						+ ($educational_assistance['current_year']['Female'] ?: $default)
						+ ($food_assistance['current_year']['Female'] ?: $default)
						+ ($finacial_and_other_assistance['current_year']['Female'] ?: $default)
					) ?>
				</td>
				<td class="text-right">
					<?= Html::number(
						($medical['current_year']['Total'] ?: $default)
						+ ($laboratory_request['current_year']['Total'] ?: $default)
						+ ($balik_probinsya['current_year']['Total'] ?: $default)
						+ ($death_assistance['current_year']['Total'] ?: $default)
						+ ($social_pension['current_year']['Total'] ?: $default)
						+ ($educational_assistance['current_year']['Total'] ?: $default)
						+ ($food_assistance['current_year']['Total'] ?: $default)
						+ ($finacial_and_other_assistance['current_year']['Total'] ?: $default)
					) ?>
				</td>
				
				<td class="text-right">
					<?= Html::number(
						($medical['current_year']['Total_Amount'] ?: $default)
						+ ($laboratory_request['current_year']['Total_Amount'] ?: $default)
						+ ($balik_probinsya['current_year']['Total_Amount'] ?: $default)
						+ ($death_assistance['current_year']['Total_Amount'] ?: $default)
						+ ($social_pension['current_year']['Total_Amount'] ?: $default)
						+ ($educational_assistance['current_year']['Total_Amount'] ?: $default)
						+ ($food_assistance['current_year']['Total_Amount'] ?: $default)
						+ ($finacial_and_other_assistance['current_year']['Total_Amount'] ?: $default)
					) ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>



