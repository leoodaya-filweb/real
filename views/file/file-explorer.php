<?php

use app\helpers\Html;
use app\helpers\Url;
use app\helpers\App;

use app\models\File;
use app\models\search\FileSearch;
use app\widgets\ActiveForm;
use app\widgets\Autocomplete;
use yii\widgets\Pjax;
use app\widgets\FileExplorer;

/* @var $this yii\web\View */
/* @var $model app\models\File */

$this->title = 'Document Library';
$this->params['breadcrumbs'][] = 'Documents';
$this->params['searchModel'] = $searchModel;
$this->params['showCreateButton'] = true; 
$this->params['activeMenuLink'] = '/file/system-files';

?>

<p class="lead font-weight-bolder">
	Total: <?= File::totalSize() ?>
	<?= Html::if(App::queryParams() != null, Html::a('Clear Filter', ['file/system-files'], [
		'class' => 'btn btn-secondary btn-sm font-weight-bold ml-2'
	])) ?>
</p>

<?php if ($dataProvider->totalCount > 0 && App::queryParams() != null): ?>
	<?php $this->beginContent('@app/views/file/_row-header.php'); ?>
	    <?= Html::foreach($dataProvider->models, function($file) {
	        return $this->render('/file/_row', ['model' => $file]);
	    }) ?>
	<?php $this->endContent(); ?>
<?php else: ?>
	<?= FileExplorer::widget() ?>
<?php endif ?>
