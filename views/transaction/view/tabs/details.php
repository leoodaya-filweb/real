<?php

use app\helpers\Html;
use app\widgets\Detail;

$this->registerCss(<<< CSS
    .detail-view {
        margin-top: 0;
    }
CSS);
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
    'title' => 'Transaction Details',
    'toolbar' => <<< HTML
        <div class="card-toolbar">
            <a href="{$model->viewUrlDocuments}" class="btn btn-outline-primary font-weight-bolder btn-sm"> View Documents </a>
            <a href="{$model->viewUrlMemberProfile}" class="btn btn-outline-primary font-weight-bolder btn-sm ml-3"> Member Profile </a>
        </div>
    HTML
]); ?>

	<?= Detail::widget(['model' => $model]) ?>

<?php $this->endContent(); ?>