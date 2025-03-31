<?php

use app\helpers\Html;
use app\models\Barangay;
use app\models\CivilStatus;
use app\models\EducationalAttainment;
use app\models\Sex;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;
use app\widgets\DatePicker;
use app\widgets\Dropzone;
use app\widgets\ImageGallery;
use app\widgets\Webcam;

/* @var $this yii\web\View */
/* @var $model app\models\Masterlist */
/* @var $form app\widgets\ActiveForm */

$this->registerJs(<<< JS
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

                KTApp.block('body', {
                    overlayColor: '#000000',
                    state: 'warning',
                    message: 'Please wait...'
                });
                $.ajax({
                    url: app.baseUrl + 'file/delete?token=' + $(self).data('token'),
                    method: 'post',
                    dataType: 'json',
                    success: function(s) {
                        let tableId = $(self).closest('table').attr('id');

                        if(s.status == 'success') {
                            $('#' + tableId).DataTable({
                                destroy: true,
                                pageLength: 5,
                                order: [[0, 'desc']]
                            }).row($(self).closest('tr')).remove().draw();
                            $(document).find('.file-hidden-input-' + $(self).data('token')).remove();
                            Swal.fire({
                                icon: "success",
                                title: "Deleted",
                                text: "Your file has been deleted.",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        else {
                            Swal.fire('Error', s.errors, 'error');
                        }
                        KTApp.unblock('body');
                    },
                    error: function(e) {
                        Swal.fire('Error', e.responseText, 'error');
                        KTApp.unblock('body');
                    },
                })
            }
        });
    });

    $(document).on('click', '.btn-edit-file', function() {
        let file = $(this);

        KTApp.block('.files-container', {
            state: 'warning', // a bootstrap color
            message: 'Please wait...',
        });

        $.ajax({
            url: app.baseUrl + 'file/view',
            method: 'get',
            data: {
                token: file.data('token'),
                template: '_form-ajax',
            },
            dataType: 'json',
            success: function(s) {
                if(s.status == 'success') {
                    $('#modal-edit-document .modal-body').html(s.form);
                    $('#modal-edit-document').modal('show');
                }
                else {
                    Swal.fire('Error', s.error, 'error');
                }
                KTApp.unblock('.files-container');
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('.files-container');
            }
        });
    });

    function getAge(dateString) {
		var ageInMilliseconds = new Date() - new Date(dateString);
		return Math.floor(ageInMilliseconds/1000/60/60/24/365); // convert to years
	}

	$(document).on('change', '#socialpensioner-birth_date', function() {
		let birthDate = $(this).val();

		$('#socialpensioner-age').val(getAge(birthDate));
	});
JS);

