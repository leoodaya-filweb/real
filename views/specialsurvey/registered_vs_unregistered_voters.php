<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Specialsurvey;
use app\widgets\Anchors;
use app\widgets\BulkAction;
use app\widgets\DateRange;
use app\widgets\ExportButton;
use app\widgets\FilterColumn;
use app\widgets\Grid;
use app\widgets\Mapbox;
use app\widgets\TinyMce;
//use yii\helpers\Url;
use app\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Registered VS Unregistered Voters';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['searchForm'] = 'testvv';
$this->params['showCreateButton'] = false;//true; 
$this->params['showExportButton'] = false; //true;
$this->params['activeMenuLink'] = '/specialsurvey/registered-vs-unregistered-voters';
$this->params['createTitle'] = 'Create Survey';




$this->registerCss(<<< CSS
    #map {
        height: 500px;
        width: 100%;
    }
    .color-box {
        width: 20px;
        height: 20px;
        border-radius: 4px;
    }
    
    .new-map .mapboxgl-ctrl-geocoder, .new-map .mapboxgl-ctrl-bottom-right, .new-map .mapboxgl-ctrl-bottom-left, .new-map .mapboxgl-ctrl-geolocate{
        display: none;
    }
    
    #barangay-new{
    position: absolute;
    bottom: 97px;
    right: 14px;
    background: #ffffff;
    padding: 10px;
    font-size: 11px;
    z-index: 10;
    width: 250px;
    }
    
    .content-legend{
    position: absolute;
    bottom: 2px;
    right: 10px;
    background: #ffffff;
    padding: 10px;
    font-size: 11px;
    z-index: 9;
    width: 250px;
    }
    
    .graph-content{
    position: absolute;
    bottom: 100px;
    right: 10px;
    background: #ffffff;
    padding: 10px;
    font-size: 12px;
    font-weight: bold;
    z-index: 9;
    width: 250px;    
    }
    
    .color-voters{
    position: absolute;
    z-index: 10;
    top: 100px;
    right: 10px;
    background: #cbcbcbb5;
    padding: 10px;
        
    }
    
    .new-map .badge:empty, .new-map li {
     display: inline-block;
    }
    
    .new-map ul {
       padding-left: 10px;
     }
     
    .new-map .view-badge{
    padding: 3px !important;
    padding-right: 3px !important;
    padding-left: 3px !important;
    background-color: #fff !important;
    outline: 1px solid #337ab7 !important;
     }

    
CSS, ['type' => "text/css"]);


