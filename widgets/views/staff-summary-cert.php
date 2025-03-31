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
					<span>STAFF NAME</span>
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

       <?php  foreach($data['users'] as $key=>$row){
       
         $row['username']=$row->fullname;
       ?>

			<tr>
				<td style="<?= $fwbr_tc ?>"><?php echo  $row->fullname; //$row['username']?></td>
				<td style="<?= $tr ?>"><?= Html::number($data[$row['username']]['current_week']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($data[$row['username']]['current_week']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($data[$row['username']]['current_week']['Total']) ?: $default ?></td>
				
				<td style="<?= $tr ?>"><?= Html::number($data[$row['username']]['current_month']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($data[$row['username']]['current_month']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($data[$row['username']]['current_month']['Total']) ?: $default ?></td>
				
				<td style="<?= $tr ?>"><?= Html::number($data[$row['username']]['current_quarter']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($data[$row['username']]['current_quarter']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($data[$row['username']]['current_quarter']['Total']) ?: $default ?></td>
			
				<td style="<?= $tr ?>"><?= Html::number($data[$row['username']]['current_year']['Male']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($data[$row['username']]['current_year']['Female']) ?: $default ?></td>
				<td style="<?= $tr ?>"><?= Html::number($data[$row['username']]['current_year']['Total']) ?: $default ?></td>
				
			</tr>
    <?php 
    
    
        $current_week_male +=$data[$row['username']]['current_week']['Male'];
        $current_week_fmale +=$data[$row['username']]['current_week']['Female'];
        $current_week_total +=$data[$row['username']]['current_week']['Total'];
        $current_week_total_amount +=$data[$row['username']]['current_week']['Total_Amount'];
        
        $current_month_male +=$data[$row['username']]['current_month']['Male'];
        $current_month_fmale +=$data[$row['username']]['current_month']['Female'];
        $current_month_total +=$data[$row['username']]['current_month']['Total'];
        $current_month_total_amount +=$data[$row['username']]['current_month']['Total_Amount'];
        
        $current_quarter_male +=$data[$row['username']]['current_quarter']['Male'];
        $current_quarter_fmale +=$data[$row['username']]['current_quarter']['Female'];
        $current_quarter_total +=$data[$row['username']]['current_quarter']['Total'];
        $current_quarter_total_amount +=$data[$row['username']]['current_quarter']['Total_Amount'];
        
        $current_year_male +=$data[$row['username']]['current_year']['Male'];
        $current_year_fmale +=$data[$row['username']]['current_year']['Female'];
        $current_year_total +=$data[$row['username']]['current_year']['Total'];
        $current_year_total_amount +=$data[$row['username']]['current_year']['Total_Amount'];
    
    } ?>

      

			<tr>
				<td colspan="14"><br></td>
			</tr>
			<tr>
				<td class="text-center">
					<b>TOTAL</b>
				</td>
				<td class="text-right">
					<?= Html::number($current_week_male) ?>
				</td>
				<td class="text-right">
					<?= Html::number($current_week_fmale) ?>
				</td>
				<td class="text-right">
					<?= Html::number($current_week_total) ?>
				</td>
				
				<!--  -->
				<td class="text-right">
					<?= Html::number($current_month_male) ?>
				</td>
				<td class="text-right">
					<?= Html::number($current_month_fmale) ?>
				</td>
				<td class="text-right">
					<?= Html::number($current_month_total) ?>
				</td>
				
			

				<!--  -->
				<td class="text-right">
					<?= Html::number($current_quarter_male) ?>
				</td>
				<td class="text-right">
					<?= Html::number($current_quarter_fmale) ?>
				</td>
				<td class="text-right">
					<?= Html::number($current_quarter_total) ?>
				</td>
				

				<!--  -->
					<td class="text-right">
					<?= Html::number($current_year_male) ?>
				</td>
				<td class="text-right">
					<?= Html::number($current_year_fmale) ?>
				</td>
				<td class="text-right">
					<?= Html::number($current_year_total) ?>
				</td>
				
			</tr>
		</tbody>
	</table>
</div>



