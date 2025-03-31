<?php

use app\widgets\TinyMce;
?>

<div id="dafac-<?= $widgetId ?>">
	<?= TinyMce::widget([
		'content' => $content,
		'landscapeA4' => true,
		'height' => '16in'
	]) ?>
</div>