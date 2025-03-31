<?php

use app\widgets\TinyMce;
?>

<div id="petty-cash-voucher-<?= $widgetId ?>">
	<?= TinyMce::widget([
		'content' => $content
	]) ?>
</div>