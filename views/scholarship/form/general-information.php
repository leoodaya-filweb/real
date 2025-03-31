<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Barangay;
use app\models\HazardMap;
use app\models\Scholarship;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;
use app\widgets\DatePicker;

$this->registerJs(<<< JS
    const computeAge = (birth_date) => {
        var dob = new Date(birth_date);  
        //calculate month difference from current date in time  
        var month_diff = Date.now() - dob.getTime();  
          
        //convert the calculated difference in date format  
        var age_dt = new Date(month_diff);   
          
        //extract year from date      
        var year = age_dt.getUTCFullYear();  
          
        //now calculate the age of the user  
        var age = Math.abs(year - 1970);  

        return age;
    }

    $('#scholarship-birth_date').change(function() {
        $('#scholarship-age').val(computeAge($(this).val()));
    });
    
    $(document).on("change", ".date-form", function(){
   valid = parseDate($(this).val()); //pattern.test($(this).val());
   // console.log(valid);

    if(!valid){
      $(this).val("")
     }
   });

    
  function parseDate(str) {
    function pad(x){return (((''+x).length==2) ? '' : '0') + x; }
    var m = str.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/)
    , d = (m) ? new Date(m[3], m[2]-1, m[1]) : null
    , matchesPadded = (d&&(str==[pad(d.getDate()),pad(d.getMonth()+1),d.getFullYear()].join('/')))
    , matchesNonPadded = (d&&(str==[d.getDate(),d.getMonth()+1,d.getFullYear()].join('/')));
   return (matchesPadded || matchesNonPadded) ? d : null;
  }
  
  
  
  Inputmask().mask("scholarship-birth_date");
    
    
JS);
?>

<h4 class="mb-10 font-weight-bold text-dark">
    <?= $tabData['title'] ?>
</h4>

<?php $form = ActiveForm::begin() ?>
    <p class="lead font-weight-bold text-uppercase">Primary Details</p>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'name_suffix')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?php /* DatePicker::widget([
                'form' => $form,
                'model' => $model,
                'attribute' => 'birth_date'
            ]) */?>
            
            
             <?= $form->field($model, 'birth_date', [
                                            'template' => "
                                                {label} <span class='text-muted'>(mm/dd/yyyy)</span>
                                                {input}
                                                {error}
                                            "    
                                        ])->textInput([
                                            //'datepicker' => 'true',
                                            'class'=>'form-control date-form ',
                                            'data-inputmask'=>"'alias': 'datetime', 'inputFormat': 'mm/dd/yyyy'",
                                            'autocomplete' => 'off'
                                        ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'age')->textInput([
                'type' => 'number'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= BootstrapSelect::widget([
                'form' => $form,
                'model' => $model,
                'attribute' => 'sex',
                'data' => [
                    Scholarship::MALE => 'Male',
                    Scholarship::FEMALE => 'Female',
                ]
            ]) ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model, 'guardian')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <p class="lead font-weight-bold text-uppercase mt-10">Address</p>
    <div class="row">
        <div class="col-md-4">
            <?php /* BootstrapSelect::widget([
                'form' => $form,
                'model' => $model,
                'attribute' => 'barangay_id',
                'data' => HazardMap::dropdown('id', 'name', ['type' => HazardMap::BARANGAY])
            ]) */ ?>
            
            <?= BootstrapSelect::widget([
	    			'form' => $form,
	    			'model' => $model,
	    		//	'prompt' => false,
	    			'attribute' => 'barangay_id',
	    			'data' => Barangay::dropdown('no', 'name', [
						'municipality_id' => App::setting('address')->municipality_id
					])
	    		]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'house_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'street_address')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <p class="lead font-weight-bold text-uppercase mt-10">Contact Details</p>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'alternate_email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'contact_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'alternate_contact_no')->textInput(['maxlength' => true]) ?>
        </div>
    </div>


    <div class="form-group mt-10">
        <?= Html::submitButton('Next', [
            'class' => 'btn btn-success btn-lg font-weight-bold'
        ]) ?>
    </div>
<?php ActiveForm::end(); ?>

