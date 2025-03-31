<?php

use app\helpers\App;
use app\helpers\Url;
use app\helpers\Html;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;

/* @var $this yii\web\View */
/* @var $model app\models\Member */
/* @var $form app\widgets\ActiveForm */
$this->registerJs(<<< JS
    $('#member-birth_date').on('change', function() {
        var birth_date = $(this).val();
        $.ajax({
            url: app.baseUrl + 'member/compute-age',
            method: 'post',
            data: {birth_date: birth_date},
            dataType: 'json',
            success: function(s) {
                if(s.status == 'success') {
                    $('#member-age').val(s.age);
                }
            },
            error: function(e) {
                alert(e.responseText);
            }
        });
    });
JS);

$action = $action ?? 'create';

?>
<div class="wizard wizard-2" id="kt_wizard" data-wizard-state="first" data-wizard-clickable="false">
	<div class="wizard-nav flex-lg-shrink-0 w-lg-300px w-xl-375px border-right px-8 py-12 px-lg-10">
		<div class="wizard-steps">
		<?= Html::foreach($step_forms, function($step_form, $key) use($step, $member, $action) {
				$class = ($step == $key)? 'current': (($step > $key)? 'completed': 'pending');
				$url = Html::ifElse($member->qr_id, Url::to([$action, 'qr_id' => $member->qr_id, 'step' => $step_form['slug']]), Url::to([$action, 'step' => $step_form['slug']]));
				return <<< HTML
					<div class="wizard-step" data-wizard-type="step" data-wizard-state="{$class}">
						<div class="wizard-icon">
							<span class="wizard-number">{$key}</span>
							<span class="wizard-check">
								<span class="svg-icon svg-icon-2x">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<polygon points="0 0 24 0 24 24 0 24"></polygon>
											<path d="M6.26193932,17.6476484 C5.90425297,18.0684559 5.27315905,18.1196257 4.85235158,17.7619393 C4.43154411,17.404253 4.38037434,16.773159 4.73806068,16.3523516 L13.2380607,6.35235158 C13.6013618,5.92493855 14.2451015,5.87991302 14.6643638,6.25259068 L19.1643638,10.2525907 C19.5771466,10.6195087 19.6143273,11.2515811 19.2474093,11.6643638 C18.8804913,12.0771466 18.2484189,12.1143273 17.8356362,11.7474093 L14.0997854,8.42665306 L6.26193932,17.6476484 Z" fill="#000000" fill-rule="nonzero" transform="translate(11.999995, 12.000002) rotate(-180.000000) translate(-11.999995, -12.000002)"></path>
										</g>
									</svg>
								</span>
							</span>
						</div>
						<div class="wizard-label">
							<h3 class="wizard-title">{$step_form['title']}</h3>
							<div class="wizard-desc">{$step_form['description']}</div>
						</div>
					</div>
				HTML;
			}) ?>
		</div>
	</div>
	<div class="wizard-body py-12 px-8">
		<div class="row">
			<div class="col">
				<?php $form = ActiveForm::begin([
					'forceClass' => 'form fv-plugins-bootstrap fv-plugins-framework'
				]); ?>
          			<div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
						<?= $this->render('_form/' . $step_forms[$step]['slug'], [
							'model' => $model,
							'step' => $step,
							'form' => $form,
						]) ?>
						<div></div>
						<div></div>
						<div></div>
						<div></div>
						<div></div>
					</div>
		        <?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>