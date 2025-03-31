  <?php
  use yii\grid\GridView;

  /* @var $searchModel app\models\SpecialsurveySearch */
  /* @var $dataProvider yii\data\ActiveDataProvider */

  echo GridView::widget([
      'dataProvider' => $dataProvider,
      'filterModel' => $searchModel,
      'columns' => [
          'id',
          'name',
          'barangay',
          'purok',
          'criteria1_color_id',
          [
            'attribute' => 'converted_voter',
            'label' => 'Converted Voter',
            'value' => function ($model) {
                return $model->converted_voter ? 'Yes' : 'No';
            },
          ],
        
          ['class' => 'yii\grid\ActionColumn'],
      ],
  ]);
  ?>
