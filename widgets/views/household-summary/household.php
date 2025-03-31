<?php

use app\helpers\Html;
?>

<div class="text-dark-50 line-height-lg">
	<div> <b>Household Number:</b> <?= $household->no ?: 'None' ?> </div>
	<div> <b>Transfer Date:</b> <?= $household->transfer_date ?: 'None' ?> </div>

	<div class="my-2"></div>
	<div> <b>Address</b></div>
	<ul>
		<li> <b>Region:</b> <?= $household->regionName ?: 'None' ?> </li>
		<li> <b>Province:</b> <?= $household->provinceName ?: 'None' ?> </li>
		<li> <b>Municipality:</b> <?= $household->municipalityName ?: 'None' ?> </li>
		<li> <b>Barangay:</b> <?= $household->barangayName ?: 'None' ?> </li>
		<li> <b>Purok:</b> <?= $household->purok_no ?: 'None' ?> </li>
		<li> <b>Blk No:</b> <?= $household->blk_no ?: 'None' ?> </li>
		<li> <b>Lot No:</b> <?= $household->lot_no ?: 'None' ?> </li>
		<li> <b>Street:</b> <?= $household->street ?: 'None' ?> </li>
		<li> <b>Zone:</b> <?= $household->zone_no ?: 'None' ?> </li>
	</ul>
	<?= Html::if(($imageFiles = $household->imageFiles) != null, function() use($imageFiles) {
		$images = Html::foreach($imageFiles, function($file) {
			$image = Html::image($file, ['w' => 50], ['class' => 'img-fluid']);
			return <<< HTML
				<a href="{$file->viewerUrl}" target="_blank">
					{$image}
				</a>
			HTML;
		});
		return <<< HTML
			<div> <b>Images</b></div>
			{$images}
		HTML;
	}) ?>
</div>