$this->registerJs(<<<JS
         
    // $('.filter-select').change(function () {
    //     var barangay = $('#select-barangay').val();


    //     var selectedPurok = $('#select-purok').val();
    //     if ($(this).attr('id') === 'select-barangay') {
    //         selectedPurok = ""; // Reset purok if barangay changes
    //     }

    //     $.ajax({
    //         url: '/real/web/specialsurvey/registered-vs-unregistered-voters?list=1',
    //         method: 'get',
    //         data: { barangay, purok: selectedPurok,  },
    //         success: function (response) {
    //             const data = JSON.parse(response.chartData);
    //             const label = JSON.parse(response.barangayLabels);
                
              
    //             $('#content-listing').html(response.membersHtml);  
    //             renderChart(data, label);
               
                
    //         },
    //         error: function (e) {
    //             console.log('AJAX error', e);
    //         }
    //     });
    // });
     
    function renderChart(chart_data_json,barangay_labels_json){
        
        
        let options = {
            series: chart_data_json,
            chart: {
                type: 'bar',
                height: 650,
                stacked: true,
                toolbar: { show: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    borderRadius: 5,
                }
            },
            stroke: { width: 1, colors: ['#fff'] },
            title: {
                text: 'Registered Vs Unregistered Voters',
                align: 'center',
                style: {
                    fontSize: '26px',
                    fontWeight: 'bold',
                    color: '#333'
                }
            },
            xaxis: {
                categories: barangay_labels_json,
                labels: {
                    style: { fontSize: '14px',  colors: '#333' }
                },
                axisBorder: { show: true, color: '#ddd' },
                axisTicks: { show: true, color: '#ddd' }
            },
            yaxis: {
                labels: {
                    style: { fontSize: '13px', colors: '#555' }
                },
                title: {
                    text: 'Number of Voters',
                    style: { fontSize: '16px', fontWeight: 'bold', color: '#333' }
                }
            },
            tooltip: {
                theme: 'dark',
                y: { formatter: function (val) { return val + " Voters"; } }
            },
            fill: { opacity: 0.9 },
            grid: {
                borderColor: '#e0e0e0',
                strokeDashArray: 4,
                padding: { left: 10, right: 10, top: 20, bottom: 20 }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '14px',
                markers: { radius: 4 },
                labels: { colors: '#333' }
            },
            colors: ['#1B98E0', '#D72638'], // Blue for registered, Red for unregistered
            dataLabels: {
                enabled: true,
                style: { fontSize: '13px', fontWeight: 'bold', colors: ['#fff'] },
                formatter: function (val, opts) {
                    return val; // Show actual number
                }
            }
        };

        document.querySelector("#registered-vs-unregistered-graph").innerHTML = "";

        var chart = new ApexCharts(document.querySelector("#registered-vs-unregistered-graph"), options);
        chart.render();
    }

    renderChart($chartData, $barangayLabels);

JS);


         $contentlegend ='<div class="content-legend">
                        <strong class="font-weight-bold">
                            Color Assignment
                        </strong>
                        (<strong>'.App::setting('surveyColor')->dominance_percentage.'%</strong> Dominance)
                      <ul>'.Html::foreach(Specialsurvey::colorPriority(), function($survey) {
                            return Html::tag('li', implode(' - ', [
                                '<span class="badge badge-pill" style="background-color: '.$survey['color'].'; height: 10px; width: 10px; padding:0px!Important;"></span> '.$survey['label'],
                                $survey['id']
                            ]));
                        })
                     .'</ul>
                </div>';





?>



