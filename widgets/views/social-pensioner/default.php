<?php

use app\widgets\Detail;
?>

<h3>PRIMARY INFORMATION</h3>
<?= Detail::widget([
	'model' => $model,
	'attributes' => [
		// 'statusBadge:raw',
        'profilePhoto:raw',
        'qr_id:raw',
        'last_name:raw',
        'middle_name:raw',
        'first_name:raw',
        'name_suffix:raw',
        'genderName:raw',
        'age:raw',
        'birth_date:raw',
        'birth_place:raw',
        'civilStatusName:raw',
        'email:raw',
        'contact_no:raw',
	]
]); ?>

<div class="my-10"></div>
<h3>ADDRESS</h3>
<?= Detail::widget([
	'model' => $model,
	'attributes' => [
		'house_no:raw',
        'street:raw',
        'barangay:raw',
        'sitio:raw',
        'purok:raw',
	]
]); ?>


<div class="my-10"></div>
<h3>OTHERS</h3>
<?= Detail::widget([
	'model' => $model,
	'attributes' => [
		'educationalAttainmentLabel:raw',
		'occupation:raw',
		'income:raw',
		'source_of_income:raw',
		'date_registered:raw',
		'documentViews:raw',
	]
]); ?>

<div class="my-10"></div>
<h3>PRIORITY SCORE</h3>
<?= Detail::widget([
	'model' => $model,
	'attributes' => [
		'pwd_score:number',
		'senior_score:number',
		'solo_parent_score:number',
		'solo_member_score:number',
		'accessibility_score:number',
		'priorityScore:number',
	]
]); ?>

<div class="my-10"></div>
<h3>SYSTEM</h3>
<?= Detail::widget([
	'model' => $model,
	'attributes' => [
	    'created_at' => [
	        'attribute' => 'created_at',
	        'format' => 'fulldate'
	    ],
	    'updated_at' => [
	        'attribute' => 'updated_at',
	        'format' => 'fulldate'
	    ],
	    'createdByName' => [
	        'label' => 'Created By',
	        'attribute' => 'createdByName',
	        'format' => 'raw'
	    ],
	    'updatedByName' => [
	        'label' => 'Updated By',
	        'attribute' => 'updatedByName',
	        'format' => 'raw'
	    ],
	]
]); ?>