<?php
use app\widgets\PieChart;
use app\helpers\Html;


$total_voters=0;
foreach ($features as $key=>$feature){
      $total_voters +=filter_var($feature['properties']['household'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);

      foreach ($feature['properties']['household_colors'] as $hc){ 
            // $data[]=[ $hc['label']=>filter_var($hc['total'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND) ];
            if($data[$hc['label']]){
               $data[$hc['label']]= $data[$hc['label']] + filter_var($hc['total'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND); 
            }else{
              $data[$hc['label']]=filter_var($hc['total'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
            }
            
            if($key==0){
             $barangay = $feature['properties']['barangay'];
            }else{
             $barangay = 'All';   
            }
      }

}
//echo json_encode($data);

echo 'Barangay: '.$barangay;
echo '<br/>Purok: '.($queryParams['purok']?:'All');
echo '<br/>Total Voters: '. number_format($total_voters);
?>


	<?= PieChart::widget([
	        'width' => 330,
	        'datasort'=>false,
	        'colors'=>['#181c32','#e4e6ef','#1bc5bd','#f64e60'],
			//'data' => ['Black' => 5,	'Gray' => 50,	'Green' => 20,'Red'=>5]
			'data' => $data
		]) ?>
