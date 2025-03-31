<?php

use app\models\Sex;
use app\helpers\App;
use app\helpers\Url;
use app\helpers\Html;
use app\models\Member;
use app\models\PwdType;
use app\widgets\Filter;
use app\widgets\AppIcon;
use app\widgets\Iconbox;
use app\models\Household;
use app\widgets\Reminder;
use app\models\CivilStatus;
use app\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\SocialPension;
use app\models\search\MemberSearch;
use app\models\EducationalAttainment;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form app\widgets\ActiveForm */

$this->registerJs(<<< JS
    let submitForm = function() {
        KTApp.block('#event-form', {
            overlayColor: '#000000',
            state: 'success',
            message: 'Please wait...'
        });

        let form = $('#event-form');

        $.ajax({
            url: app.baseUrl + 'event/find-beneficiaries',
            data: form.serialize(),
            method: 'get',
            dataType: 'json',
            success: function(s) {
                if(s.status == 'success') {
                    $('#total-beneficiaries-found').html(s.total);
                }
                else {
                    Swal.fire('Error', s.error, 'error');
                }
                KTApp.unblock('#event-form');
            },
            error: function() {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('#event-form');
            }
        });
    }
    $('#membersearch-age_from, #membersearch-age_to').change(function() {
        let ageFrom = $('#membersearch-age_from').val(),
            ageTo = $('#membersearch-age_to').val(),
            card = $(this).closest('.card'),
            title = card.data('title');

        if(ageFrom && ageTo) {
            card.find('.card-title span').html(title + "("+ ageFrom +" - "+ ageTo +")");
        }
        else {
            card.find('.card-title span').html(title);
        }
        submitForm();
    });

    $('#event-form input[type="checkbox"]').change(function() {
        let checkboxes = $(this).closest('.card-body').find('input[type=checkbox]:checked'),
            card = $(this).closest('.card'),
            title = card.data('title');

        if (checkboxes.length > 0) {
           card.find('.card-title span').html(title +" ("+ checkboxes.length +")");
        }
        else {
           card.find('.card-title span').html(title);
        }

        submitForm();
    });

    $('form#event-form').on('beforeSubmit', function(e) {
        e.preventDefault();
        let form = $(this);
        KTApp.block('#event-form', {
            overlayColor: 'red',
            state: 'warning', // a bootstrap color
            message: 'Creating list, Do not close!...',
        });
 
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            dataType: 'json',
            data: form.serialize(),
            success: function(s) {
                if(s.status == 'success') {
                    Swal.fire({
                        icon: "success",
                        title: "List Created",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.href = s.redirect;
                }
                else {
                    Swal.fire("Error", 'There is something wrong', "error");
                }
                KTApp.unblock('#event-form');
            },
            error: function(e) {
                    Swal.fire("Error", e.responseText, "error");
                KTApp.unblock('#event-form');
            }
        });
        return false;
    });
JS);

$memberSearch = $model->socialPensionSearch;
$searchModel = $memberSearch['searchModel'];
$dataProvider = $memberSearch['dataProvider'];

?>
<h4 class="mb-10 font-weight-bold text-dark">
    <?= $tabData['title'] ?>
</h4>

