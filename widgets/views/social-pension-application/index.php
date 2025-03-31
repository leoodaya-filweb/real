<?php

use app\widgets\TinyMce;
?>

<div id="general-intake-sheet-<?= $widgetId ?>">
	<?= TinyMce::widget([
		'content' => $content
	]) ?>
</div>