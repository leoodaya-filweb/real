<?php

use app\widgets\ImageGallery;

$this->registerCss(<<< CSS
	#sig-canvas-{$widgetId} {
		border: 2px dotted #CCCCCC;
		border-radius: 4px;
		cursor: crosshair;
	}
	#e-signature-{$widgetId} .form-group {
		margin-bottom:  0 !important;
	}
CSS);

$this->registerWidgetJs($widgetFunction, <<< JS
	(function() {
		window.requestAnimFrame = (function(callback) {
			return window.requestAnimationFrame ||
			window.webkitRequestAnimationFrame ||
			window.mozRequestAnimationFrame ||
			window.oRequestAnimationFrame ||
			window.msRequestAnimaitonFrame ||
			function(callback) {
				window.setTimeout(callback, 1000 / 60);
			};
		})();

		let fileIdInput = ['#e-signature-{$widgetId}', '.e-signature-imagegallery', '.file-id-input'].join(' ');

		var canvas = document.getElementById("sig-canvas-{$widgetId}");
		var ctx = canvas.getContext("2d");
		ctx.strokeStyle = "#222222";
		ctx.lineWidth = 4;

		var drawing = false;
		var mousePos = {
			x: 0,
			y: 0
		};
		var lastPos = mousePos;

		canvas.addEventListener("mousedown", function(e) {
			drawing = true;
			lastPos = getMousePos(canvas, e);
		}, false);

		canvas.addEventListener("mouseup", function(e) {
			drawing = false;
		}, false);

		canvas.addEventListener("mousemove", function(e) {
			mousePos = getMousePos(canvas, e);
		}, false);

		// Add touch event support for mobile
		canvas.addEventListener("touchstart", function(e) {
		}, false);

		canvas.addEventListener("touchmove", function(e) {
			var touch = e.touches[0];
			var me = new MouseEvent("mousemove", {
				clientX: touch.clientX,
				clientY: touch.clientY
			});
			canvas.dispatchEvent(me);
		}, false);

		canvas.addEventListener("touchstart", function(e) {
			mousePos = getTouchPos(canvas, e);
			var touch = e.touches[0];
			var me = new MouseEvent("mousedown", {
				clientX: touch.clientX,
				clientY: touch.clientY
			});
			canvas.dispatchEvent(me);
		}, false);

		canvas.addEventListener("touchend", function(e) {
			var me = new MouseEvent("mouseup", {});
			canvas.dispatchEvent(me);
		}, false);

		function getMousePos(canvasDom, mouseEvent) {
			var rect = canvasDom.getBoundingClientRect();
			return {
				x: mouseEvent.clientX - rect.left,
				y: mouseEvent.clientY - rect.top
			}
		}

		function getTouchPos(canvasDom, touchEvent) {
			var rect = canvasDom.getBoundingClientRect();
			return {
				x: touchEvent.touches[0].clientX - rect.left,
				y: touchEvent.touches[0].clientY - rect.top
			}
		}

		function renderCanvas() {
			if (drawing) {
				ctx.moveTo(lastPos.x, lastPos.y);
				ctx.lineTo(mousePos.x, mousePos.y);
				ctx.stroke();
				lastPos = mousePos;
			}
		}

		// Prevent scrolling when touching the canvas
		document.body.addEventListener("touchstart", function(e) {
			if (e.target == canvas) {
				e.preventDefault();
			}
		}, false);
		document.body.addEventListener("touchend", function(e) {
			if (e.target == canvas) {
				e.preventDefault();
			}
		}, false);
		document.body.addEventListener("touchmove", function(e) {
			if (e.target == canvas) {
				e.preventDefault();
			}
		}, false);

		document.body.addEventListener("mouseover", function(e) {
			if (e.target != canvas) {
				drawing = false;
				var me = new MouseEvent("mouseup", {});
				canvas.dispatchEvent(me);
			}
		}, false);

		(function drawLoop() {
			requestAnimFrame(drawLoop);
			renderCanvas();
		})();

		function clearCanvas() {
			canvas.width = canvas.width;
			ctx.strokeStyle = "#222222";
			ctx.lineWidth = 4;
		}

		// Set up the UI
		var clearBtn = document.getElementById("sig-clearBtn-{$widgetId}");
		var submitBtn = document.getElementById("sig-submitBtn-{$widgetId}");
		var container = '#e-signature-{$widgetId}';
		clearBtn.addEventListener("click", function(e) {
			clearCanvas();
			$('#e-signature-{$widgetId} input').val('{$model->{$attribute}}');
			{$clearJs}
		}, false);
		submitBtn.addEventListener("click", function(e) {

			let toDataURL = canvas.toDataURL();

            $('#e-signature-{$widgetId} input').val(toDataURL);
	        {$uploadSuccess}

	        toastr.options = {
			    "closeButton": false,
			    "debug": false,
			    "newestOnTop": true,
			    "positionClass": "toast-top-center",
			    "preventDuplicates": false,
			    "onclick": null,
			    "showDuration": "500",
			    "hideDuration": "3000",
			    "timeOut": "3000",
			    "extendedTimeOut": "1000"
			};
	        toastr.success("Signature Save!");

		}, false);
	})();
JS);
?>

<!-- Content -->
<div id="e-signature-<?= $widgetId ?>">

	<div class="row">
		<div class="col-md-12">
	 		<canvas id="sig-canvas-<?= $widgetId ?>" width="<?= $width ?>" height="<?= $height ?>">
	 		</canvas>
	 	</div>
	</div>

	<?= $form->field($model, $attribute)->hiddenInput()->label(false) ?>
	<div class="d-flex justify-content-center">
		<div>
			<button type="button" class="btn-sm font-weight-bold btn btn-success" id="sig-submitBtn-<?= $widgetId ?>">Submit Signature</button>
		</div>
		<div class="ml-2">
			<button type="button" class="btn-sm font-weight-bold btn btn-secondary" id="sig-clearBtn-<?= $widgetId ?>">Clear Signature</button>
		</div>
	</div>
</div>