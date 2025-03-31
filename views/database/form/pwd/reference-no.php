<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Reference No.</h3>
			<hr/>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'sss_no')->textInput([
				'maxlength' => true
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'gsis_no')->textInput([
				'maxlength' => true
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'pagibig_no')->textInput([
				'maxlength' => true
			]) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'psn_no')->textInput([
				'maxlength' => true
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'philhealth_no')->textInput([
				'maxlength' => true
			]) ?>
		</div>
	</div>
</section>