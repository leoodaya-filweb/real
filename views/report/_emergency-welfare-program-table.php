<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Transaction;

$this->registerCss(<<< CSS
	.table-summary td {
		border:  1px solid #000;
		padding:  3px 5px;
		color: #000;
	}
	.table-summary .bt {border-top: 1px solid transparent !important;}
	.table-summary .bb {border-bottom: 1px solid transparent !important;}
	.table-summary .bl {border-left: 1px solid transparent !important;}
	.table-summary .br {border-right: 1px solid transparent !important;}
	.table-summary .bgy {background-color: #fccc00;}
	.table-summary .bgg {background-color: #70ad46;}
	.table-summary .bggrey {background-color: #d9d9d9;}
	.table-summary .cgrey {color: #d9d9d9;}
CSS);

$total_cell = isset($type) && $type == 'sheet' ? '50px': '5%';
$first_cell = isset($type) && $type == 'sheet' ? '300px': '25%';


$lr = $lr ?? $searchModel->emergency_welfare_program_data(Transaction::AICS_LABORATORY_REQUEST);
$medical = $medical ?? $searchModel->emergency_welfare_program_data(Transaction::AICS_MEDICAL);
$financial = $financial ?? $searchModel->emergency_welfare_program_data(Transaction::AICS_FINANCIAL);
$bp = $bp ?? $searchModel->emergency_welfare_program_data(Transaction::BALIK_PROBINSYA_PROGRAM);
?>
<?php if (isset($title)): ?>
	<div class="text-center my-10">
		<p class="lead font-weight-bold"><?= $title ?></p>
	</div>
<?php endif ?>

<div class="table-responsive">
	<table class="table-summary <?= $class ?? '' ?>" style="width: 100%">
		<tbody>
			<tr>
				<td class="bt bl"></td> 
				<td colspan="3" class="text-center font-weight-bold">
					<?= $searchModel->currentWeek('F d') ?>
				</td>
				<td class="bt br"></td> 
				<td class="bt bl br"></td> 
				<td class="bt bl br"></td> 
				<td class="bt bl br"></td> 
				<td class="bt bl br"></td> 
				<td class="bt bl br"></td> 
				<td class="bt br font-weight-bold" colspan="3">As of: <?= App::formatter()->asDateToTimezone('', 'Y'); ?></td>
			</tr>

			<tr class="bgy">
				<td rowspan="2" class="font-weight-bolder text-center p25" width="<?= $first_cell ?>">
					<span>EMERGENCY WELFARE PROGRAM </span>
					<br>(Assistance to Individuals in Crisis Situation)
				</td>
				<td colspan="3" class="font-weight-bolder text-center"> Current Week </td>
				<td colspan="3" class="font-weight-bolder text-center"> Month to date </td>
				<td colspan="3" class="font-weight-bolder text-center"> Current Quarter </td>
				<td colspan="3" class="font-weight-bolder text-center"> Year to date </td>
			</tr>
			<tr>
				<td class="font-weight-bolder text-center bgg tdtag" width="<?= $total_cell ?>">M</td>
				<td class="font-weight-bolder text-center bgg tdtag" width="<?= $total_cell ?>">F</td>
				<td class="font-weight-bolder text-center bgg tdtag" width="<?= $total_cell ?>">Total</td>
				<td class="font-weight-bolder text-center bgg tdtag" width="<?= $total_cell ?>">M</td>
				<td class="font-weight-bolder text-center bgg tdtag" width="<?= $total_cell ?>">F</td>
				<td class="font-weight-bolder text-center bgg tdtag" width="<?= $total_cell ?>">Total</td>
				<td class="font-weight-bolder text-center bgg tdtag" width="<?= $total_cell ?>">M</td>
				<td class="font-weight-bolder text-center bgg tdtag" width="<?= $total_cell ?>">F</td>
				<td class="font-weight-bolder text-center bgg tdtag" width="<?= $total_cell ?>">Total</td>
				<td class="font-weight-bolder text-center bgg tdtag" width="<?= $total_cell ?>">M</td>
				<td class="font-weight-bolder text-center bgg tdtag" width="<?= $total_cell ?>">F</td>
				<td class="font-weight-bolder text-center bgg tdtag" width="<?= $total_cell ?>">Total</td>
			</tr>

			<tr>
				<td colspan="13" class="bggrey">
					<span class="cgrey">_</span>
				</td>
			</tr>

			<tr>
				<td class="text-center font-weight-bolder"  style="text-align: center;">Medical Assistance (AICS)</td>
				<td class="text-right"><?= $medical['current_week']['Male'] ?></td>
				<td class="text-right"><?= $medical['current_week']['Female'] ?></td>
				<td class="text-right"><?= $medical['current_week']['Total'] ?></td>
				<td class="text-right"><?= $medical['current_month']['Male'] ?></td>
				<td class="text-right"><?= $medical['current_month']['Female'] ?></td>
				<td class="text-right"><?= $medical['current_month']['Total'] ?></td>
				<td class="text-right"><?= $medical['current_quarter']['Male'] ?></td>
				<td class="text-right"><?= $medical['current_quarter']['Female'] ?></td>
				<td class="text-right"><?= $medical['current_quarter']['Total'] ?></td>
				<td class="text-right"><?= $medical['current_year']['Male'] ?></td>
				<td class="text-right"><?= $medical['current_year']['Female'] ?></td>
				<td class="text-right"><?= $medical['current_year']['Total'] ?></td>
			</tr>

			<tr>
				<td class="text-center font-weight-bolder">Medical Assistance - Laboratory (AICS)</td>
				<td class="text-right"><?= $lr['current_week']['Male'] ?></td>
				<td class="text-right"><?= $lr['current_week']['Female'] ?></td>
				<td class="text-right"><?= $lr['current_week']['Total'] ?></td>
				<td class="text-right"><?= $lr['current_month']['Male'] ?></td>
				<td class="text-right"><?= $lr['current_month']['Female'] ?></td>
				<td class="text-right"><?= $lr['current_month']['Total'] ?></td>
				<td class="text-right"><?= $lr['current_quarter']['Male'] ?></td>
				<td class="text-right"><?= $lr['current_quarter']['Female'] ?></td>
				<td class="text-right"><?= $lr['current_quarter']['Total'] ?></td>
				<td class="text-right"><?= $lr['current_year']['Male'] ?></td>
				<td class="text-right"><?= $lr['current_year']['Female'] ?></td>
				<td class="text-right"><?= $lr['current_year']['Total'] ?></td>
			</tr>
			<tr>
				<td class="text-center font-weight-bolder">Financial Assistance (AICS)</td>
				<td class="text-right"><?= $financial['current_week']['Male'] ?></td>
				<td class="text-right"><?= $financial['current_week']['Female'] ?></td>
				<td class="text-right"><?= $financial['current_week']['Total'] ?></td>
				<td class="text-right"><?= $financial['current_month']['Male'] ?></td>
				<td class="text-right"><?= $financial['current_month']['Female'] ?></td>
				<td class="text-right"><?= $financial['current_month']['Total'] ?></td>
				<td class="text-right"><?= $financial['current_quarter']['Male'] ?></td>
				<td class="text-right"><?= $financial['current_quarter']['Female'] ?></td>
				<td class="text-right"><?= $financial['current_quarter']['Total'] ?></td>
				<td class="text-right"><?= $financial['current_year']['Male'] ?></td>
				<td class="text-right"><?= $financial['current_year']['Female'] ?></td>
				<td class="text-right"><?= $financial['current_year']['Total'] ?></td>
			</tr>
			<tr>
				<td class="text-center font-weight-bolder">Balik Probinsya</td>
				<td class="text-right"><?= $bp['current_week']['Male'] ?></td>
				<td class="text-right"><?= $bp['current_week']['Female'] ?></td>
				<td class="text-right"><?= $bp['current_week']['Total'] ?></td>
				<td class="text-right"><?= $bp['current_month']['Male'] ?></td>
				<td class="text-right"><?= $bp['current_month']['Female'] ?></td>
				<td class="text-right"><?= $bp['current_month']['Total'] ?></td>
				<td class="text-right"><?= $bp['current_quarter']['Male'] ?></td>
				<td class="text-right"><?= $bp['current_quarter']['Female'] ?></td>
				<td class="text-right"><?= $bp['current_quarter']['Total'] ?></td>
				<td class="text-right"><?= $bp['current_year']['Male'] ?></td>
				<td class="text-right"><?= $bp['current_year']['Female'] ?></td>
				<td class="text-right"><?= $bp['current_year']['Total'] ?></td>
			</tr>
		</tbody>
	</table>
</div>



