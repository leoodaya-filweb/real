<?php

namespace app\widgets;

class PieChart extends BaseWidget
{
    public $data = [];
    public $width;
    public $colors;
    public $datasort=true;
    public $options = [
        'chart' => [
            'width' => 400,
            'type' => 'pie',
        ],
        'legend' => [
            'position' => 'bottom',
            'fontSize' => '9px', // Adjust font size to make the legend smaller
            'itemMargin' => [
                'horizontal' => 5, // Add horizontal padding
                'vertical' => 5   // Add vertical padding
            ]
        ],
        'tooltip' => [
            'x' => [
                'show' => false
            ],
            'y' => [
                'formatter' => 'function(value, series) {
                    return value.toFixed(2);
                }'
            ]
        ],
        'responsive' => [
            [
                'breakpoint' => 400,
                'options' => [
                    'chart' => [
                        'width' => 200
                    ],
                    'legend' => [
                        'position' => 'bottom',
                        'fontSize' => '10px', // Smaller font size for smaller screens
                        'itemMargin' => [
                            'horizontal' => 3,
                            'vertical' => 3
                        ]
                    ]
                ]
            ]
        ],
        'colors' => ['#6993FF', '#1BC5BD', '#FFA800', '#F64E60', '#8950FC', '#3b5998', '#1da1f2']
    ];
    public $showChart = false;


    public function init() 
    {
        // your logic here
        parent::init();
        if($this->datasort){
        arsort($this->data);
        }

        $this->options['series'] = array_values($this->data);
        $this->options['labels'] = array_keys($this->data);
        $this->options['chart']['width'] = $this->width ?: $this->options['chart']['width'];
        $this->options['colors'] = $this->colors ?: $this->options['colors'];

        foreach ($this->data as $d) {
            if ($d > 0) {
                $this->showChart = true;
            }
        }
    }


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->showChart == false) {
            return ;
        }

        return $this->render('pie-chart/index', [
            'data' => $this->data,
            'options' => json_encode($this->options),
        ]);
    }
}