<section class="mt-5 new-map" style="position: relative;">
    
    
    <div class="d-flex align-items-center">
        <div>
            <p class="lead font-weight-bold mb-0">Filters: </p>
        </div>
        
        
        <!-- <div class="ml-5 text-center color-voters" >
            <div id="checkbox-colors" class="checkbox-list"> 
                <label class="checkbox"><input type="checkbox" checked value="1" name="color[]">  <span class="color-box" style="background: #5096f2;"></span>  Blue</label>
                <label class="checkbox"> <input type="checkbox" checked value="2" name="color[]">  <span class="color-box" style="background: #e4e6ef;"></span>  Gray</label>
                <label class="checkbox"> <input type="checkbox" checked value="3" name="color[]">  <span class="color-box" style="background: #000000;"></span>  Blackx</label>
                <label class="checkbox"> <input type="checkbox" checked value="4" name="color[]">  <span class="color-box" style="background: #404040;"></span>  Blacky</label>
                <label class="checkbox"> <input type="checkbox" checked value="5" name="color[]">  <span class="color-box" style="background: #808080;"></span>  Blacku</label>
            </div>
        </div> -->
        
        
        <div class="ml-5">
           <select class="form-control filter-select" id="select-barangay">
                <?=  Html::tag('option', 'All Barangay', [
                    'value' => '',
                   // 'selected' => true,
                    //'disabled' => true
                ]) ?>
                <?= Html::foreach(Specialsurvey::filter('barangay'), function($name)use($searchModel) {
                 
                    return Html::tag('option', $name, [
                        'value' => $name,
                        'selected' => trim($searchModel->barangay)==trim($name)?true:false
                    ]);
                }) ?>
            </select>


        </div>
        <div class="ml-5">
        <select class="form-control filter-select" id="select-purok">
                <?=  Html::tag('option', 'All Purok', [
                    'value' => '',
                    //'selected' => true,
                    //'disabled' => true
                ]) ?>
                <?= Html::foreach(Specialsurvey::filter('purok'), function($name)use($searchModel) {
                 
                    return Html::tag('option', $name, [
                        'value' => $name,
                        'selected' => trim($searchModel->purok)==trim($name)?true:false
                    ]);
                }) ?>
            </select>
         </div>
        
       
      
        
        
        
        
        
        <div class="ml-5 text-center">
            <div>Opacity <span>(85)</span></div>
            <input type="range" name="" value="85" min="1" max="100">
        </div>
    </div>
    

    <?= Mapbox::widget([
        'enableClick' => false,
        'draggableMarker' => true,
        'showMarker' => false,
        'class'=>'map-new',
       // 'lnglat' => [$model->longitude, $model->latitude],
        'zoom'=>11.12,
        'pitch'=>50.00,
        'bearing'=>-24.20,
        'lnglat' => [121.586287, 14.396804],
        'mapLoadScript' => <<< JS

                 
                 let show = false; //or true, etc.                   
                 map.style.stylesheet.layers.forEach(function(layer) {
                     if (layer.type === 'symbol') {
                         map.setLayoutProperty(layer.id,"visibility", show?"visible":"none");
                         }
                     
                 });
                 
                 map.addControl(new mapboxgl.FullscreenControl());
           
                 
                 //Barangay label
                   const datasourcebgryUrl = app.baseUrl + 'specialsurvey/unregistered-voters-population?brgy=1';
                 
                    map.addSource('brgylabel', {
                      type: 'geojson',
                      data: datasourcebgryUrl
                    });
                    
                    
                    map.addLayer({
                        'id': 'brgy-labels',
                        'type': 'symbol',
                        'source': 'brgylabel',
                        'layout': {
                            'text-field': [
                                 'format',
                                 ['get', 'name'], 
                                { 'font-scale': 0.7 },
                                ],
                            'text-variable-anchor': ['top', 'bottom', 'left', 'right'],
                            'text-radial-offset': 0.5,
                            'text-justify': 'auto',
                            'text-font': ['Open Sans Semibold', 'Arial Unicode MS Bold'],
                          
                            //'icon-image': ['get', 'icon']
                            },
                            
                           'paint': { "text-color": "#202", "text-halo-color": "#fff","text-halo-width": 2}   
                          });
                 
                 
                 
                 
                   //Voters
                 
                   const datasourceUrl = app.baseUrl + 'specialsurvey/unregistered-voters-population?barangay={$searchModel->barangay}&purok={$searchModel->purok}';
            
                
                   map.addSource('voters', {
                        type: 'geojson',
                        data: datasourceUrl
                    });

                    map.addLayer({
                        id: 'population',
                        type: 'circle',
                        paint: {
                            'circle-radius': {
                                'base': 1.75,
                                'stops': [[8, 1], [11, 3], [16, 40]]
                            },
                            'circle-color': 'red',
                            'circle-opacity': 0.8
                        },
                        source: 'voters'
                    }, 'aeroway-polygon');

                      

                      
                       let populationClick= 0;
                       map.on('click', 'population', (e) => {
                           populationClick=1;
                           const coordinates = e.features[0].geometry.coordinates.slice();
                           //const votersName = e.features[0].properties.first_name+' '+e.features[0].properties.middle_name+' '+e.features[0].properties.last_name;
                           const votersName = e.features[0].properties.last_name;
                           const total_voters = e.features[0].properties.total_voters;
                           const householdNo = e.features[0].properties.household_no;
                           const color = e.features[0].properties.color_label;
                           const content='<div id="voters'+householdNo+'" style="min-height: 200px; width: 230px; font-size: 11px;"><div><strong>'+votersName+' Family</strong><br/><div class="voters">Loading..</div></div>';
                            //    console.log(e.features[0].properties);
                           
                            // Ensure that if the map is zoomed out such that multiple
                            // copies of the feature are visible, the popup appears
                            // over the copy being pointed to.
                           if (['mercator', 'equirectangular'].includes(map.getProjection().name)) {
                               while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                                   coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                                   }
                                   }
                                   new mapboxgl.Popup()
                                   .setLngLat(coordinates)
                                   .setHTML(content)
                                   .addTo(map);
                       
                                    // const criteria = $('#select-criteria').val();
                                    // const survey_name = $('#select-survey').val();
                                    const barangay = $('#select-barangay').val();
                                    let purok= $("#select-purok").val();
                                    // const date_survey = $('input[name="date_survey"]').val();
                                    // const color_survey = $("#checkbox-colors input:checkbox:checked").map(function(){
                                    //     return $(this).val();
                                    //     }).get();
                                   
                                   const voterslisUrl = app.baseUrl + 'specialsurvey/unregistered-voters-population?hs='+householdNo+'&barangay='+barangay+'&purok='+purok;
                                   $.ajax({
                                       url: voterslisUrl,
                                       method: 'get',
                                       dataType: 'html',
                                       success: (s) => {
                                             $("#voters"+householdNo).find(".voters").html(s);
                                           },
                                           error: (e) => {
                                               console.log('e', e)
                                           }
                                       


                                   });


                                //    const urlGraph = app.baseUrl + 'specialsurvey/registered-vs-unregistered-voters?list=1&barangay='+barangay+ '&purok='+purok;
                        
                                //     $.ajax({
                                //         url: urlGraph,
                                //         method: 'get',
                                //         dataType: 'html',
                                //         success: (s) => {
                                //             const data = JSON.parse(s.chartData);
                                //             const label = JSON.parse(s.barangayLabels);
                                //             renderChart(data, label);
                                           

                                //         },
                                //         error: (e) => {
                                //             console.log('e', e)
                                        
                                //         }
                                //     });

                                   const urllist = app.baseUrl + 'specialsurvey/registered-vs-unregistered-voters?&barangay='+barangay+ '&purok='+purok+'&household_no='+householdNo;
                        
                                    $.ajax({
                                        url: urllist,
                                        method: 'get',
                                        dataType: 'html',
                                        success: (s) => {
                                            //console.log(s);
                                            // $('#content-listing').html("");
                                           
                                            // console.log(s);
                                            
                                            $('#content-listing').html(s);   
                                           

                                        },
                                        error: (e) => {
                                            console.log('e', e)
                                        
                                        }
                                    });

                                   
                                   
                                   
                                   
                           
                       });
                       
                      map.on('mouseenter', 'population', () => {
                         populationClick=1;
                        map.getCanvas().style.cursor = 'pointer';
                      });
                      map.on('mouseleave', 'population', () => {
                          map.getCanvas().style.cursor = '';
                          populationClick=0;
                       });
                       
                       
                       
                       
                    const dataUrl = app.baseUrl + 'specialsurvey/barangay-coordinates1';
                    const changePaint = (url) => {
                        $.ajax({
                            url,
                            method: 'get',
                            dataType: 'json',
                            success: (s) => {
                                const layer = map.getLayer('barangay-coordinates');
                                if (layer) {
                                    map.setPaintProperty('barangay-coordinates', 'fill-color', s.output);
                                }
                                
                             

                                $('#barangay-new').html(''); //s.preview
                                
                                // console.log(s.purok);
                               const purok= $("#select-purok").val();
                                $("#select-purok").find('option').remove(); 
                                $("#select-purok").append('<option value="">Select..</option>');
                                $.each(s.purok,function(key, value) {
                                     if(purok==value.purok){
                                      $("#select-purok").append('<option value="'+value.purok+'" selected>' + value.purok + '</option>');
                                     }else{
                                       $("#select-purok").append('<option value="'+value.purok+'">' + value.purok + '</option>');  
                                     }
                                });
                                
                                
                                
                                KTApp.unblock('.loading-container');
                            },
                            error: (e) => {
                                console.log('e', e)
                                KTApp.unblock('.loading-container');
                            }
                        });
                        
                         const url2 = url;
                        $.ajax({
                            url: url2 + "&graph=1",
                            method: 'get',
                            dataType: 'html',
                            success: (s) => {
                               // console.log(s);
                                $('.graph-content').html(s);   
                            },
                            error: (e) => {
                                console.log('e', e)
                               
                            }
                        });
                        
                        
                    }
                    KTApp.block('.loading-container', {
                        overlayColor: '#000000',
                        message: 'Loading',
                        state: 'primary'
                    });
                    
                    
                    
                    changePaint(dataUrl+'?barangay={$searchModel->barangay}&survey_name={$searchModel->survey_name}');
                    
                      let curbarangay='';
                      $('#select-barangay, #select-purok').change(function() {
                        KTApp.block('.loading-container', {
                            overlayColor: '#000000',
                            message: 'Loading',
                            state: 'primary'
                        });

                        $('#barangay-new').html('Survey summary details will go here.');

                        // const criteria = $('#select-criteria').val();
                        // const survey_name = $('#select-survey').val();
                        const barangay = $('#select-barangay').val();
                        let purok= $("#select-purok").val();
                        // const date_survey = $('input[name="date_survey"]').val();
                        
                        // const color_survey = $("#checkbox-colors input:checkbox:checked").map(function(){
                        //     return $(this).val();
                        //     }).get();
                            
                         if(curbarangay!=barangay){
                            purok='';  
                         }   
                            
                            
                        curbarangay=barangay;
                        
                      
                        
                        //console.log(color_survey);
                        
                        
                         const datasourceUrl2 = app.baseUrl + 'specialsurvey/unregistered-voters-population?barangay='+barangay+'&purok='+purok;
                         map.getSource('voters').setData(datasourceUrl2);

                         changePaint(dataUrl + '?barangay='+barangay+'&purok='+purok);
                         

                         $.ajax({
                            url: app.baseUrl +'/specialsurvey/registered-vs-unregistered-voters?list=1',
                            method: 'get',
                            data: { barangay, purok: purok,  },
                            success: function (response) {
                                const data = JSON.parse(response.chartData);
                                const label = JSON.parse(response.barangayLabels);
                                renderChart(data, label);
                                // console.log(response);
                                
                                // $('#content-listing').html(response);  
                                

                                
                            },
                            error: function (e) {
                                console.log('AJAX error', e);
                            }
                        });
                         
                         
                        const urllist = app.baseUrl + 'specialsurvey/registered-vs-unregistered-voters?&barangay='+barangay+ '&purok='+purok;
                        
                        $.ajax({
                            url: urllist,
                            method: 'get',
                            dataType: 'html',
                            success: (s) => {
                                // console.log(s);
                                
                               $('#content-listing').html(s);   

                            //    const data = JSON.parse(s.chartData);
                            //     const label = JSON.parse(s.barangayLabels);
                            //     renderChart(data, label);
                            },
                            error: (e) => {
                                console.log('e', e)
                               
                            }
                            
                        });


                       
                            
                         
                         
                         
                         
                    });
                    
                    
                    
                    
                    
                     map.on('click', 'barangay-coordinates', (e) => {
                         
                         if(populationClick==1){
                           return; 
                         }
                         
                            const currentLngLat = e.lngLat;
                 
                            // Change the cursor style as a UI indicator.
                        map.getCanvas().style.cursor = 'pointer';

                        const property = e.features[0].properties;
                        
                        if(property['barangay']=='Poblacion 61'){
                            property['barangay']= 'Poblacion 61 (Barangay 2)';
                        }else if(property['barangay']=='Poblacion 1'){
                            property['barangay']= 'Poblacion I (Barangay 1)';
                        }
                       
                          KTApp.block('.loading-container', {
                                overlayColor: '#000000',
                                message: 'Loading',
                                state: 'primary'
                            });

                        // const criteria = $('#select-criteria').val();
                        // const survey_name = $('#select-survey').val();
                        const barangay = property['barangay'];
                        const purok= $("#select-purok").val();
                        // const date_survey = $('input[name="date_survey"]').val();
                        // const color_survey = $("#checkbox-colors input:checkbox:checked").map(function(){
                        //     return $(this).val();
                        //     }).get();
                        // console.log(barangay);
                        
                 
                            $('#select-barangay').val(barangay);

                    
                            
                           
                            
                            
                             const datasourceUrl2 = app.baseUrl + 'specialsurvey/unregistered-voters-population?barangay='+barangay+'&purok='+purok;
                             map.getSource('voters').setData(datasourceUrl2);
                            
                             changePaint(dataUrl + '?barangay='+barangay+'&purok='+purok);
                             
                            //render chart
                             $.ajax({
                                url: app.baseUrl +'/specialsurvey/registered-vs-unregistered-voters?list=1',
                                method: 'get',
                                data: { barangay, purok: purok,  },
                                success: function (response) {
                                    const data = JSON.parse(response.chartData);
                                    const label = JSON.parse(response.barangayLabels);
                                    renderChart(data, label);
                                    // console.log(response);
                                    
                                    // $('#content-listing').html(response);  
                                    

                                   
                                },
                                error: function (e) {
                                    console.log('AJAX error', e);
                                }
                            });
                             
                             const urllist = app.baseUrl + 'specialsurvey/registered-vs-unregistered-voters?barangay='+barangay+ '&purok='+purok;
                             
                            
                            //  render list of unregistered voters
                                $.ajax({
                                    url: urllist,
                                    method: 'get',
                                    dataType: 'html',
                                    success: (s) => {
                                        //console.log(s);
                                        $('#content-listing').html(s);   
                                
                                    },
                                    error: (e) => {
                                        console.log('e', e)
                                    
                                    }
                                });



                            
                           

                  

                            //popup.setLngLat(currentLngLat).setHTML(content).addTo(map);
                        });
                    
                    
                    
                    
                    
                       map.on('mouseenter', 'barangay-coordinates', (e) => {
                            // Change the cursor style as a UI indicator.
                            map.getCanvas().style.cursor = 'pointer';
                        });
                        
                        map.on('mouseleave', 'barangay-coordinates', () => {
                            map.getCanvas().style.cursor = '';
                        });

                JS,
                
                'initLoadScript' => <<< JS

                    $('input[type="range"]').change(function() {
                        const opacity = $(this).val();

                        $(this).closest('div').find('span').html('('+opacity+')');

                        obj.map.setPaintProperty('barangay-coordinates', 'fill-opacity', parseFloat(opacity/100));
                    });
                JS,
                
               // <div id="barangay-new" class="scroll scroll-pull loading-container" data-scroll="true" data-wheel-propagation="true" style="height: 400px;">Survey summary details will go here.</div>
                
                'customContent'=>$contentlegend.'<div class="graph-content">Pie</div>
                
                ',
               


                
       
    ]) ?>
    
    

</section>



<div class="specialsurvey-index-page" >
    
 <div class="mt-10"></div>
 

    <div id="registered-vs-unregistered-graph"></div>


 
    <div id="content-listing">
        <?= FilterColumn::widget(['searchModel' => $MemberSearch]) ?>
        <?= Html::beginForm(['bulk-action'], 'post'); ?>
            <?= Html::a('Import Members Data', ['import'], [
                'class' => 'btn btn-outline-primary ml-10 btn-sm btn-import-member',
            ]) ?>
            <?= BulkAction::widget(['searchModel' => $MemberSearch]) ?>
            
            <?= Grid::widget([
                'dataProvider' => $dataProvider,
                'searchModel' => $MemberSearch,
                'template' => ['view', 'update', 'duplicate', 'delete', 'download-qr-code'],
                'rowOptions' => function ($model, $index, $widget, $grid){
                        if($model->head==1){
                        return ['style' => 'background-color:#f1f1f1;']; 
                        }else{
                        return [];  
                        }
                    }
                
            ]); ?>
        <?= Html::endForm(); ?> 
      
    </div>
    
    
    
</div>


