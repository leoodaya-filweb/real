<?php

use app\widgets\TinyMce;
?>

<div id="baktom-id-<?= $widgetId ?>">
	<?= TinyMce::widget([
		'size' => 'A4',
	    'zoom' => '50%',
	    'content' => $content,
	    'menubar' => false,
	    'toolbar' => 'print',
	    'height' => '200mm',
	    'plugins' =>  'print pagebreak',
	    'readonly' => true,
		'setup' => <<< JS
			editor.on('SkinLoaded', function() {
				$(".tox-toolbar-overlord").removeClass('tox-tbtn--disabled');
				$(".tox-toolbar-overlord").attr( 'aria-disabled', 'false' );
				// And activate ALL BUTTONS styles
				$(".tox-toolbar__group button").removeClass('tox-tbtn--disabled');
				$(".tox-toolbar__group button").attr( 'aria-disabled', 'false' );
			});
		JS
	]) ?>
</div>