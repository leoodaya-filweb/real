<?php

use app\helpers\Html;
use app\models\search\MemberSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Member */

$this->title = 'Update Member: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new MemberSearch();
$this->params['showCreateButton'] = false; 
$this->params['wrapCard'] = false;

$this->params['headerButtons'] = implode(" ", [
    Html::a(<<< HTML
        <span class="svg-icon svg-icon-md svg-icon-white">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="0" y="0" width="24" height="24"></rect>
                <rect fill="#000000" opacity="0.3" x="12" y="4" width="3" height="13" rx="1.5"></rect>
                <rect fill="#000000" opacity="0.3" x="7" y="9" width="3" height="8" rx="1.5"></rect>
                <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"></path>
                <rect fill="#000000" opacity="0.3" x="17" y="11" width="3" height="6" rx="1.5"></rect>
            </g>
        </svg>
        </span>New Transaction
    HTML, $model->createTransactionLink, [
        'class' => 'btn btn-primary font-weight-bolder font-size-sm'
    ]),
    Html::a('Profile Overview', $model->viewUrl, [
        'class' => 'btn btn-outline-success font-weight-bolder font-size-sm'
    ]),
    Html::a('Household', $model->viewUrlHouseholdTab, [
        'class' => 'btn btn-outline-success font-weight-bolder font-size-sm'
    ]),
    Html::a('Family Composition', $model->viewUrlFamilyCompositionTab, [
        'class' => 'btn btn-outline-success font-weight-bolder font-size-sm'
    ]),
]);
?>
<div class="member-update-page container">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>