<?php

use app\helpers\Html;
use app\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(['id' => 'setting-general-image-form']); ?>
    <h4 class="mb-10 font-weight-bold text-dark">Images</h4>
    <table class="table table-bordered table-head-solid ">
        <thead>
            <th>File</th>
            <th width="200" class="text-center">action</th>
        </thead>
        <tbody class="files-container">
            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'brand_logo',
            ]) ?>
            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'primary_logo',
            ]) ?>
            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'secondary_logo',
            ]) ?>
            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'favicon',
            ]) ?>
            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'image_holder',
            ]) ?>
            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'household_map_icon',
            ]) ?>
            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'id_template',
            ]) ?>
            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'municipality_logo',
            ]) ?>
            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'social_welfare_logo',
            ]) ?>
            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'other_logo',
            ]) ?>

            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'senior_citizen_logo',
            ]) ?>

            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'solo_parent_logo',
            ]) ?>
            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'pyap_logo',
            ]) ?>
            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'doh_logo',
            ]) ?>

            <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'baktom_logo',
            ]) ?>
            
             <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'province_logo',
            ]) ?>
            
             <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'philippines_logo',
            ]) ?>
           <?= $this->render('_image', [
                'model' => $model,
                'attribute' => 'footer_image',
            ]) ?>
            
        </tbody>
    </table>

	<div class="form-group"> <br>
		<?= ActiveForm::buttons() ?>
	</div>
<?php ActiveForm::end(); ?>