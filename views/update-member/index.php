<?php

use app\widgets\SearchQrCode;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Search Member';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['showCreateButton'] = true; 
$this->params['showExportButton'] = true;
$this->params['wrapCard'] = false;
?>
<div class="update-member-index-page">
	<?= SearchQrCode::widget([
		'title' => 'Search Profile',
		'template' => '_update-profile',
		'modalTitle' => 'Update Profile:'
	]) ?>
</div>