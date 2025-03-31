<?php

use app\helpers\Html;
?>

<ol>
	<?= Html::foreach($requirements, function($requirement) {
		return <<< HTML
			<li>{$requirement['name']} </li>
		HTML;
	}) ?>
</ol>
