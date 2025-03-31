<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Specialsurvey;
use app\widgets\Anchors;
use app\widgets\BulkAction;
use app\widgets\ExportButton;
use app\widgets\FilterColumn;
use app\widgets\Grid;
use app\widgets\TinyMce;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SpecialsurveySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Socio Economic Survey';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['showCreateButton'] = true; 
$this->params['showExportButton'] = true;
$this->params['activeMenuLink'] = '/specialsurvey';
$this->params['createTitle'] = 'Create Survey';

$this->params['headerButtons'] = implode(' ', [
    Html::a('Import CSV', ['specialsurvey/importcsv'],[
        'class' => "font-weight-bold btn btn-primary font-weight-bolder font-size-sm btn-create ml-1",
    ]),
    Html::a('Print Survey Form', '#', [
        'data-toggle' => 'modal',
        'data-target' => '#modal-survey-form',
        'class' => 'btn btn-white font-weight-bold'
    ])
]);

$this->registerJsFile('https://polyfill.io/v3/polyfill.min.js?features=default', [
    'position' => View::POS_HEAD
]);

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDDsziO7yBi_o0dmCucMAUgqUKp8o3ldNY&callback=initMap&v=weekly',[
    'defer' => 'defer',
    'position' => View::POS_END
]);

$this->registerJs(<<< JS
    $(document).ready(function () {
        let map;
        let infoWindow; 
    });

    function extractUrlParams() {
        const params = new URLSearchParams(window.location.search)
        let url = [];
        for (const param of params) {
            url.push([param[0], param[1]].join('='));
        }
        return url.join('&');
    }

    let url = extractUrlParams();

    if (url) {
        url = 'specialsurvey/barangay-coordinates?' + url;
    }
    else {
        url = 'specialsurvey/barangay-coordinates';
    }
    let coordinates_url = app.baseUrl + url;

    console.log('coordinates_url', coordinates_url)

    function initMap(url) {
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 11,
            center: { lat: 14.55808300806936, lng: 121.56454222364401 },
            mapTypeId: 'satellite',
            // mapTypeControl: false,
        });
        // Load GeoJSON.
        //"https://storage.googleapis.com/mapsdevsite/json/google.json
        map.data.loadGeoJson(url);

        // Color each letter gray. Change the color when the isColorful property
        // is set to true.
        map.data.setStyle((feature) => {
            let color = "#8a6d3b";

            color = feature.getProperty("color");

            return /** @type {!google.maps.Data.StyleOptions} */ {
                fillColor: color,
                fillOpacity: 0.7,
                strokeColor: color,
                strokeWeight: 2,
            };
        });

        // When the user clicks, set 'isColorful', changing the color of the letters.
        map.data.addListener("click", (event) => {
            event.feature.setProperty("isColorful", true);
            // document.getElementById('barangay').innerHTML = event.feature.getProperty("color");
            showArrays(event);
        });

        infoWindow = new google.maps.InfoWindow();

        // When the user hovers, tempt them to click by outlining the letters.
        // Call revertStyle() to remove all overrides. This will use the style rules
        // defined in the function passed to setStyle()
        map.data.addListener("mouseover", (event) => {
            map.data.revertStyle();
            map.data.overrideStyle(event.feature, { strokeWeight: 5 });


            let barangay = event.feature.getProperty("barangay"),
                color = event.feature.getProperty("color"),
                household = event.feature.getProperty("household"),
                household_colors = event.feature.getProperty("household_colors"),
                url_link = event.feature.getProperty("url_link");

            let html = '';
                html += '<table class="table table-bordered mt-5">';
                    html += '<tbody>';
                        html += '<tr>';
                            html += '<th width="30%">name</th>';
                            html += '<td>'+ barangay +'</td>';
                        html += '</tr>';
                        html += '<tr>';
                            html += '<th>color</th>';
                            html += '<td><div style="width:20px;height:20px;background:'+color+'"></div> </td>';
                        html += '</tr>';
                        html += '<tr>';
                            html += '<th>Surveyee</th>';
                            html += '<td>'+ household +'</td>';
                        html += '</tr>';
                        html += '<tr>';
                            html += '<th>summary</th>';
                            html += '<td>';
                                for (const hc in household_colors) {
                                    html += '<div>';
                                        html += '<span style="width:15px;height:15px;background:'+household_colors[hc].color+'; color: '+household_colors[hc].color+'">234</span> <strong class="ml-2">'+ household_colors[hc].label +'</strong>: ' + household_colors[hc].total;
                                    html += '</div>';
                                }
                            html += '</td>';
                        html += '</tr>';
                    html += '</tbody>';
                html += '</table>';
            document.getElementById('barangay').innerHTML = html;
        });

        map.data.addListener("mouseout", (event) => {
            map.data.revertStyle();
        });
    }

    function showArrays(event) {
        // Since this polygon has only one path, we can call getPath() to return the
        // MVCArray of LatLngs.
        // @ts-ignore
        // const polygon = this;
        //const vertices = polygon.getPath();
        const vertices = event.feature.getGeometry().getAt(0);
        const household_colors = event.feature.getProperty("household_colors");

        let contentString =
        "<b><a href=\""+event.feature.getProperty("url_link")+"\" >"+event.feature.getProperty("barangay")+"</a></b><br>" +
        "Clicked location:<br>"+event.latLng.lat()+","+event.latLng.lng() +
        "<br>" +
        "<b>Total Respondent: "+event.feature.getProperty("household")+"</b><br>" +
        "<b><a href=\""+event.feature.getProperty("url_link")+"\" >View Details</a></b><br>" +
        "Colors: <br>" ;

        // Iterate over the vertices.
        for (let i = 0; i < household_colors.length; i++) {
            contentString +=  "<div  ><span style=\" color:"+ household_colors[i].color +"; \">&#9744;</span>" + household_colors[i].label + ": "+ household_colors[i].total+"</div>";
        }

        // Replace the info window's content and position.
        infoWindow.setContent(contentString);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
    }

    window.initMap = initMap(coordinates_url);


    function isEmpty(obj) {
        for(var prop in obj) {
            if(Object.prototype.hasOwnProperty.call(obj, prop)) {
                return false;
            }
        }

        return JSON.stringify(obj) === JSON.stringify({});
    }

    $('#select-criteria').change(function() {
        $('#barangay').html('Survey summary details will go here.');
        let criteria = $(this).val();
        let url = extractUrlParams();
        if(url) {
            window.initMap = initMap(coordinates_url + '&criteria=' + criteria);
        }
        else {
            window.initMap = initMap(coordinates_url + '?criteria=' + criteria);
        }
    });
