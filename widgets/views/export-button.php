<?php

use app\helpers\Html;

$js = <<< JS
    // let popupCenter = (url, title='Print Report', w=1000, h=700) => {
    //     // Fixes dual-screen position                             Most browsers      Firefox
    //     const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
    //     const dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;
    //     const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    //     const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
    //     const systemZoom = width / window.screen.availWidth;
    //     const left = (width - w) / 2 / systemZoom + dualScreenLeft
    //     const top = (height - h) / 2 / systemZoom + dualScreenTop
    //     const newWindow = window.open(url, title, 
    //       `
    //       scrollbars=yes,
    //       width=(w/systemZoom), 
    //       height=(h/systemZoom), 
    //       top=top, 
    //       left=left
    //       `
    //     )
    //     if (window.focus) {
    //         // setTimeout(newWindow.print(), 1000);
    //     } 
    // }

    $('#{$widgetId} .export-link').on('click', function() {
        KTApp.blockPage({
            overlayColor: '#000000',
            message: 'Exporting...',
            state: 'primary' // a bootstrap color
        });

        let a = $(this),
            link = a.data('link'),
            name = a.data('name');

        fetch(link)
            .then(resp => resp.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                // the filename you want
                a.download = name;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                KTApp.unblockPage();
            })
            .catch((e) => {
                toastr.error(e.responseText);
                KTApp.unblockPage();
            });
    });
JS;
$this->registerWidgetJs($widgetFunction, $js);
?>
<div class="dropdown dropdown-inline" data-toggle="tooltip" title="Quick actions" data-placement="top" id="<?= $widgetId ?>">
    <?= Html::a(<<< HTML
        <i class="fa fa-download"></i>
        {$title}
    HTML, '#', $anchorOptions) ?>
    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right p-0 m-0">
        <!--begin::Navigation-->
        <ul class="navi navi-hover">
            <li class="navi-header pb-1">
                <span class="text-primary text-uppercase font-weight-bolder font-size-sm">
                    Choose
                </span>
            </li>
            <?= Html::foreach($exports, function($anchor) {
                return '<li class="navi-item">' . $anchor . '</li>';
            }) ?>
        </ul>
        <!--end::Navigation-->
    </div>
</div>