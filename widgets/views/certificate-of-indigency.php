<?php

use app\widgets\TinyMce;
?>

<div id="certificate-of-indigency-<?= $widgetId ?>">
	<?= TinyMce::widget([
		'content' => $content
	]) ?>
</div>