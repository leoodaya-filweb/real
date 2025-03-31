<?php

use app\widgets\TinyMce;
?>

<style>

table th, table td {
    border: 1px solid #ccc;
    padding: 0.1rem;
} 
    
    
</style>

<div id="solo-parent-application-form-<?= $widgetId ?>">
	<?= TinyMce::widget([
		'size' => 'A4',
	    'content' => $content,
	    'menubar' => false,
	    'toolbar' => 'print',
	    'height' => '600mm',
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