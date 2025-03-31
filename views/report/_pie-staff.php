<?php

use app\widgets\PieChart;



  foreach($data['users'] as $key=>$row){ 
    
    
  
        $userdata[$row->fullname]=  $data['total_'.$row->fullname];// $data[$row['username']]['current_year']['Total'];
    
    }
//print_r( $userdata);

?>
<div class="d-flex justify-content-around">
	<div class="">
		<?= PieChart::widget([
			'width' => 400,
			'data' => $userdata
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