?>
<?php $form = ActiveForm::begin(['id' => 'masterlist-form']); ?>
	
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
		'title' => 'Primary Information'
	]) ?>
		<div class="row">
			<div class="col-md-4">
				<div class="text-center" style="width: fit-content;">
					<?= Html::image($model->photo, ['w'=>100], [
		                'class' => 'img-thumbnail user-photo mw-100',
		                'loading' => 'lazy',
		            ] ) ?>
		            <div class="my-2"></div>
		            <?= ImageGallery::widget([
		            	'tag' => 'Social Pensioner',
		            	'buttonTitle' => 'Profile Photo',
		                'model' => $model,
		                'attribute' => 'photo',
		                'ajaxSuccess' => "
		                    if(s.status == 'success') {
		                        $('.user-photo').attr('src', s.src);
		                    }
		                ",
		            ]) ?> 
				</div>
			</div>
		</div>
		<div class="my-2"></div>

		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'qr_id')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= DatePicker::widget([
					'form' => $form,
					'model' => $model,
					'attribute' => 'date_registered',
				]) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'name_suffix')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= BootstrapSelect::widget([
                    'form' => $form,
                    'model' => $model,
                    'attribute' => 'sex',
                    'data' => Sex::dropdown(),
		            'prompt' => false
                ]) ?>
			</div>
			<div class="col-md-4">
				<?= BootstrapSelect::widget([
                    'form' => $form,
                    'model' => $model,
                    'attribute' => 'civil_status',
                    'data' => CivilStatus::dropdown(),
		            'prompt' => false
                ]) ?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4">
				<?= DatePicker::widget([
					'form' => $form,
					'model' => $model,
					'attribute' => 'birth_date',
				]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'age')->textInput([
					'readonly' => true
				]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'birth_place')->textInput() ?>
			</div>
		</div>

		

	<?php $this->endContent() ?>

	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
		'title' => 'Contact Details'
	]) ?>
		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'contact_no')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'other_contact_no')->textInput(['maxlength' => true]) ?>
			</div>
		</div>
	<?php $this->endContent() ?>

	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
		'title' => 'Address'
	]) ?>
		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'house_no')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'sitio')->textInput(['maxlength' => true]) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'purok')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= BootstrapSelect::widget([
                    'form' => $form,
                    'model' => $model,
                    'attribute' => 'barangay',
                    'data' => Barangay::dropdown('name', 'name'),
                ]) ?>
			</div>
		</div>
	<?php $this->endContent() ?>


	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
		'title' => 'Priority Score Basis'
	]) ?>
		<div class="row">
			<div class="col-md-4">
				<?= BootstrapSelect::widget([
		            'form' => $form,
		            'model' => $model,
		            'attribute' => 'is_pwd',
		            'data' => [false => 'No', true => 'Yes'],
		            'prompt' => false
		        ]) ?>
			</div>
			<div class="col-md-4">
				<?= BootstrapSelect::widget([
		            'form' => $form,
		            'model' => $model,
		            'attribute' => 'is_senior',
		            'data' => [false => 'No', true => 'Yes'],
		            'prompt' => false
		        ]) ?>
			</div>
			<div class="col-md-4">
				<?= BootstrapSelect::widget([
		            'form' => $form,
		            'model' => $model,
		            'attribute' => 'is_solo_parent',
		            'data' => [false => 'No', true => 'Yes'],
		            'prompt' => false
		        ]) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<?= BootstrapSelect::widget([
		            'form' => $form,
		            'model' => $model,
		            'attribute' => 'is_solo_member',
		            'data' => [false => 'No', true => 'Yes'],
		            'prompt' => false
		        ]) ?>
			</div>
		</div>

	<?php $this->endContent() ?>

	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
		'title' => 'Others'
	]) ?>
		<div class="row">
			<div class="col-md-4">
				<?= BootstrapSelect::widget([
                    'form' => $form,
                    'model' => $model,
                    'attribute' => 'educational_attainment',
                    'data' => EducationalAttainment::dropdown('label', 'label'),
                ]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'occupation')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'income')->textInput(['type' => 'number']) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'source_of_income')->textInput(['maxlength' => true]) ?>
			</div>
		</div>
	<?php $this->endContent() ?>

	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
		'title' => 'Documents'
	]) ?>
	    <section>
	        <div class="row">
	            <div class="col-md-4">
	                <div class="document-container-holder"></div>
					<?= Webcam::widget([
						'tag' => 'Social Pensioner',
						'withInput' => false,
						'model' => $model,
						'attribute' => 'documents[]',
						'ajaxSuccess' => <<< JS
							$('#table-file').DataTable({
							destroy: true,
							pageLength: 5,
							order: [[0, 'desc']]
							}).row.add($(s.row)).draw();
							$('.document-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="Masterlist[documents][]" value="'+ s.file.token +'">'); 
						JS
					]) ?>

	                <div class="mt-5"></div>
					<?= Dropzone::widget([
						'tag' => 'Social Pensioner',
						'title' => 'Drop Other Documents here.',
						'model' => $model,
						'attribute' => 'documents',
						'inputName' => 'hidden',
						'success' => <<< JS
							this.removeFile(file);
							$('#table-file').DataTable({
							destroy: true,
							pageLength: 5,
							order: [[0, 'desc']]
							}).row.add($(s.row)).draw();
							$('.document-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="Masterlist[documents][]" value="'+ s.file.token +'">'); 
						JS,
					]) ?>
	            </div>
	            <div class="col-md-8">
	                <?php $this->beginContent('@app/views/file/_row-header.php'); ?>
	                    <?= Html::foreach($model->documentFiles, function($file) {
	                        return $this->render('/file/_row', [
	                            'model' => $file
	                        ]) . Html::input('text', 'Masterlist[documents][]', $file->token,
	                            ['class' => "app-hidden file-hidden-input-{$file->token}"]
	                        );
	                    }) ?>
	                <?php $this->endContent(); ?>
	            </div>
	        </div> 
	    </section>
	<?php $this->endContent() ?>

	

    <div class="form-group">
		<?= ActiveForm::buttons() ?>
    </div>
<?php ActiveForm::end(); ?>

<div class="modal fade" id="modal-edit-document" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-edit-document">Rename File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>