<?php

use app\widgets\TinyMce;
?>

<div id="certificate-of-apparent-disability-<?= $widgetId ?>">
	<?= TinyMce::widget([
		'content' => $content
	]) ?>
</div>