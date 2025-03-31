<?php

use app\helpers\App;
use app\helpers\ArrayHelper;
use app\widgets\BootstrapSelect;
use app\widgets\Checkbox;
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Disability</h3>
			<hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<label class="font-weight-bold">Type of Disability</label>
			<?= Checkbox::widget([
			    'data' => $model->typeOfDisabilityDropdown,
			    'name' => 'Database[pwd_type_of_disability][]',
			    'checkedFunction' => function($key, $value) use ($model) {
			        return isset($model->pwd_type_of_disability) && in_array($key, $model->pwd_type_of_disability) ? 'checked': '';
			    }
			]) ?>
		</div>
		<div class="col-md-8">
			<label class="font-weight-bold">Cause of Disability</label>

			<div class="row">
				<div class="col-md-6">
					<div class="mt-5">
						<label>Congenital / Inborn</label>
						<?= Checkbox::widget([
						    'data' => ArrayHelper::valueToKey(App::params('pwd_form')['cause_of_disability'][0]['cause']),
						    'name' => 'Database[cause_of_disability][congenital-inborn][]',
						    'checkedFunction' => function($key, $value) use ($model) {
						        return isset($model->cause_of_disability['inborn']) && in_array($key, $model->cause_of_disability['inborn']) ? 'checked': '';
						    }
						]) ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="mt-5">
						<label>Acquired</label>
						<?= Checkbox::widget([
						    'data' => ArrayHelper::valueToKey(App::params('pwd_form')['cause_of_disability'][1]['cause']),
						    'name' => 'Database[cause_of_disability][acquired][]',
						    'checkedFunction' => function($key, $value) use ($model) {
						        return isset($model->cause_of_disability['acquired']) && in_array($key, $model->cause_of_disability['acquired']) ? 'checked': '';
						    }
						]) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>