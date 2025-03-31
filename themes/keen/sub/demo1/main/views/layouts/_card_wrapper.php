<?php

use app\helpers\Html;
$toolbar = $toolbar ?? '';
$stretch = $stretch ?? false;
?>

<div class="card card-custom <?= $stretch ? 'card-stretch': '' ?> gutter-b <?= $class ?? '' ?>">
	<?= Html::if(($title = $title ?? '') != null, function() use($title, $toolbar) {
		return <<< HTML
			<div class="card-header">
				<div class="card-title">
					<h3 class="card-label">{$title}</h3>
				</div>
				{$toolbar}
			</div>
		HTML;
	}) ?>

    <div class="card-body">
		<?= $content ?> 
	</div>
</div>