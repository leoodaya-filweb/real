<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url; 
use yii\helpers\Inflector; 
 
class ExportButton extends BaseWidget
{
    public $actions = [
        'print' => [
            'title' => 'Print',
            'icon' => 'print',
        ],
        'export-pdf' => [
            'title' => 'PDF',
            'icon' => 'pdf',
            'ext' => '.pdf'
        ],
        'export-csv' => [
            'title' => 'CSV',
            'icon' => 'csv',
            'ext' => '.csv'
        ],
        // 'export-xls' => [
        //     'title' => 'XLS 95',
        //     'icon' => 'excel',
        //     'ext' => '.xls'
        // ],
        'export-xlsx' => [
            'title' => 'XLSX 2007',
            'icon' => 'excel',
            'ext' => '.xlsx'
        ],
    ]; 
    
    public $exports = [];
    public $controller;
    public $title = 'Export Data';
    public $view = 'widget';
    public $user;

    public $anchorOptions = [
        'class' => 'btn btn-bg-white btn-text-dark-50 btn-hover-text-primary btn-icon-primary font-weight-bolder font-size-sm px-5 mr-3',
        'data-toggle' => 'dropdown',
        'aria-haspopup' => true,
        'aria-expanded' => false
    ];

    public $printUrl;
    public $pdfUrl;
    public $csvUrl;
    public $xlsUrl;
    public $xlsxUrl;

    public $filename;

    public $exportAction;

    public function init() 
    {
        // your logic here
        parent::init();

        if (App::isLogin()) {
            $this->user = $this->user ?: App::identity();
        }

        $access = App::component('access');
        $this->controller = $this->controller ?: App::controllerID();

        foreach ($this->actions as $action => $data) {
            $theAction = $this->exportAction ?: $action;
            if ($this->user->can($theAction, $this->controller)) {
                $params = App::queryParams();
                array_unshift($params, $action);
                $link = Url::to($params);

                $icon = Html::isHtml($data['icon'])? $data['icon']: $this->render("icon/{$data['icon']}");

                $title = "{$icon}<span class='navi-text'> &nbsp; {$data['title']}</span>";


                if ($action == 'print') {
                    if ($this->printUrl) {
                        $arr = array_merge($this->printUrl, App::queryParams());
                        $link = Url::to($arr);
                    }

                    $this->exports[] = Anchor::widget([
                        'title' => $title,
                        'link' => '#!',
                        'options' => [
                            'class' => 'navi-link',
                            'onclick' => "popupCenter('{$link}')"
                        ]
                    ]);
                }
                else {
                    if ($action == 'export-pdf') {
                        if ($this->pdfUrl) {
                            $arr = array_merge($this->pdfUrl, App::queryParams());
                            $link = Url::to($arr);
                        }
                    }
                    elseif ($action == 'export-csv') {
                        if ($this->csvUrl) {
                            $arr = array_merge($this->csvUrl, App::queryParams());
                            $link = Url::to($arr);
                        }
                    }
                    elseif ($action == 'export-xls') {
                        if ($this->xlsUrl) {
                            $arr = array_merge($this->xlsUrl, App::queryParams());
                            $link = Url::to($arr);
                        }
                    }
                    elseif ($action == 'export-xlsx') {
                        if ($this->xlsxUrl) {
                            $arr = array_merge($this->xlsxUrl, App::queryParams());
                            $link = Url::to($arr);
                        }
                    }

                    if ($this->filename) {
                        $name = $this->filename . $data['ext'];
                    }
                    elseif ($this->controller) {
                        $name = Inflector::id2camel($this->controller) . ' Report' . $data['ext'];
                    }
                    else {
                        $name = $action . $data['ext'];
                    }

                    $this->exports[] = Anchor::widget([
                        'title' => $title,
                        'link' => '#!',
                        // 'link' => $link,
                        'options' => [
                            'class' => 'navi-link export-link',
                            'data-link' => $link,
                            'data-name' => $name,
                        ]
                    ]);
                }
            }
        }
    }


    /**
     * {@inheritdoc}
     */
    public function run()
    { 
        if (! $this->exports) {
            return ;
        }
        return $this->render('export-button', [
            'exports' => $this->exports,
            'title' => $this->title,
            'anchorOptions' => $this->anchorOptions,
        ]); 
    }
}
