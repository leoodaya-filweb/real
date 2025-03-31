<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\ActiveForm;
use app\widgets\TinyMce;

?>
<?php $form = ActiveForm::begin(['id' => 'setting-general-report-template-form']); ?>
    <h4 class="mb-10 font-weight-bold text-dark">Report Templates</h4>

    <div class="accordion accordion-solid accordion-toggle-plus" id="accordion-filter">
        <?= Html::foreach(App::setting('reportTemplate')->default(), function($template) use($model) {

            $form = TinyMce::widget([
                'size' => $template['size'],
                'height' => '400mm',
                'toolbar_sticky' => false,
                'model' => $model,
                'attribute' => $template['name'],
                'landscapeA4' => $template['name'] == 'dafac' ? true: false
            ]);
            $name = strtoupper(str_replace('_', ' ', $template['name']));
            return <<< HTML
                <div class="card">
                    <div class="card-header">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#{$template['name']}" aria-expanded="false">
                            <i class="flaticon2-list-3"></i>
                            <span>{$name}</span>
                        </div>
                    </div>
                    <div id="{$template['name']}" class="collapse" data-parent="#accordion-filter">
                        <div class="card-body">
                            {$form}
                        </div>
                    </div>
                </div>
            HTML;
        }) ?>
    </div>
 
	<div class="form-group"> <br>
		<?= ActiveForm::buttons() ?>
	</div>
<?php ActiveForm::end(); ?>