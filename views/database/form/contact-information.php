<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Contact Information</h3> 
			<hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'contact_no')->textInput([
				'maxlength' => true
			]) ?>
		</div>

		<div class="col-md-4">
			<?= $form->field($model, 'other_contact_no')->textInput([
				'maxlength' => true
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'email')->textInput([
				'maxlength' => true
			]) ?>
		</div>
		
		
		<div class="col-md-12 mt-10 mb-5">
		 <strong> In Case of Emergency</strong>
		</div>

		<div class="col-md-3">
			<?= $form->field($model, 'incase_emergency[name]')->textInput()->label('Name') ?>
		</div>
		
		<div class="col-md-2">
			<?= $form->field($model, 'incase_emergency[relationship]')->textInput()->label('Relationship') ?>
		</div>
		
		<div class="col-md-2">
			<?= $form->field($model, 'incase_emergency[contact_number]')->textInput()->label('Contact Number') ?>
		</div>
		
		<div class="col-md-5">
			<?= $form->field($model, 'incase_emergency[address]')->textInput()->label('Addess') ?>
		</div>
		
		
	</div>
</section>