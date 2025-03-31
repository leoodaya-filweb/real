<?php

use app\widgets\TinyMce;
?>

<div id="financial-certification-<?= $widgetId ?>">
	<?= TinyMce::widget([
		'content' => $content
	]) ?>
</div>