<?php

use app\helpers\App;

$this->title = $model->nameWithExtension;

$this->registerCssFile(App::publishedUrl('/handsontable/handsontable.full.min.css', Yii::getAlias('@app/assets')));
$this->registerJsFile(App::publishedUrl('/handsontable/handsontable.full.min.js', Yii::getAlias('@app/assets')));

$this->registerJs(<<< JS
	let spreadsheetData = {};

	KTApp.block('body', {
        overlayColor: '#000000',
        message: 'Please wait...',
        state: 'primary'
    });

    let initTable = function(el, data) {
    	return Handsontable(document.getElementById(el), {
			data: data,
			rowHeaders: true,
			colHeaders: true,
			columnSorting: false,
			width: '100%',
			manualRowMove: false,
			customBorders: false,
			dropdownMenu: true,
			multiColumnSorting: true,
			filters: true,
			licenseKey: 'non-commercial-and-evaluation',
	    });
    }

    $.ajax({
    	url: '{$model->viewerUrl}',
    	dataType: 'json',
    	method: 'get',
    	// async: false,
    	success: function(s) {
    		KTApp.unblock('body');
    		if(s.data) {
    			spreadsheetData = s.data;
    			d = s.data;

    			if (d.length > 1) {
    				let ul = '<ul class="nav nav-tabs nav-tabs-line">';
    				let tab = '<div class="tab-content">';
	    			for (var i = 0; i <= d.length - 1; i++) {
	    				ul += '<li class="nav-item" data-key="'+ i +'">';
	    				if (i == 0) {
	    					ul += '<a class="nav-link active" data-toggle="tab" href="#tab-'+ i +'">'+ d[i]['title'] +'</a>';
	    					tab += '<div class="tab-pane fade show active" id="tab-'+ i +'" role="tabpanel" aria-labelledby="tab-'+ i +'"><div id="handsontable-container-'+ i +'"></div></div>'
	    				}
	    				else {
	    					ul += '<a class="nav-link" data-toggle="tab" href="#tab-'+ i +'">'+ d[i]['title'] +'</a>';
	    					tab += '<div class="tab-pane fade" id="tab-'+ i +'" role="tabpanel" aria-labelledby="tab-'+ i +'"><div id="handsontable-container-'+ i +'"></div></div>'
	    				}
	    				ul += '</li>';
	    			}
	    			tab += '</tab>';
	    			ul += '</ul>';

	    			$('#spreadsheet-container').html(ul + tab);

	    			for (var i = 0; i <= d.length - 1; i++) {
	    				spreadsheetData[i] = initTable('handsontable-container-' + i, d[i]['row']);
	    			}
    			}
    			else {
    				spreadsheetData[i] = initTable('spreadsheet-container', d[0]['row']);
    			}
    		}
    	}
	});

	$(document).on('click', '.nav-item', function() {
		KTApp.block('body', {
	        overlayColor: '#000000',
	        message: 'Please wait...',
	        state: 'primary'
	    });
		setTimeout(function() {
			for (var i = 0; i <= spreadsheetData.length - 1; i++) {
				spreadsheetData[i].render();
			}
    		KTApp.unblock('body');
		}, 5)
	});
JS);
?>
 
<div id="spreadsheet-container"></div>

