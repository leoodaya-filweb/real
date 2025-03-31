<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Contact Information</h3> 
			<hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'other_contact_no')->textInput([
				'maxlength' => true
			])->label('Landline No.') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'contact_no')->textInput([
				'maxlength' => true
			])->label('Mobile No.') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'email')->textInput([
				'maxlength' => true
			]) ?>
		</div>
	</div>
</section>