<?php

use app\helpers\Html;
use yii\helpers\Html AS YiiHtml;


$this->registerCss(<<< CSS
    .app-alert {
        background: #fff;
        padding: 15px;
        box-shadow: rgb(0 0 0 / 20%) 0px 1px 4px 0;
        display: inline-flex;
        width: 100%;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        margin-bottom: 1rem;
        border-radius: 4px;
    }
    .app-alert .content-alert {margin-bottom: 0;}
    .app-alert .head-alert {font-size: 1.2rem; font-weight: 500;}
    .app-alert .close-alert {margin: auto 0; cursor: pointer;}
    .primary-alert {border-left: 4px solid #4085f0;}
    .success-alert {border-left: 4px solid #1BC5BD;}
    .warning-alert {border-left: 4px solid #ffbc46;}
    .info-alert {border-left: 4px solid #00b5e5;}
    .danger-alert {border-left: 4px solid #ff3a46;}
    .primary-alert .head-alert i {color: #4085f0;}
    .success-alert .head-alert i {color: #1BC5BD;}
    .warning-alert .head-alert i {color: #ffbc46;}
    .info-alert .head-alert i {color: #00b5e5;}
    .danger-alert .head-alert i {color: #ff3a46;}
CSS);

$this->registerWidgetJs($widgetFunction, <<< JS
	let video = document.querySelector("#{$videoOptions['id']}"),
		click_button = document.querySelector("#{$buttonOptions['id']}"),
		canvas = document.querySelector("#{$canvasOptions['id']}"),
		loading = document.querySelector("#webcam-container-{$widgetId} .loading"),
		input = $('#webcam-container-{$widgetId} .model-name-input');

	$(document).on('click', '.close-alert', function() {
        $(this).closest('.app-alert').remove();
    });

	// input.val('{$modelName}-webcam-' + Date.now());
	
	let initCamera = async function() {
	   	let stream = null;

	    try {
	    	stream = await navigator.mediaDevices.getUserMedia({ 
	    		video:  {width: {$videoOptions['width']}, height: {$videoOptions['height']}, facingMode: "user"}, 
	    		audio: false, 
    		});
	    	let settings = stream.getTracks()[0].getSettings();

	    	// stream.getTracks()[0].applyConstraints({ advanced : [{ brightness: 20 }] });
	    	// let getCapabilities = stream.getTracks()[0].getCapabilities();

	    	// canvas.width = settings.width
	    	// canvas.height = settings.height

	    	canvas.width = settings.width
	    	canvas.height = settings.height
	    }
	    catch(e) {
            // $('#webcam-container-{$widgetId}').html('');
            let html = '<div class="app-alert danger-alert" id="camera-{$widgetId}">';
			    html += '<div>';
			        html += '<div class="head-alert">';
			            html += '<i class="fas fa-window-close"></i> ';
			            html += 'Camera Issue';
			        html += '</div>';
			        html += '<p class="content-alert">';
			            html += e.message;
			        html += '</p>';
			    html += '</div>';
			    html += '<div class="close-alert"> <i class="ki ki-close"></i> </div>';
			html += '</div>';
            $('#webcam-container-{$widgetId}').html(html);
	    	return;
	    }

	    video.srcObject = stream;

	    video.style.display = 'block';
	    click_button.style.display = 'block';
	    loading.style.display = 'none';
	}
	{$initFunction}
	// initCamera();

	if(click_button) {
		click_button.addEventListener('click', function() {

			if(input.length && ! input.val()) {
				Swal.fire({
			        icon: "warning",
			        title: "Please input file name",
			        showConfirmButton: false,
			        timer: 1500
			    });

				return false;
			}

			KTApp.block('#webcam-container-{$widgetId}', {
				overlayColor: '#000000',
				state: 'primary',
				message: 'Uploading...'
			});
		    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
		   	let blobData = canvas.toBlob(function(blob) {
		   		const formData = new FormData();
		   		let modelName = input.val();
		   		modelName = (modelName)? modelName: '{$modelName}-webcam-' + Date.now();

		    	formData.append('UploadForm[tag]', '{$tag}');
		    	formData.append('UploadForm[modelName]', '{$modelName}');
	        	formData.append('UploadForm[fileInput]', blob, modelName + '.jpeg');

		        $.ajax({
		    		url: app.baseUrl + 'file/upload',
		            method: 'POST',
		            data: formData,
		            processData: false,
		            contentType: false,
		            dataType: 'json',
		            success: function(s) {
		                if(s.status == 'success') {
		                   	{$ajaxSuccess}
		                }
		                else {
		                    alert(s.message);
		                }
						KTApp.unblock('#webcam-container-{$widgetId}');
		            },
		            error:function(e) {
		                alert(e.responseText);
						KTApp.unblock('#webcam-container-{$widgetId}');
		            },
		        });
		   	}, 'image/jpeg');
		});
	}

	input.on('keydown', function(e) {
		if (e.keyCode == 13) {
			e.preventDefault();
	   		click_button.click()
		}
	});
JS, \yii\web\View::POS_END);

?>

<div id="webcam-container-<?= $widgetId ?>">
	<div class="loading">Camera is loading...</div>
	<?= Html::tag('video', '', $videoOptions) ?>

		<div class="<?= $withNameInput ? 'input-group': '' ?> mt-3 webcam-input-group text-center">
			<?= Html::if($withNameInput, Html::input('text', 'modelName', $inputValue, [
				'class' => 'model-name-input form-control',
				'placeholder' => 'Enter File name',
				'required' => true,
			])) ?>
			<div class="input-group-append">
				<?= YiiHtml::a($buttonOptions['value'], "#!", $buttonOptions) ?>
			</div>
		</div>

	<?= Html::tag('canvas', '', $canvasOptions) ?>
    <?= Html::if($withInput, function() use($model, $attribute) {
    	return Html::activeInput('hidden', $model, $attribute, [
	    	'class' => 'webcam-file-input'
	    ]);
    }) ?>
</div>


