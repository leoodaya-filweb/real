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
		<?php if ($user->can('search-qr-code') || $user->can('transactions')): ?>
			<div class="col-md-8">
			    
			   <?php if ($user->can('search-qr-code')): ?>
			   <div class="card card-custom mb-8">
			 <div class="card-header border-0 pt-6">
		     <h3 class="card-title align-items-start flex-column">
		  	<span class="card-label font-weight-bolder font-size-h4 text-dark-75">
			    Transactions
			</span>
			<span class="text-muted mt-3 font-weight-bold font-size-lg">
			      Search household member to create Transaction
		   	</span>
		     </h3>
		
	       </div>
			       
			    <div class="card-body pt-0" style="margin-top: -30px;"> 
			
					<?= SearchQrCode::widget([
						'title' => '<i class="fas fa-pencil-alt"></i> '
					]) ?>
			
			 	</div>
				</div>
			  <?php endif ?>
					
				<?php if ($user->can('transactions')): ?>
					<?= LatestTransactions::widget() ?>
				<?php endif ?>
			</div>
		<?php endif ?>

		<?php if ($user->can('events')): ?>
			<div class="col-md-4">
				<div class="latest-events pt-0" style="height: 100%">
					<?= LatestEvents::widget() ?>
				</div>
			</div>
		<?php endif ?>
	</div>

	<?php if ($user->can('transaction-chart')): ?>
		<div class="row">
			<div class="col-md-12">
				<?= ApexChart::widget() ?>
			</div>
		</div>
	<?php endif ?>

	<div class="row">
		<?= Html::if($user->can('recent-members'), implode('', [
			'<div class="col-lg-4 col-md-4">',
			RecentMembers::widget(),
			'</div>'
		])) ?>

		<?= Html::if($user->can('database-priority-sector'), implode('', [
			'<div class="col-lg-4 col-md-4">',
			DatabasePrioritySector::widget([
				'enableSorting' => false,
				'withCard' => true,
			]),
			'</div>'
		])) ?> 
		<?= Html::if($user->can('recent-households'), implode('', [
			'<div class="col-lg-4 col-md-4">',
			RecentHouseholds::widget(),
			'</div>'
		])) ?>
		<?= Html::if($user->can('budget'), implode('', [
			'<div class="col-lg-4 col-md-4">',
			AppBudget::widget(),
			'</div>'
		])) ?>
	
	</div>
</div>