JS);	

$this->registerCss(<<< CSS
    #map {
        height: 500px;
        width: 100%;
    }
CSS, ['type' => "text/css"]);
?>

<div class="specialsurvey-index-page">
    
    <div class="d-flex align-items-center mb-2">
        <div class="lead font-weight-bold">
            Survey Month (<?= App::formatter()->asDateToTimezone('', 'Y') ?>)
        </div>
        <div class="dropdown ml-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= App::get('survey_name') ?: 'Select Survey' ?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <?= Html::foreach(Specialsurvey::filter('survey_name', [
                    'DATE_FORMAT(date_survey, "%Y")' => App::setting('currentYear')
                ]), function($name) {
                    return Html::a($name, Url::to(['index', 'date_range' => App::get('date_range'), 'survey_name' => $name]), [
                        'class' => 'dropdown-item'
                    ]);
                }) ?>
            </div>
        </div>
    </div>
    <div class="btn-group mr-2" role="group">
        <?= Html::foreach(Specialsurvey::monthFilter(), function($month) {
            return Html::a($month['label'], Url::to(['index', 'date_range' => $month['date_range'], 'survey_name' => App::get('survey_name')]), [
                'class' => 'btn btn-outline-secondary' . (App::get('date_range') == $month['date_range'] ? ' active font-weight-bolder': '')
            ]);
        }) ?>
    </div>

    <div class="my-10"></div>

    <div class="row"> 
        <div class="col-md-8"> 
            <div id="map"></div>
        </div>
        <div class="col-md-4"> 
            <div class="row">
                <div class="col-md-6">
                    <strong class="lead font-weight-bold">BARANGAY DETAILS</strong>
                </div>
                <div class="col-md-6">
                    <select class="form-control" id="select-criteria">
                        <?= Html::foreach([1, 2, 3, 4, 5], function($n) {
                            return Html::tag('option', "Criteria {$n}", [
                                'value' => $n,
                                'selected' => App::get("criteria{$n}_color_id") ? true: false
                            ]);
                        }) ?>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div id="barangay">
                        Survey summary details will go here.
                    </div>
                </div>
            </div>

            <div class="row mt-10">
                <div class="col-md-12">
                    <div class="lead">
                        <strong class="font-weight-bold">
                            COLOR PRIORITY
                        </strong>
                        (<strong><?= App::setting('surveyColor')->dominance_percentage ?>%</strong> DOMINANCE)
                    </div>
                    <ul>
                        <?= Html::foreach(Specialsurvey::colorPriority(), function($survey) {
                            return Html::tag('li', implode(' - ', [
                                $survey['label'],
                                $survey['priority'],
                            ]));
                        }) ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="my-5"></div>

    <?= FilterColumn::widget(['searchModel' => $searchModel]) ?>
    <?= Html::beginForm(['bulk-action'], 'post'); ?>
        <?= BulkAction::widget(['searchModel' => $searchModel]) ?>
        <?= Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]); ?>
    <?= Html::endForm(); ?> 
</div>


<div class="modal fade" id="modal-survey-form" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Printable Survey Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <?= TinyMce::widget([
                    'content' => App::setting('reportTemplate')->survey_form,
                    'size' => 'A4',
                    'menubar' => false,
                    'toolbar' => 'print',
                    'height' => '400mm',
                    'plugins' =>  'print pagebreak',
                    'readonly' => true,
                    'setup' => <<< JS
                        editor.on('SkinLoaded', function() {
                            $(".tox-toolbar-overlord").removeClass('tox-tbtn--disabled');
                            $(".tox-toolbar-overlord").attr( 'aria-disabled', 'false' );
                            // And activate ALL BUTTONS styles
                            $(".tox-toolbar__group button").removeClass('tox-tbtn--disabled');
                            $(".tox-toolbar__group button").attr( 'aria-disabled', 'false' );
                        });
                    JS
                ]) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>