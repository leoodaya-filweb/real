<?php

use app\helpers\Html;

$this->registerWidgetJs($widgetFunction, <<< JS
    let options = {$options};

    if({$withSetup}) {
        options['setup'] = function(editor) {
            {$setup}
        }
        // options['init_instance_callback'] = function (editor) {
        //     editor.on('ExecCommand', function (e) {
        //       if (e.command === 'mcePrint') {
        //       }
        //     });
        //   }
    }

    tinymce.init(options); 

    // $('.print-btn-{$widgetId}').click(function() {
    //     tinymce.activeEditor.execCommand("mcePrint", true);
    // })
JS);

$this->registerCss(<<< CSS
    #tinymce-{$widgetId} .tox-tinymce {
        height: {$height} !important;
        max-height: {$height} !important;
    }
CSS);
?>
<div class="tinymce" id="tinymce-<?= $widgetId ?>">
    <?= $textInput ?>
</div>

<!-- <a class="print-btn-<?= $widgetId ?>">Trigger TinyMCE Print</a> -->