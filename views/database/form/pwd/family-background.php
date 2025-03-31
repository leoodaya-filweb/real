<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Family Background</h3> 
			<hr/>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="mb-2 font-weight-bold">Father</div>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'father_lastname')->textInput([
				'maxlength' => true,
			])->label('Last Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'father_firstname')->textInput([
				'maxlength' => true,
			])->label('First Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'father_middlename')->textInput([
				'maxlength' => true,
			])->label('Middle Name') ?>
		</div>
	</div>


	<hr>
	<div class="row">
		<div class="col-md-12">
			<div class="mb-2 font-weight-bold">Mother</div>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'mother_lastname')->textInput([
				'maxlength' => true,
			])->label('Last Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'mother_firstname')->textInput([
				'maxlength' => true,
			])->label('First Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'mother_middlename')->textInput([
				'maxlength' => true,
			])->label('Middle Name') ?>
		</div>
	</div>


	<hr>
	<div class="row">
		<div class="col-md-12">
			<div class="mb-2 font-weight-bold">Guardian</div>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'guardian_lastname')->textInput([
				'maxlength' => true,
			])->label('Last Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'guardian_firstname')->textInput([
				'maxlength' => true,
			])->label('First Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'guardian_middlename')->textInput([
				'maxlength' => true,
			])->label('Middle Name') ?>

		</div>
	</div>

</section>