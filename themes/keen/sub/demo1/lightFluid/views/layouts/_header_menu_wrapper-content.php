<?php

use app\widgets\ActiveForm;
use app\widgets\Search;

$this->addCssFile('font-awesome/3.0');
$this->registerCss(<<< CSS
    input[type="search"]::-webkit-input-placeholder { 
        font-family: FontAwesome, Poppins, Helvetica, "sans-serif" !important;
    }

    input[type="search"]::-moz-placeholder  { 
        font-family: FontAwesome, Poppins, Helvetica, "sans-serif" !important;
    }

    input[type="search"]:-ms-input-placeholder  { 
        font-family: FontAwesome, Poppins, Helvetica, "sans-serif" !important;
    }
CSS)

?>


<?php $form = ActiveForm::begin([
    'id' => 'main-search-form',
    'action' => $searchAction, 
    'method' => 'get'
]); ?>

    <?= Search::widget([
        'searchKeywordUrl' => $this->params['searchKeywordUrl'] ?? '',
        'submitOnclick' => true,
        'model' => $searchModel,
        'options' => [
            'placeholder' => " Search",
            'data-search-input' => 'true',
        ]
    ]) ?>
<?php ActiveForm::end(); ?>