<?php $form = ActiveForm::begin(['id' => 'event-form']); ?>
    <?= $form->field($searchModel, 'social_pension_status')->hiddenInput([
        'name' => 'social_pension_status',
        'value' => SocialPension::SOCIAL_PENSIONER
    ])->label(false) ?>
    <div class="row" data-sticky-container>
        <div class="col-md-6">

            <div class="accordion accordion-solid accordion-toggle-plus" id="accordion-filter">
                <div class="card" data-title="Family Head">
                    <div class="card-header">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#family-head-container" aria-expanded="false">
                            <i class="fas fa-house-user"></i>
                            <span>Family Head <?= $searchModel->totalFilterTag('head') ?></span>
                        </div>
                    </div>
                    <div id="family-head-container" class="collapse" data-parent="#accordion-filter">
                        <div class="card-body">
                            <?= Filter::widget([
                                'form' => $form,
                                'model' => $searchModel,
                                'attribute' => 'head',
                                'title' => false,
                                'data' => App::keyMapParams('family_head')
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card" data-title="Solo Parent">
                    <div class="card-header">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#solo-parent-container">
                            <i class="fas fa-school"></i>
                            <span>Solo Parent <?= $searchModel->totalFilterTag('solo_parent') ?></span>
                        </div>
                    </div>
                    <div id="solo-parent-container" class="collapse" data-parent="#accordion-filter">
                        <div class="card-body">
                            <?= Filter::widget([
                                'form' => $form,
                                'model' => $searchModel,
                                'attribute' => 'solo_parent',
                                'data' => App::keyMapParams('solo_parent'),
                                'title' => false,
                                'limit' => 100
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="card" data-title="Solo Member">
                    <div class="card-header">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#solo-member-container">
                            <i class="fas fa-school"></i>
                            <span>Solo Member <?= $searchModel->totalFilterTag('solo_member') ?></span>
                        </div>
                    </div>
                    <div id="solo-member-container" class="collapse" data-parent="#accordion-filter">
                        <div class="card-body">
                            <?= Filter::widget([
                                'form' => $form,
                                'model' => $searchModel,
                                'attribute' => 'solo_member',
                                'data' => App::keyMapParams('solo_member'),
                                'title' => false,
                                'limit' => 100
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card" data-title="Gender">
                    <div class="card-header">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#gender-container">
                            <i class="fas fa-user-cog"></i>
                            <span>Gender <?= $searchModel->totalFilterTag('sex') ?></span>
                        </div>
                    </div>
                    <div id="gender-container" class="collapse" data-parent="#accordion-filter">
                        <div class="card-body">
                            <?= Filter::widget([
                                'form' => $form,
                                'model' => $searchModel,
                                'attribute' => 'sex',
                                'data' => Sex::dropdown(),
                                'title' => false,
                            ]) ?>
                        </div>
                    </div>
                </div>
         
                <div class="card" data-title="Civil Status">
                    <div class="card-header">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#civil-status-container">
                            <i class="fas fa-user-tie"></i>
                            <span>Civil Status <?= $searchModel->totalFilterTag('civil_status') ?></span>
                        </div>
                    </div>
                    <div id="civil-status-container" class="collapse" data-parent="#accordion-filter">
                        <div class="card-body">
                            <?= Filter::widget([
                                'form' => $form,
                                'model' => $searchModel,
                                'attribute' => 'civil_status',
                                'data' => CivilStatus::dropdown(),
                                'title' => false,
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card" data-title="Educational Attainment">
                    <div class="card-header">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#educational-attainment-container">
                            <i class="fas fa-school"></i>
                            <span>Educational Attainment <?= $searchModel->totalFilterTag('educational_attainment') ?></span>
                        </div>
                    </div>
                    <div id="educational-attainment-container" class="collapse" data-parent="#accordion-filter">
                        <div class="card-body">
                            <?= Filter::widget([
                                'form' => $form,
                                'model' => $searchModel,
                                'attribute' => 'educational_attainment',
                                'data' => EducationalAttainment::dropdown(),
                                'title' => false,
                                'limit' => 100
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card" data-title="PWD">
                    <div class="card-header">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#pwd-container">
                            <i class="fas fa-user"></i>
                            <span>PWD <?= $searchModel->totalFilterTag('pwd') ?></span>
                        </div>
                    </div>
                    <div id="pwd-container" class="collapse" data-parent="#accordion-filter">
                        <div class="card-body">
                            <?= Filter::widget([
                                'form' => $form,
                                'model' => $searchModel,
                                'attribute' => 'pwd',
                                'data' => App::keyMapParams('pwd'),
                                'title' => false,
                                'limit' => 100
                            ]) ?>
                        </div>
                    </div>
                </div>


                <div class="card" data-title="PWD Type">
                    <div class="card-header">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#pwd_type-container">
                            <i class="fas fa-user"></i>
                            <span>PWD Type <?= $searchModel->totalFilterTag('pwd_type') ?></span>
                        </div>
                    </div>
                    <div id="pwd_type-container" class="collapse" data-parent="#accordion-filter">
                        <div class="card-body">
                            <?= Filter::widget([
                                'form' => $form,
                                'model' => $searchModel,
                                'attribute' => 'pwd_type',
                                'data' => PwdType::dropdown(),
                                'title' => false,
                                'limit' => 100
                            ]) ?>
                        </div>
                    </div>
                </div>


                <div class="card" data-title="Barangay">
                    <div class="card-header">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#barangay-container">
                            <i class="fas fa-shield-alt"></i>
                            <span>Barangay <?= $searchModel->totalFilterTag('barangay_ids') ?></span>
                        </div>
                    </div>
                    <div id="barangay-container" class="collapse" data-parent="#accordion-filter">
                        <div class="card-body">
                            <?= Filter::widget([
                                'form' => $form,
                                'model' => $searchModel,
                                'attribute' => 'barangay_ids',
                                'title' => false,
                                'data' => ArrayHelper::map(App::setting('address')->barangays, 'id', 'name'),
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card" data-title="Purok">
                    <div class="card-header">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#purok-container">
                            <i class="fas fa-road"></i>
                            <span>Purok <?= $searchModel->totalFilterTag('purok_no') ?></span>
                        </div>
                    </div>
                    <div id="purok-container" class="collapse" data-parent="#accordion-filter">
                        <div class="card-body">
                            <?= Filter::widget([
                                'form' => $form,
                                'model' => $searchModel,
                                'attribute' => 'purok_no',
                                'title' => false,
                                'data' => Household::filter('purok_no'),
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card" data-title="Age">
                    <div class="card-header">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#age-container">
                            <i class="far fa-calendar-alt"></i>
                            <span>Age (<?= $searchModel->age_from ?> - <?= $searchModel->age_to ?>)</span>
                        </div>
                    </div>
                    <div id="age-container" class="collapse" data-parent="#accordion-filter">
                        <div class="card-body">
                            <?= $form->field($searchModel, 'age_from')->dropDownList(
                                Member::ageDropdown(), [
                                    'prompt' => 'Select Age',
                                    'name' => 'age_from'
                                ]
                            ) ?>
                            <?= $form->field($searchModel, 'age_to')->dropDownList(
                                Member::ageDropdown(), [
                                    'prompt' => 'Select Age',
                                    'name' => 'age_to'
                                ]
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
            <p class="mt-5">No Available filter? 
                <?= Html::a('Create manually!', Url::current(['tab' => 'create-list'])) ?>
            </p>
        </div>
        <div class="col-md-6 total-beneficiaries-container">
            <div data-sticky="true" data-margin-top="120">
                <?= Reminder::widget([
                    'head' => 'Target Beneficiaries',
                    'message' => "A total of ". Html::tag('span', Html::number($model->no_of_pensioner), ['class' => 'font-weight-bolder']) ." social pensioners needed.",
                    'type' => 'info'
                ]) ?>

                <?= Reminder::widget([
                    'head' => 'Weighted score for prioritization',
                    'message' => <<< HTML
                        <div> <b>[ 0.6]</b> - If the social pensioner is a priority based on the guidelines (3 priority  (Pensioner, PWD, Senior Citizen)) </div>
                        <div> <b>[0.25]</b> - If a single household member, no members to depend on</div>
                        <div> <b>[0.15]</b> - If priority is based on proximity and accessibility to resources</div>
                    HTML,
                    'type' => 'primary',
                    'withDot' => false
                ]) ?>
                <?= Iconbox::widget([
                    'title' =>'Members Found (' . Html::tag('span', App::formatter('asNumber', $dataProvider->totalCount), [
                        'id' => 'total-beneficiaries-found',
                        'class' => 'font-weight-bolder'
                    ]) . ')',
                    'url' => '#',
                    'iconContent' => Html::tag(
                        'div', AppIcon::widget(['icon' => 'add-user']), [
                        'class' => 'svg-icon svg-icon-warning svg-icon-4x',
                    ]),
                    'content' => 'Total numbers of members found base on filter.',
                    'wrapperClass' => 'wave wave-animate-slower'
                ]) ?>
            </div>
        </div>
    </div>

    <div class="form-group mt-10">
        <?= Html::a('Back', Url::current(['tab' => 'documents']), [
            'class' => 'btn btn-light-info btn-lg'
        ]) ?>
        <?= Html::submitButton('Next', [
            'class' => 'btn btn-success btn-lg'
        ]) ?>
    </div>
<?php ActiveForm::end(); ?>
