<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Organization Information</h3>
			<hr/>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'org_affiliated')->textInput([
				'maxlength' => true
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'org_contact_person')->textInput([
				'maxlength' => true
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'org_tel_no')->textInput([
				'maxlength' => true
			]) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			<?= $form->field($model, 'org_office_address')->textInput([
				'maxlength' => true
			]) ?>
		</div>
	</div>
</section>