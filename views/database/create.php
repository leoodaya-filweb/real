<?php

use app\models\search\DatabaseSearch;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Database */

$this->title = 'Create Database Entry: ' . $model->prioritySectorLabel;
$this->params['breadcrumbs'][] = ['label' => 'Databases', 'url' => $model->indexUrl];
//$this->params['breadcrumbs'][] = 'Create';
$this->params['breadcrumbs'][] = [
    'label' => $model->prioritySectorLabel, 
    'url' => ['database/member', 'priority_sector' => $model->priority_sector]
];
$this->params['breadcrumbs'][] = 'Create ';
$this->params['searchModel'] = new DatabaseSearch();
$this->params['activeMenuLink'] = '/database';
$this->params['wrapCard'] = false;

$this->registerJs(<<< JS
	$("body").on('change','#database-priority_sector', function(evt) {
		var priority_sector = $(this).val();

		KTApp.block('#kt_content', {
			overlayColor: '#000000',
			type: 'v1',
			state: 'success',
			size: 'lg',
			message: 'Loading..'
		});

		$('body').find('#database-create-page-load')
			.load("{$model->createUrl}"+"?priority_sector="+priority_sector, function() {
				KTApp.unblock('#kt_content');
			}
		);
	});

  $(document).on("change", ".date-form", function(){
   //valid = parseDate($(this).val()); //pattern.test($(this).val());
   // console.log(valid);
    valid = moment($(this).val(), 'MM/DD/YYYY',true).isValid();

    if(!valid){
      $(this).val("");
     }
   });

    
  function parseDate(str) {
    function pad(x){return (((''+x).length==2) ? '' : '0') + x; }
    var m = str.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/)
    , d = (m) ? new Date(m[3], m[2]-1, m[1]) : null
    , matchesPadded = (d&&(str==[pad(d.getDate()),pad(d.getMonth()+1),d.getFullYear()].join('/')))
    , matchesNonPadded = (d&&(str==[d.getDate(),d.getMonth()+1,d.getFullYear()].join('/')));
   return (matchesPadded || matchesNonPadded) ? d : null;
  }
  
  Inputmask().mask("database-date_of_birth");
	
	
JS, View::POS_END);
?>

<div id="database-create-page-load" class="database-create-page">
	<?= $this->render($form, [
		'model' => $model,
	]) ?>
</div>