<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\File;

$this->registerCss(<<< CSS
	#printable {width: 100%; color: #000; font-size: 10pt; height: 100%;}
	#printable .hr-border {
		border: none; /* Remove default horizontal line */
		border-top: 1px solid #000; /* Set the top border to create the line */
		
	}
	#printable .hr-border-lg {
		border: none; /* Remove default horizontal line */
		border-top: 2px solid #e1e1e1;
       
	}
	#printable table {
		width: 100%;
	}
	.printpage { 
        width: 8.5in;
    }
	
	.print-content {
                position: relative;
                z-index: 100;
            }
	
	
@media print {
    .printfooter { 
        width: 8.5in;
    }
    .printpage { 
        width: 8.5in;
    }
}	
	
	
CSS);

$this->params['withHeader'] = false;

$file = File::controllerFind(App::setting('image')->footer_image, 'token');


$this->title = 'Post Activity Report: ' . $model->mainAttribute;

 
?>

<div id="printable" class="printpage" style="<?= $style ?? '' ?>   position: relative;">

	<table>
		<thead>
			<tr>
				<td>
					<div class="d-flex justify-content-between align-items-center" style="width: 400px; margin: auto;">
						<div>
							<?= Html::image(App::setting('image')->municipality_logo, ['w' => 100], [
								'class' => 'img-fluid'
							]) ?>
						</div>
						
						<div>
							<?= Html::image(App::setting('image')->social_welfare_logo, ['w' => 116], [
								'class' => 'img-fluid'
							]) ?>
						</div>
						<div>
							<?= Html::image(App::setting('image')->province_logo, ['w' => 96], [
								'class' => 'img-fluid'
							]) ?>
						</div>
						<div>
							<?= Html::image(App::setting('image')->philippines_logo, ['w' => 110], [
								'class' => 'img-fluid'
							]) ?>
						</div>
					</div>

					<div class="text-center mt-5">
						<h2 class="font-weight-bolder" style="font-family: 'times new roman', times, serif;">MUNICIPAL SOCIAL WELFARE AND DEVELOPMENT OFFICE</h2>
					</div>

					<hr class="hr-border-lg mb-10">
				
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td >
					<div class="text-center">
						<h3 class="font-weight-bolder mb-0">POST ACTIVITY REPORT</h3>
					</div>

					<div class="my-8">
						<table>
							<tbody>
								<tr>
									<td width="30%">DATE</td>
									<td width="10%">:</td>
									<td class="text-uppercase"><?= date('F d, Y', strtotime($model->date)) ?></td>
								</tr>
								<tr> <td colspan="3"><div class="my-3"></div></td> </tr>
								<tr>
									<td>FOR</td>
									<td>:</td>
									<td class="text-uppercase"><?= $model->for ?></td>
								</tr>
								<tr> <td colspan="3"><div class="my-3"></div></td> </tr>
								<tr>
									<td>SUBJECT</td>
									<td>:</td>
									<td class="text-uppercase"><?= $model->subject ?></td>
								</tr>
								<tr> <td colspan="3"><div class="my-3"></div></td> </tr>
								<tr>
									<td>TITLE OF ACTIVITY</td>
									<td>:</td>
									<td class="text-uppercase"><?= $model->title ?></td>
								</tr>
								<tr> <td colspan="3"><div class="my-3"></div></td> </tr>
								<tr>
									<td>LOCATION OF ACTIVITY</td>
									<td>:</td>
									<td class="text-uppercase"><?= $model->location ?></td>
								</tr>
								<tr> <td colspan="3"><div class="my-3"></div></td> </tr>
								<tr>
									<td>DATE OF ACTIVITY</td>
									<td>:</td>
									<td class="text-uppercase"><?= date('F d, Y', strtotime($model->date_of_activity)) ?></td>
								</tr>
								<tr> <td colspan="3"><div class="my-3"></div></td> </tr>
								<tr>
									<td>CONCERNED OFFICE</td>
									<td>:</td>
									<td class="text-uppercase"><?= $model->concerned_office ?></td>
								</tr>
								
							
							<?php
							/*
							if ($model->highlights_of_activity){?>
							   <tr> <td colspan="3"><div class="my-3"></div></td> </tr>
							 	<tr>
									<td class="align-baseline">HIGHLIGHTS OF ACTIVITY</td>
									<td class="align-baseline">:</td>
									<td class="text-capitalize align-baseline">
										<?= $model->highlights_of_activity[0] ?? '' ?>
									</td>
								</tr>
								<?php 
								
						
								
							echo App::foreach($model->highlights_of_activity, fn ($highlights, $index) => $index > 0 ? <<< HTML
									<tr>
										<td class="align-baseline"></td>
										<td class="align-baseline">:</td>
										<td class="text-capitalize align-baseline">
											{$highlights}
										</td>
									</tr>
								HTML: '');
							
							}	
							*/
								?>
								
							    <tr> <td colspan="3"><div class="my-3"></div></td> </tr>
							 	<tr>
									<td colspan="3" class="align-baseline py-3"><strong>HIGHLIGHTS OF ACTIVITY</strong></td>
							
								</tr>
								
								<tr>
									<td colspan="3">
									   <?php 
									   foreach($model->highlights_of_activity as $key=>$activity){
									     echo '<p style="text-indent: 50px;">'.$activity.'</p>';
									   } 
									   ?>   
									</td>
							
								</tr>
								
							</tbody>
						</table>
					</div>
					
					<?php 	
					$http_build_query = http_build_query($model->highlights_of_activity);
                    $totalChar= strlen($http_build_query);
                    
                    $imageFiles = $model->imageFiles;
                    if($imageFiles){
                   ?>
					<div class="photos-report <?= $totalChar>200?'break-before-report':null?>">
						<p class="mb-0" >PHOTOS:</p>
						<div class="d-flex flex-wrap justify-content-between" style="margin: 0px 15px;">
							<?= App::foreach($imageFiles, fn ($file) => Html::image($file, [
								'w' => 370], [
								'class' => 'img-thumbnail',
								'style' => 'max-width: 450px;height: fit-content; max-height: 278px;'
							])) ?>
						</div>
					</div>
					<?php } ?>
					

					<div class="mt-10">
						<p><?= nl2br($model->description); ?></p>
					</div>


					<div class="mt-10" style="padding-left: 50px;">
						<p>PREPARED BY:</p>
						<p class="mt-3">
							<span class="font-weight-bolder text-uppercase"><?= $model->prepared_by ?></span>
							<br><?= $model->prepared_by_position ?>
						</p>
					</div>

					<div class="mt-0 mb-20" style="padding-left: 500px;">
						<p>NOTED BY:</p>
						<p class="mt-3">
							<span class="font-weight-bolder text-uppercase"><?= $model->noted_by ?></span>
							<br><?= $model->noted_by_position ?>
						</p>
					</div>
				</td>
			</tr>
		</tbody>
	
	</table> 
	
	
	 
	
	
</div>