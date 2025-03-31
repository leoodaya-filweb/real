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
                margin: 0.3in 0.5in;
                position: relative;
                z-index: 100;
            }
            
            .break-before-report{
            page-break-before: always;
            }
            
            .break-after-report{
            clear: both;
            page-break-before: inherit;
            }
        }
        @page { 
            size: <?= $size ?>;
            margin: 0in;
            -webkit-print-color-adjust: exact;
            page-break-after: always;
        }
    </style>
    <script type="text/javascript">
        var base_url = "<?= Url::home(true) ?>";
    </script>
</head>
<body style="background-color: #fff;height: auto;  position: relative;">
<?php $this->beginBody() ?>

   <div class="print-content">
    <!-- begin:: Page --> 
    <?= Html::if($this->params['withHeader'] ?? true, 
    //ReportTemplate::widget()
     ReportTemplate::widget(['template'=>'header_new'])
    ) ?>
    <div class="my-2"></div>
    <?= $content ?>

    <?php if ($this->params['withHeader'] ?? true): ?>
        
      <div class="print-tbl-footer">
        <div>
            <em>Printed on <?= App::formatter()->asDateToTimezone() ?> via accessgov.ph</em>
        </div>
      </div>
      
       <?php 
      
     if(App::identity('role_id')==3){
      //echo ReportTemplate::widget(['template'=>'footer']);
      
  
      //echo  ReportTemplate::widget(['template'=>'header_new']);
      
      }
      
      ?>
      
      
      
    <?php endif ?>

    <script>
        var KTAppSettings = { 
            "breakpoints": { 
                "sm": 576, 
                "md": 768, 
                "lg": 992, 
                "xl": 1200, 
                "xxl": 1400 
            }, 
            "colors": { 
                "theme": { 
                    "base": { 
                        "white": "#ffffff", 
                        "primary": "#3E97FF", 
                        "secondary": "#E5EAEE", 
                        "success": "#08D1AD", 
                        "info": "#844AFF", 
                        "warning": "#F5CE01", 
                        "danger": "#FF3D60", 
                        "light": "#E4E6EF", 
                        "dark": "#181C32" 
                    }, 
                    "light": {
                        "white": "#ffffff", 
                        "primary": "#DEEDFF", 
                        "secondary": "#EBEDF3", 
                        "success": "#D6FBF4", 
                        "info": "#6125E1", 
                        "warning": "#FFF4DE", 
                        "danger": "#FFE2E5", 
                        "light": "#F3F6F9", 
                        "dark": "#D6D6E0" 
                    }, 
                    "inverse": { 
                        "white": "#ffffff", 
                        "primary": "#ffffff", 
                        "secondary": "#3F4254", 
                        "success": "#ffffff", 
                        "info": "#ffffff", 
                        "warning": "#ffffff", 
                        "danger": "#ffffff", 
                        "light": "#464E5F", 
                        "dark": "#ffffff" 
                    } 
                }, 
                "gray": { 
                    "gray-100": "#F3F6F9", 
                    "gray-200": "#EBEDF3", 
                    "gray-300": "#E4E6EF", 
                    "gray-400": "#D1D3E0", 
                    "gray-500": "#B5B5C3", 
                    "gray-600": "#7E8299", 
                    "gray-700": "#5E6278", 
                    "gray-800": "#3F4254", 
                    "gray-900": "#181C32" 
                } 
            }, 
            "font-family": "Poppins" 
        };
    </script>
<?php $this->endBody() ?>

   </div>

  <?php if(!$this->params['noFooter']) { ?>
  <div class="printfooter" style="background-image: url(<?= $file->displayPath ?>); background-position: center bottom;background-repeat: no-repeat;   background-size: contain;
    height: 200px; position: fixed;left: 0; bottom: 0px; width: 100%; z-index:0;">
  </div>  
   <?php } ?>


</body>
</html>
<?php $this->endPage() ?>