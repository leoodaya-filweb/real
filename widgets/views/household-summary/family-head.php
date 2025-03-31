<?php

use app\helpers\App;
use app\helpers\Url;
use app\helpers\Html;

?>

<div class="text-dark-50 line-height-lg">
	<?= Html::if(!$member->isHead, $member->relationTag . '&nbsp; ' . Html::a('<i class="fa fa-edit text-warning" title="Edit" data-toggle="tooltip"></i>', [$action, 'no' => $household->no, 'step' => 'family-composition'])) ?>
	<div class="row">
		<div class="col">
			<?= Html::if($member->qr_id, function() use ($member) {
				$a = Html::a('Download QR Code', $member->downloadQrCodeUrl); 
				return <<< HTML
					<div> <b>QR Code ID:</b> {$member->qr_id} | {$a} </div>
				HTML;
			}) ?>
			<div> <b>Fullname:</b> <?= strtoupper($member->fullname) ?: 'None' ?> </div>
			<div> <b>Sex:</b> <?= $member->genderName ?: 'None' ?> </div>
			<div> <b>Civil Status:</b> <?= $member->civilStatusName ?: 'None' ?> </div>
			<div> <b>Birth Date:</b> <?= $member->birth_date ?: 'None' ?> </div>
			<div> <b>Birth Place:</b> <?= $member->birth_place ?: 'None' ?> </div>
			<div> <b>Educational Attainment:</b> <?= $member->educationalAttainmentLabel ?: 'None' ?> </div>
		</div>
		<div class="col">
			<div class="ribbon ribbon-right">
				<?= Html::if($key, function() use($key) {
					return Html::tag('div', $key, [
						'class' => 'ribbon-target bg-danger',
						'style' => 'top: 10px; right: -2px;'
					]);
				}) ?>
				<a href="<?= Url::to(['file/download', 'token' => $member->photo ?: App::setting('image')->image_holder]) ?>">
					<?= Html::image($member->photo, ['w' => 110], [
						'title' => 'Member\'s Photo',
						'data-content' => 'Click to download',
						'data-toggle' => 'popover',
						'data-placement' => 'top',
					]) ?>
				</a>
				<a href="<?= $member->downloadQrCodeUrl ?>">
					<?= Html::img($member->qrCode, [
						'width' => 130,
						'title' => 'Member\'s QR Code',
						'data-content' => 'Click to download',
						'data-toggle' => 'popover',
						'data-placement' => 'top',
					]) ?>
				</a>
			</div>
		</div>
	</div>

	<div class="my-2"></div>
	<div> <b>Contact:</b>  </div>
	<ul>
		<li> <b>Email:</b> <?= $member->email ?: 'None' ?> </li>
		<li> <b>Mobile Number:</b> <?= $member->contact_no ?: 'None' ?> </li>
	</ul>

	<div class="my-2"></div>
	<div> <b>Occupation:</b>  </div>
	<ul>
		<li> <b>Occupation:</b> <?= $member->occupation ?: 'None' ?> </li>
		<li> <b>Monthly Income:</b> <?= $member->monthlyIncome ?: 'None' ?> </li>
		<li> <b>Souce of Income:</b> <?= $member->source_of_income ?: 'None' ?> </li>
	</ul>

	<div class="my-2"></div>
	<div> <b>Pension:</b>  </div>
	<ul>
		<li> <b>Pensioner Tag:</b> <?= $member->pensionerTag ?: 'None' ?> </li>
		<li> <b>Pensioner From:</b> <?= $member->pensioner_from ?: 'None' ?> </li>
		<li> <b>Monthly Pension:</b> <?= $member->monthlyPensionAmount ?: 'None' ?> </li>
	</ul>
</div>
	
