<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;

class TinyMce extends BaseWidget
{
    public $model;
    public $attribute;

    public $textInput;
    public $content;

    public $height = '70vh';
    public $min_height = 842;
    public $toolbar_sticky = true;

    public $options = [
        'resize' => 'both',
        'toolbar_mode' => 'floating',
        'toolbar_location' => 'bottom, top',
        // ** Other interesting options (use as needed) **
        'branding' => false,  // Remove the "Powered by Tiny"
        'elementpath' => true,  // Stop showing the selected element TAG 
        'statusbar' => true,    // Set the statusbar as visible (yes, you can hide it)
        'noneditable_noneditable_class' => 'fa',
        'extended_valid_elements' => 'span[*]',
        // 'setup' => ( function ( editor ) {
        //     //  Fired when the editor skin has been loaded.
        //     editor.on('SkinLoaded', function() {
        //         // remove buttons here
        //         // Change Toolbar styles
        //         // $(".tox-toolbar-overlord").first().removeClass('tox-tbtn--disabled');
        //         // $(".tox-toolbar-overlord").first().attr( 'aria-disabled', 'false' );

        //         // // And activate ALL BUTTONS styles
        //         // $(".tox-toolbar__group button").removeClass('tox-tbtn--disabled');
        //         // $(".tox-toolbar__group button").attr( 'aria-disabled', 'false' );
        //     })
        // })
    ];

    // public $toolbar = 'advlist | autolink | link image | lists charmap | print preview | code | table tabledelete | pagebreak';
    // public $plugins = 'advlist autolink link image lists charmap print preview code table pagebreak';
    public $toolbar = 'preview | print | undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen save | insertfile image media template link anchor codesample | ltr rtl';
    
    public $plugins = 'print preview paste importcss searchreplace autolink save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons';

    public $readonly = false;
    public $menubar = true;
    public $setup;

    public $size = '8.5in 13in';
    public $landscapeA4 = false;
    public $zoom = '70%';
    public $margin='0.3in 0.5in';
    public $bodymargin='auto';

    public $content_style;


    public function init()
    {
        if ($this->landscapeA4) {
            $this->size = '13in 8.5in';
        }

        if (App::isLogin() && App::identity('isDeveloper')) {
            if (! str_contains($this->toolbar, 'print')) { 
                $this->toolbar = $this->toolbar  . " | print";
            }

            if (! str_contains($this->plugins, 'print')) { 
                $this->plugins = $this->plugins  . " print";
            }
        }

        if ($this->model && $this->attribute) {
            $this->textInput = Html::activeTextarea($this->model, $this->attribute, [
                'class' => 'tox-target',
            ]);
        }
        else {
            $this->textInput = Html::textarea('textarea-' . $this->id, $this->content, [
                'class' => 'tox-target',
                'id' => 'tinymce-textarea-id'
            ]);
        }
        $this->options['selector'] = "#tinymce-{$this->id} textarea";
        $this->options['toolbar'] = $this->toolbar;
        $this->options['plugins'] = $this->plugins;
        $this->options['readonly'] = $this->readonly;
        $this->options['menubar'] = $this->menubar;
        $this->options['min_height'] = $this->min_height;
        $this->options['toolbar_sticky'] = $this->toolbar_sticky;
        $this->options['content_style'] = <<< CSS
            {$this->content_style}
            .mce-content-body img[data-mce-selected], .mce-content-body table[data-mce-selected] {
                outline: 3px solid transparent;
            }
            .mce-content-body div.mce-resizehandle {
                background-color: transparent;
                border-color: transparent;
            }

            @media print {
                body {
                    -webkit-print-color-adjust: exact;
                    margin: {$this->bodymargin};
                    zoom: {$this->zoom};
                }
   
                
              .table-print{
                     border-collapse: collapse;
                     width: 100%;
                     border: none!Important;
                     border-style: none;
                     margin: 0px;
              }
               .content-td, .header-cert, .footer-td{
                 border: none!Important;
                 padding:0px;
              }
              
              .header-cert{
                 height: 55mm;
              }
              
              .footer-td{
                 height: 55mm;
              }
         
   
                
                .content-report-cert {
                   padding:0mm 0mm 0mm 0mm;
                  // min-height: 613.0mm;
                   margin-top:-30px;
                }
                .content-report-cert, .content-report-cert p {
                    page-break-inside: avoid;
                    }
                    
                .cert-content {
                   padding-left:0.5in!Important; padding-right:0.5in!Important; padding-bottom:2.5in!Important;
                }
                
                
            }
            @page { 
                size: {$this->size};
                // size: A4;
                margin: {$this->margin};
                -webkit-print-color-adjust: exact;
                
            }
            
            

            
            
        CSS;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('tinymce', [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'textInput' => $this->textInput,
            'height' => $this->height,
            'withSetup' => $this->setup ? 'true': 'false',
            'setup' => $this->setup,
            'options' => json_encode($this->options),
        ]);
    }
}
