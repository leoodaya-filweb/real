<?php

use app\widgets\TinyMce;
?>

<div id="certificate-of-marriage-counseling-<?= $widgetId ?>">
	<?= TinyMce::widget([
		'content' => $content
	]) ?>
</div>