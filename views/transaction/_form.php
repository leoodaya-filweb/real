<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Member;
use app\widgets\ActiveForm;
use app\widgets\Autocomplete;
use app\widgets\BootstrapSelect;
use app\widgets\Dropzone;
use app\widgets\Webcam;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */
/* @var $form app\widgets\ActiveForm */

$this->registerJs( <<< JS
	let viewMember = function() {
		KTApp.block('.container-member-detail', {
			overlayColor: '#000000',
			state: 'warning',
			message: 'Please wait...'
		});
		$.ajax({
			url: app.baseUrl + 'member/detail',
			data: {
				qr_id: $('#transaction-qr_id').val(),
				template: '_detail-no-action'
			},
			dataType: 'json',
			success: function(s) {
				if (s.status == 'success') {
					$('.container-member-detail').html(s.detailView);
				}
				else {
					Swal.fire('Error', s.error, 'error');
				}
				KTApp.unblock('.container-member-detail');
			},
			error: function(e) {
				Swal.fire('Error', e.responseText, 'error');
				KTApp.unblock('.container-member-detail');
			}
		});
	}
	$('#btn-search-qr').click(function() {
		viewMember();
	});

	$('#transaction-qr_id').on('keyup', function(e) {
		if (e.keyCode === 13) {
			viewMember();
			e.target.focus();
      		e.target.select();
	    }
	});

	$('form input:not([type="submit"])').keydown((e) => {
        if (e.keyCode === 13) {
            e.preventDefault();
            return false;
        }
        return true;
    });

    $(document).on('click', '.btn-remove-file', function() {
    	var self = this;
    	Swal.fire({
	        title: "Are you sure?",
	        text: "You won\"t be able to revert this!",
	        icon: "warning",
	        showCancelButton: true,
	        confirmButtonText: "Yes, delete it!",
	        cancelButtonText: "No, cancel!",
	        reverseButtons: true
	    }).then(function(result) {
	        if (result.value) {
	            $(self).closest('.file-container').remove();
	            Swal.fire({
			        icon: "success",
			        title: "Deleted",
	        		text: "Your file has been deleted.",
			        showConfirmButton: false,
			        timer: 1500
			    });
	        }
	    });
    	
	});
JS);
?>
<?php $form = ActiveForm::begin(['id' => 'transaction-form']); ?>
    <div class="row">
    	<div class="col-md-5">
        	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
        		'title' => 'Transaction Form'
        	]); ?>
	        	<?= Html::ifElse($withSlug, $form->field($model, 'qr_id')->textInput([
	        		'readonly' => true
	        	]), function() use($model, $form) {
	        		return Autocomplete::widget([
		        		'submitOnclickJs' => <<< JS
							KTApp.block('.container-member-detail', {
								overlayColor: '#000000',
								state: 'warning',
								message: 'Please wait...'
							});

							$.ajax({
								url: app.baseUrl + 'member/detail',
								data: {
									qr_id: inp.value,
									template: '_detail-no-action'
								},
								dataType: 'json',
								success: function(s) {
									if (s.status == 'success') {
										$('.container-member-detail').html(s.detailView);
									}
									else {
										Swal.fire('Error', s.error, 'error');
									}
									KTApp.unblock('.container-member-detail');
								},
								error: function(e) {
									Swal.fire('Error', e.responseText, 'error');
									KTApp.unblock('.container-member-detail');
								}
							});
						JS,
		        		'input' => $form->field($model, 'qr_id', [
							'template' => <<< HTML
								{label}
								<div class="input-group">
									{input}
									<div class="input-group-append">
										<button class="btn btn-primary" type="button" id="btn-search-qr">
											Search
										</button>
									</div>
								</div>
								{error}
							HTML
		        		])->textInput([
		        			'autofocus' => true
		        		]),
		        		'url' => Url::to(['member/find-qr-by-keywords']),
		        	]);
	        	}) ?>

				<?= $form->field($model, 'amount')->textInput([
					'type' => 'number'
				]) ?>
				
	        	
				<?= $form->field($model, 'remarks')->textarea(['rows' => 8]) ?>

				<label class="lead font-weight-bold">Scan Documents</label>
				
			    <?= Webcam::widget([
			    	'tag' => 'Transaction',
                    'withInput' => false,
                    'model' => $model,
                    'attribute' => 'files[]',
                    'buttonOptions' => [
                        'class' => 'btn btn-primary btn-sm mt-3',
                        'value' => 'Capture',
                        'style' => 'max-width: 200px;margin: 0 auto;',
                    ],
                    'ajaxSuccess' => <<< JS
                        let html = '<div class="image-input image-input-outline file-container">';
                        	html += '<div class="image-input-wrapper" style="background-image: url('+  s.src +');width: 200px"></div>';
                        	html += '<label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow btn-remove-file" data-action="change" data-toggle="tooltip" title="" data-original-title="Remove">';
                        		html += '<i class="fa fa-trash icon-sm text-danger"></i>';
                        	html += '</label>';
                        	html += '<input type="text" class="app-hidden" name="Transaction[files][]" value="'+ s.file.token +'">';
                        	html += '<a title="View: '+ s.file.name +'" target="_blank" href="'+ s.src +'&rotate=-90"><p class="badge badge-secondary mb-1">'+ s.file.truncatedName +'</p></a>';
                        html += '</div>';
                        $('.files-container').append(html); 
                    JS
                ]) ?>

                <hr>
                <div class="files-container">
                	<?= Html::foreach($model->imageFiles, function($file) {
						return <<< HTML
							<div class="image-input image-input-outline file-container">
								<div class="image-input-wrapper" style="background-image: url({$file->getUrlImage(['w' => 200])});width: 200px"></div>
								<label class="btn btn-xs btn-icon btn-circle btn-white text-dangerbtn-hover-text-primary btn-shadow btn-remove-file" data-action="change" data-toggle="tooltip" title="" data-original-title="Remove">
									<i class="fa fa-trash icon-sm text-danger"></i>
								</label>
								<input type="text" class="app-hidden" name="Transaction[files][]" value="{$file->token}">
								
								<a title="View: {$file->name}" target="_blank" href="{$file->urlImage}">
									<p class="badge badge-secondary mb-1">{$file->truncatedName}</p>
								</a>
							</div>
						HTML;
                	}) ?>
                </div>
                <hr>
			    <div class="form-group mt-10">
					<?= ActiveForm::buttons('lg') ?>
			    </div>
			<?php $this->endContent(); ?> 
        </div>
        <div class="col">
        	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
        		'title' => 'Member\'s Information'
        	]); ?>
        		<div class="container-member-detail">
		        	<?= Html::ifElse($member, function() use($member) {
		        		return $this->render('/member/_detail-no-action', [
		                    'model' => $member,
		                ]);
					}, <<< HTML
						<div class="text-center">
							<h4>Member Primary Details</h4>
							<i class="far fa-user-circle mt-5" style="font-size: 12em"></i>
						</div>
					HTML) ?>
        		</div>
			<?php $this->endContent(); ?> 
        </div>
    </div>

<?php ActiveForm::end(); ?>


<div class="modal fade" id="add-entry-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" data-scroll="true">
                
            </div>
        </div>
    </div>
</div>
