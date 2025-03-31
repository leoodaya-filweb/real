<?php

use app\widgets\Map;
use app\helpers\Html;
?>
<h4 class="mb-10 font-weight-bold text-dark">Review household Details and Submit</h4>

<div class="row">
	<div class="col-md-5">
		<h6 class="font-weight-bolder mb-3">
			General Information:
			<?= Html::a('<i class="fa fa-edit text-warning" title="Edit" data-toggle="tooltip"></i>', [$action, 'no' => $household->no, 'step' => 'general-information']) ?>
		</h6>
		<?= $householdContent ?>

		<div class="separator separator-dashed my-10"></div>
		<h6 class="font-weight-bolder mb-3">
			Map Plot:
			<?= Html::a('<i class="fa fa-edit text-warning" title="Edit" data-toggle="tooltip"></i>', [$action, 'no' => $household->no, 'step' => 'map']) ?>
		</h6>
		<?= $mapContent ?>
	</div>
	<div class="col">
		<?= Map::widget([
			'longitude' => $household->longitude,
			'latitude' => $household->latitude,
			'template' => 'summary-view'
		]) ?>
	</div>
</div>


<div class="separator separator-dashed my-10"></div>
<h6 class="font-weight-bolder mb-3">
	Family Head Details:
	<?= Html::a('<i class="fa fa-edit text-warning" title="Edit" data-toggle="tooltip"></i>', [$action, 'no' => $household->no, 'step' => 'family-head']) ?>
</h6>
<?= $familyHeadContent ?>

<?= Html::if($familyCompositionsContent, function() use($familyCompositionsContent, $household, $familyCompositions, $action) {
	$a = Html::a('<i class="fa fa-edit text-warning" title="Edit" data-toggle="tooltip"></i>', [$action, 'no' => $household->no, 'step' => 'family-composition']);
	$t = number_format($household->totalFamilyComposition);
	return <<< HTML
		<div class="separator separator-dashed my-10"></div>
		<h6 class="font-weight-bolder mb-3">Family Compositions ({$t}): {$a}</h6>
		{$familyCompositionsContent}
	HTML;
}) ?>
