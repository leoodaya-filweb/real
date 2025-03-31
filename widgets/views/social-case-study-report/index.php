<?php

use app\widgets\TinyMce;
?>

<div id="social-case-study-report-<?= $widgetId ?>">
	<?= TinyMce::widget([
		'content' => $content
	]) ?>
</div>