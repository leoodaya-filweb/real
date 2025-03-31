<?php
use app\widgets\PieChart;
use app\helpers\Html;
?>
<?php foreach ($features as $feature): ?>
	<div class="d-flex">
		<div>
			<div class="color-box" style="background: <?= $feature['properties']['color'] ?>;"></div>
		</div>
		<div class="ml-3">
			<span class="font-weight-bold"><strong><?= $feature['properties']['barangay'] ?> (<?= $feature['properties']['percentage'] ?>%)</strong></span>
		</div>
	</div>

	<table class="ml-10">
		<tbody>
			<?php 
			$total= filter_var($feature['properties']['household'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);  
			foreach ($feature['properties']['household_colors'] as $hc): 
			  //$total+= filter_var($hc['total'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
			?>
				<tr>
					<td >
						<div class="color-box" style="background: <?= $hc['color'] ?>;"></div> 
					</td>
					<td width="20%"> <?= $hc['label'] ?> </td>
					<th width="40%"><?= $hc['total'] ?>  (<?=   $total>0?round((filter_var($hc['total'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND)/$total)*100):0;  ?>%)</th>
					<td width="25%"> 
						<label class="badge badge-info ml-5 view-badge">
							<a href="<?= $feature['properties']['url_link'] ?>">View</a>
						</label>
					</td>
				</tr>
			<?php endforeach ?>
			<tr>
			    <td colspan="2">
					Total Voters
				</td>
				<td colspan="2">
					<strong><?= $feature['properties']['household'] ?></strong>
				</td>
			</tr>    
		</tbody>
	</table>
	<hr/>
<?php endforeach ?>
