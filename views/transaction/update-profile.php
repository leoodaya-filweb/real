<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\search\MemberSearch;
use app\models\search\TransactionSearch;
use app\widgets\Reminder;

/* @var $this yii\web\View */
/* @var $model app\models\Member */

$label = App::keyMapParams('transaction_types_menu', 'slug', 'label')[$transaction_type] ?? '';

$this->title = 'Update Member Profile: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $transaction->indexUrl];
$this->params['breadcrumbs'][] = $label;
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new TransactionSearch();
$this->params['showCreateButton'] = false; 
$this->params['wrapCard'] = false;

$this->params['headerButtons'] = implode(" ", [
	Html::a(<<< HTML
		<span class="svg-icon svg-icon-md svg-icon-black">
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
		<rect x="0" y="0" width="24" height="24"/>
		<path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3"/>
		<path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000"/>
		</g>
		</svg>
		</span>Proceed to {$label} Form
	HTML, $model->getCreateTransactionLink($transaction_type), [
		'class' => 'btn btn-outline-info font-weight-bolder font-size-sm',
	]),
	Html::a('View Household Information', '#modal-household-information', [
		'class' => 'btn btn-outline-info font-weight-bolder',
		'data-toggle' => 'modal'
	])
]);
?>

<?php /* Reminder::widget([
	'head' => $label,
	'message' => 'Please click '. Html::a('here', $model->getCreateTransactionLink($transaction_type), ['class' => 'font-weight-bold']) .' to proceed to '. $label .' form.',
	'type' => 'info'
])*/ ?>

<div class="container" style="position: relative;">

<?= Reminder::widget([
    'head' => 'IMPORTANT REMINDER:',
    'message' => <<< HTML
        <div>Kindly make sure that all information related to the Client on the Household Member Profile is accurate</div>
        <div>and updated before proceeding to the transaction.</div>
        <br>
        <div>Check if the Client is still part of the current household. Click transfer to a) New household, if Client has</div>
        <div>moved out to a new household or residence away from the current or b) Existing household, if Client</div>
        <div>moved to an existing household or residence.</div>
    HTML,
    'type' => 'primary',
    'withDot' => false
]); ?>


<div class="member-update-page">
	<?= $this->render('_update-profile', [
        'model' => $model,
        'household' => $household,
    ]) ?>
</div>

</div>

<div class="modal fade" id="modal-household-information" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Household Information</h5>
                <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" data-scroll="true" style="height: 100vh">

                <?= $this->render('/member/create/household', [
                    'model' => $model->household
                ]); ?>
            </div>
            <div class="modal-footer">
                <?= Html::a('Close', '#', [
                    'class' => 'btn btn-light-primary btn-lg font-weight-bold btn-close-modal',
                    'data-dismiss' => 'modal'
                ]) ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-entry-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" data-scroll="true">
                
            </div>
        </div>
    </div>
</div>
