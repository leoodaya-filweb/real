<?php

use app\widgets\TinyMce;
?>

<div id="certificate-of-compliance-<?= $widgetId ?>">
	<?= TinyMce::widget([
		'content' => $content
	]) ?>
</div>