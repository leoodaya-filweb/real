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
/* @var $searchModel app\models\search\SpecialsurveySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

 $searchModel->searchTemplate = 'specialsurvey/_search_voters';
 $searchModel->searchAction = ['specialsurvey/voter-analysis'];


$this->title = 'Voter Support & Behavior Analysis';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['searchForm'] = 'testvv';
$this->params['showCreateButton'] = false;//true; 
$this->params['showExportButton'] = false; //true;
$this->params['activeMenuLink'] = '/specialsurvey/voter-distribution';
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
        
        <!-- not displaying -->
        <div class="ml-5 text-center color-voters d-none" >
        <div id="checkbox-colors" class="checkbox-list"> 
            <label class="checkbox"><input type="checkbox"  value="1" name="color[]">  <span class="color-box" style="background: #5096f2;"></span>  Blue</label>
            <label class="checkbox"> <input type="checkbox" checked value="2" name="color[]">  <span class="color-box" style="background: #e4e6ef;"></span>  Gray</label>
            <label class="checkbox"> <input type="checkbox"  value="3" name="color[]">  <span class="color-box" style="background: #000000;"></span>  Blackx</label>
            <label class="checkbox"> <input type="checkbox"  value="4" name="color[]">  <span class="color-box" style="background: #404040;"></span>  Blacky</label>
            <label class="checkbox"> <input type="checkbox"  value="5" name="color[]">  <span class="color-box" style="background: #808080;"></span>  Blacku</label>
        </div>
        </div>
        
        <div class="ml-5">
           <select class="form-control" id="select-barangay">
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
        <select class="form-control" id="select-purok">
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
        
        <div class="ml-5">
            <select class="form-control" id="select-survey">
                <?=  Html::tag('option', 'Select Survey', [
                    'value' => '',
                    'selected' => true,
                   // 'disabled' => true
                ]) ?>
                <?= Html::foreach(Specialsurvey::filter('survey_name'), function($name) {
                 
                    return Html::tag('option', $name, [
                        'value' => $name,
                        'selected' => false
                    ]);
                }) ?>
            </select>
        </div>
        <div class="ml-5">
            <select class="form-control" id="select-criteria">
                <?= Html::foreach([1, 2, 3, 4, 5], function($n) {
                    return Html::tag('option', "Criteria {$n}", [
                        'value' => $n,
                        'selected' => App::get("criteria{$n}_color_id") ? true: false
                    ]);
                }) ?>
            </select>
        </div>
        <div class="ml-5">
            <?= DateRange::widget([
                'withTitle' => false,
                'attribute' => 'date_survey',
                'model' => new Specialsurvey(),
                'onchange' => <<< JS
                    const date_survey = start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD');
                    $('input[name="date_survey"]').trigger('change');
                JS
            ]) ?>
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
                   const datasourcebgryUrl = app.baseUrl + 'specialsurvey/population-coordinates?&brgy=1';
                 
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
                 
                   const datasourceUrl = app.baseUrl + 'specialsurvey/population-coordinates?barangay={$searchModel->barangay}&purok={$searchModel->purok}&color_survey=2&criteria=2&survey_name={$searchModel->survey_name}&keywords={$searchModel->keywords}';
              
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
                            'circle-color': [
                                'case',
                                ['==', ['get', 'criteria1_color_id'], 2], '#a9a9a9',  // Dark Grey for ID 2
                                'rgba(0,0,0,0)'  // Fully Transparent for Others
                            ]
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
                        //    const content='<div id="voters'+householdNo+'" style="min-height: 200px; width: 230px; font-size: 11px;"><div><strong>'+votersName+' Family</strong><br/>HS No.: '+householdNo+'</div><br/>Total Voters: '+total_voters+'<br/>Color: '+color+'<div class="voters">Loading..</div></div>';
                            const content='<div id="voters'+householdNo+'" style="min-height: 200px; width: 230px; font-size: 11px;"><div><strong>'+votersName+' Family</strong><br/><div class="voters">Loading..</div></div>';

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
                       
                                    const criteria = $('#select-criteria').val();
                                    const survey_name = $('#select-survey').val();
                                    const barangay = $('#select-barangay').val();
                                    let purok= $("#select-purok").val();
                                    const date_survey = $('input[name="date_survey"]').val();
                                    const color_survey = $("#checkbox-colors input:checkbox:checked").map(function(){
                                        return $(this).val();
                                        }).get();
                                       
                                   
                                   const voterslisUrl = app.baseUrl + 'specialsurvey/population-coordinates?hs='+householdNo+'&barangay='+barangay+'&criteria='+criteria+'&survey_name='+ (survey_name || '')+'&color_survey='+color_survey+'&purok='+purok;
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

                                   const urllist = app.baseUrl + 'specialsurvey/voter-distribution?barangay='+barangay+'&criteria='+criteria+'&survey_name='+ (survey_name || '')+'&color_survey='+color_survey+'&purok='+purok+'&household_no='+householdNo;
                        
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
                                   
                                   
                                   
                                   
                           
                       });
                       
                      map.on('mouseenter', 'population', () => {
                         populationClick=1;
                        map.getCanvas().style.cursor = 'pointer';
                      });
                      map.on('mouseleave', 'population', () => {
                          map.getCanvas().style.cursor = '';
                          populationClick=0;
                       });
                       
                       
                       
                       
                    const dataUrl = app.baseUrl + 'specialsurvey/gray-barangay-coordinates';
                    const changePaint = (url) => {
                        $.ajax({
                            url,
                            method: 'get',
                            dataType: 'json',
                            success: (s) => {
                                const layer = map.getLayer('barangay--coordinates');
                                if (layer) {
                                    map.setPaintProperty('barangay--coordinates', 'fill-color', s.output);
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
                            url: url2 + "&graph=1&grey=1",
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
                        
                        
                        
                         const url3 = url;
                        $.ajax({
                            url: url3 + "&bgygraph=1",
                            method: 'get',
                            dataType: 'html',
                            success: (s) => {
                               // console.log(s);
                                $('#content-graph').html(s);   
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
                      $('#select-criteria, #select-survey, #select-barangay, #select-purok,  input[name="date_survey"], input[name="color[]"] ').change(function() {
                        KTApp.block('.loading-container', {
                            overlayColor: '#000000',
                            message: 'Loading',
                            state: 'primary'
                        });

                        $('#barangay-new').html('Survey summary details will go here.');

                        const criteria = $('#select-criteria').val();
                        const survey_name = $('#select-survey').val();
                        const barangay = $('#select-barangay').val();
                        let purok= $("#select-purok").val();
                        const date_survey = $('input[name="date_survey"]').val();
                        
                        const color_survey = $("#checkbox-colors input:checkbox:checked").map(function(){
                                        return $(this).val();
                                        }).get();
                         if(curbarangay!=barangay){
                            purok='';  
                         }   
                            
                            
                        curbarangay=barangay;
                        
                      
                        
                        //console.log(color_survey);
                        
                        
                         const datasourceUrl2 = app.baseUrl + 'specialsurvey/population-coordinates?barangay='+barangay+'&criteria='+criteria+'&survey_name='+ (survey_name || '')+'&color_survey='+color_survey+'&purok='+purok;
                         map.getSource('voters').setData(datasourceUrl2);

                         changePaint(dataUrl + '?barangay='+barangay+'&criteria=' + criteria + '&survey_name=' + (survey_name || '')+ '&date_range=' + date_survey+'&color_survey='+color_survey+'&purok='+purok);
                         
                         
                         
                        const urllist = app.baseUrl + 'specialsurvey/voter-distribution?barangay='+barangay+'&criteria='+criteria+'&survey_name='+ (survey_name || '')+'&color_survey='+color_survey+'&purok='+purok;
                        
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

                        const criteria = $('#select-criteria').val();
                        const survey_name = $('#select-survey').val();
                        const barangay = property['barangay'];
                        const purok= $("#select-purok").val();
                        const date_survey = $('input[name="date_survey"]').val();
                        const color_survey = ["2"]; // Always include Gray voters

                        
                 
                         $('#select-barangay').val(barangay);

                    
                            
                             const datasourceUrl2 = app.baseUrl + 'specialsurvey/population-coordinates?barangay='+barangay+'&criteria='+criteria+'&survey_name=' + (survey_name || '')+'&color_survey='+color_survey+'&purok='+purok;
                             map.getSource('voters').setData(datasourceUrl2);
                              
                              
                            
                             changePaint(dataUrl + '?barangay='+barangay+'&criteria=' + criteria + '&survey_name=' + (survey_name || '')+ '&date_range=' + date_survey+'&color_survey='+color_survey+'&purok='+purok);
                             
                             
                             const urllist = app.baseUrl + 'specialsurvey/voter-distribution?barangay='+barangay+'&criteria='+criteria+'&survey_name='+ (survey_name || '')+'&color_survey='+color_survey+'&purok='+purok;
                        
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
 

    <div id="content-graph">
        <?= $this->render('voter_gray_barangay_graph',[]) ?>
    </div>
      
 
    <div id="content-listing">
    <?= Html::beginForm(['bulk-action'], 'post'); ?>
        <?= BulkAction::widget(['searchModel' => $searchModel]) ?>
        <?= Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]); ?>
    <?= Html::endForm(); ?> 
      
                    
    </div>
    
    
    
</div>


