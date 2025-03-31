<?php
/* @var $this \yii\web\View */
/* @var $content string */
use app\models\File;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\themes\keen\assets\KeenAsset;
use app\themes\keen\sub\demo1\main\assets\KeenDemo1AppAsset;
use app\widgets\ReportTemplate;


KeenDemo1AppAsset::register($this);
KeenAsset::register($this);

$sleep = $this->params['sleep'] ?? 1300;

$this->registerJs(<<< JS
    const sleep = ms => new Promise(resolve => setTimeout(resolve, ms));
    (async () => {
        await sleep({$sleep});
        window.print()
    })();
JS);

$size = $this->params['size'] ?? 'A4';


$file = File::controllerFind(App::setting('image')->footer_image, 'token');


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="<?= Url::image(App::setting('image')->favicon, ['w' => 16]) ?>" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style type="text/css">
        table {page-break-inside: auto; }
        tr {page-break-inside: avoid; page-break-after: auto; }
        thead {display: table-header-group; }
        tfoot {display: table-footer-group; }
        body {display: block; }

        .print-tbl-footer > div {
            font-size: 8pt;
        }
        body {
                width: 8.3in;
                margin:auto;
            }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                width: 8.3in;
                margin:auto;
                
            }
            .table-bordered th, .table-bordered td {
                border: 1px solid #3F4254 !important;
            }
            .fs-13pt,
            .fs-13pt th,
            .fs-13pt td {
                font-size: 13pt !important;
            }
             .print-content {
                margin: 0.3in 0.3in;
                position: relative;
                z-index: 100;
            }
            
            .print-content .p-break-after{
                  page-break-after:always;
            }
            
            .print-content .p-break-before{
                  page-break-before:always;
            }
        }
        @page { 
            size: <?= $size ?>;
            margin: 0in;
            -webkit-print-color-adjust: exact;
            
        }
    </style>
    <script type="text/javascript">
        var base_url = "<?= Url::home(true) ?>";
    </script>
</head>
<body style="background-color: #fff;height: auto;  position: relative;">
<?php $this->beginBody() ?>
   <div class="print-content">
    <?= $content ?>
   </div>
  <?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>