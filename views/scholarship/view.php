<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\search\ScholarshipSearch;
use app\widgets\Anchors;
use app\widgets\Detail;
use app\widgets\Dropzone;
use app\widgets\InputList;

/* @var $this yii\web\View */
/* @var $model app\models\Scholarship */

$this->title = 'Scholarship: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Scholarships', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new ScholarshipSearch();
$this->params['showCreateButton'] = true; 
$this->params['wrapCard'] = false;

$this->registerCss(<<< CSS
    .card.card-custom {border: 1px solid #EBEDF3; }
    .nav-tabs {border-bottom: none; }
CSS);
?>
<div class="scholarship-view-page">
    <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
        'bodyStyle' => 'background:#fff; padding: 2rem',
        'title' => implode(' ', [$model->name, $model->getStatusBadge('label-lg')]),
        'toolbar' => '
            <div class="card-toolbar">
                <ul class="nav nav-tabs nav-tabs-line nav-bold">
                    <li class="nav-item" data-tab="general">
                        <a class="nav-link '. ($tab == 'general' ? 'active': '') .'" href="'. Url::current(['tab' => 'general']) .'">
                            GENERAL INFORMATION
                        </a>
                    </li>
                    <li class="nav-item" data-tab="interview">
                        <a class="nav-link '. ($tab == 'interview' ? 'active': '') .'" href="'. Url::current(['tab' => 'interview']) .'">
                            INTERVIEW NOTES
                        </a>
                    </li>
                    <!--
                    <li class="nav-item" data-tab="grant-form">
                        <a class="nav-link '. ($tab == 'grant-form' ? 'active': '') .'" href="'. Url::current(['tab' => 'grant-form']) .'">
                            SCHOLASHIP GRANT FORM
                        </a>
                    </li>
                    <li class="nav-item" data-tab="disbursement-voucher">
                        <a class="nav-link '. ($tab == 'disbursement-voucher' ? 'active': '') .'" href="'. Url::current(['tab' => 'disbursement-voucher']) .'">
                            DISBURSEMENT VOUCHER
                        </a>
                    </li>
                    <li class="nav-item" data-tab="obr-request">
                        <a class="nav-link '. ($tab == 'obr-request' ? 'active': '') .'" href="'. Url::current(['tab' => 'obr-request']) .'">
                            OBR REQUEST
                        </a>
                    </li>
                    <li class="nav-item" data-tab="journal-entry">
                        <a class="nav-link '. ($tab == 'journal-entry' ? 'active': '') .'" href="'. Url::current(['tab' => 'journal-entry']) .'">
                            JOURNAL ENTRY VOUCHER
                        </a>
                    </li>
                    -->
                </ul>
            </div>
        '
    ]) ?>
        
        <div class="tab-content">
            <?= $this->render("view/tab/{$tab}", [  
                'model' => $model,
                'tab' => $tab,
            ]) ?>
        </div>
    <?php $this->endContent() ?>
</div>