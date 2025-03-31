<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\ApexChart;
use app\widgets\AppBudget;
use app\widgets\DatabasePrioritySector;
use app\widgets\LatestEvents;
use app\widgets\LatestTransactions;
use app\widgets\Map;
use app\widgets\RecentHouseholds;
use app\widgets\RecentMembers;
use app\widgets\SearchQrCode;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dashboard';
$this->params['searchModel'] = $searchModel; 
$this->params['wrapCard'] = false;

$user = App::identity();

?>
<div class="dashboard-page">
	<div class="row">

		<?= Html::if($user->can('search-qr-code'), implode('', [
			'<div class="col-lg-6 col-md-6">',
			SearchQrCode::widget([
				'title' => '<i class="fas fa-pencil-alt"></i> Create Transaction'
			]),
			'</div>'
		])) ?>

		<?= Html::if($user->can('database-priority-sector'), implode('', [
			'<div class="col-lg-6 col-md-6">',
			DatabasePrioritySector::widget([
				'enableSorting' => false,
				'withCard' => true,
			]),
			'</div>'
		])) ?>

		<?= Html::if($user->can('transactions'), implode('', [
			'<div class="col-lg-6 col-md-6">',
			LatestTransactions::widget(),
			'</div>'
		])) ?>

		<?= Html::if($user->can('events'), implode('', [
			'<div class="col-lg-6 col-md-6">',
			LatestEvents::widget(),
			'</div>'
		])) ?>
		
		<?= Html::if($user->can('budget'), implode('', [
			'<div class="col-lg-6 col-md-6">',
			AppBudget::widget(),
			'</div>'
		])) ?>
	
	
		<?= Html::if($user->can('transaction-chart'), implode('', [
			'<div class="col-lg-12 col-md-12">',
			ApexChart::widget(),
			'</div>'
		])) ?>
	
	
		<?= Html::if($user->can('recent-members'), implode('', [
			'<div class="col-lg-6 col-md-6">',
			RecentMembers::widget(),
			'</div>'
		])) ?>

		<?= Html::if($user->can('recent-households'), implode('', [
			'<div class="col-lg-6 col-md-6">',
			RecentHouseholds::widget(),
			'</div>'
		])) ?>
		
	</div>
</div>

