<?php

use app\helpers\App;
use app\helpers\Html;

$this->title = $model->nameWithExtension;

if (APP_ENV == 'local') {
	$this->registerJsFile(App::publishedUrl('/docx-preview/jszip.min.js', Yii::getAlias('@app/assets')));
	$this->registerJsFile(App::publishedUrl('/docx-preview/docx-preview.js', Yii::getAlias('@app/assets')));

	$this->registerJs(<<< JS
		KTApp.block('body', {
			overlayColor: '#000000',
			message: 'Please wait...',
			state: 'primary'
		});

		fetch('{$model->locationPath}')
			.then(res => res.blob()) // Gets the response and returns it as a blob
			.then(blob => {
				var doc = new File([blob], '{$model->nameWithExtension}', { lastModified: new Date().getTime(), type: blob.type });
		        //If Document not NULL, render it.
		        if (doc != null) {
		            //Set the Document options.
		            var docxOptions = Object.assign(docx.defaultOptions, {
		                useMathMLPolyfill: true
		            });
		            //Reference the Container DIV.
		            var container = document.querySelector("#word-container");

		            //Render the Word Document.
		            docx.renderAsync(doc, container, null, docxOptions);
		        }
		        KTApp.unblock('body');
			});
	JS);
}
?>

<?= Html::ifElse(APP_ENV == 'local', '<div id="word-container" class=""></div>', <<< HTML
	<iframe src="https://drive.google.com/viewerng/viewer?url={$model->locationPath}&embedded=true" height="100%" width="100%" frameborder="0"></iframe>
HTML) ?>