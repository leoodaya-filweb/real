<?php

use app\helpers\App;
use yii\helpers\Url;

$this->registerJs(<<< JS
	$('a.anchor-ajax-form').on('click', function() {
		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'warning',
			message: 'Loading form...'
		});
 
		let a = $(this);

		$.ajax({
			url: a.data('url'),
			method: 'get',
			dataType: 'json',
			success: function(s) {
				$('#add-entry-modal .modal-title').html('Add New ' + a.data('title'))
				$('#add-entry-modal .modal-body').html(s.form)

				$('#add-entry-modal .modal-dialog').removeClass('modal-lg');
				$('#add-entry-modal .modal-dialog').addClass(a.data('size'));

				$('.kt-selectpicker').selectpicker();
				$('#add-entry-modal').modal('show');
				KTApp.unblockPage();
			},
			error: function(e) {
				Swal.fire("Error", e.responseText, "error");
				KTApp.unblockPage();
			}
		});
	})
JS, \yii\web\View::POS_END, 'anchor-ajax-form');
?>

<?php if (App::access()->userCanRoute($url)): ?>
	
	<div class="mb-10" style="margin-top: -10px">
	    <a href="#" 
	        class="badge badge-secondary anchor-ajax-form" 
	        data-size="<?= $size ?? '' ?>"
	        data-url="<?= Url::to($url) ?>"
	        data-title="<?= $title ?>">
	        Add New <?= $title ?>
	    </a>
	</div>

<?php endif ?>
