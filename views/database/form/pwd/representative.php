<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Representative</h3> 
			<hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'representative_lastname')->textInput([
				'maxlength' => true
			])->label('Last Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'representative_firstname')->textInput([
				'maxlength' => true
			])->label('First Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'representative_middlename')->textInput([
				'maxlength' => true
			])->label('Middle Name') ?>
		</div>
	</div>
</section>