<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\widgets\Anchor;

/**
 * This is the model class for table "{{%post_activity_reports}}".
 *
 * @property int $id
 * @property string $date
 * @property string $for
 * @property string $subject
 * @property string $title
 * @property string|null $location
 * @property string $date_of_activity
 * @property string $concerned_office
 * @property string|null $highlights_of_activity
 * @property string|null $description
 * @property string|null $photos
 * @property string $prepared_by
 * @property string $noted_by
 * @property string|null $token
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class PostActivityReport extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post_activity_reports}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'post-activity-report',
            'mainAttribute' => 'title',
            'paramName' => 'token',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['date', 'for', 'subject', 'title', 'date_of_activity', 'concerned_office', 'prepared_by', 'noted_by', 'prepared_by_position', 'noted_by_position'], 'required'],
            [['date', 'date_of_activity', 'photos', 'highlights_of_activity'], 'safe'],
            [['location', 'description',], 'string'],
            [['for', 'subject', 'title', 'concerned_office', 'prepared_by', 'noted_by', 'prepared_by_position', 'noted_by_position'], 'string', 'max' => 255],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'date' => 'Date',
            'for' => 'For',
            'subject' => 'Subject',
            'title' => 'Title',
            'location' => 'Location',
            'date_of_activity' => 'Date of Activity',
            'concerned_office' => 'Concerned Office',
            'highlights_of_activity' => 'Highlights of Activity',
            'description' => 'Description',
            'photos' => 'Photos',
            'prepared_by' => 'Prepared By',
            'noted_by' => 'Noted By',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\PostActivityReportQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\PostActivityReportQuery(get_called_class());
    }

    public function getDefaultGridColumns()
    {
        return [
            'serial',
            'checkbox',
            'date',
            'subject',
            'title',
            'date_of_activity',
            'prepared_by',
            'created_at',
        ];
    }
     
    public function gridColumns()
    {
        return [
            'date' => [
                'attribute' => 'date', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->date,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'for' => ['attribute' => 'for', 'format' => 'raw'],
            'subject' => ['attribute' => 'subject', 'format' => 'raw'],
            'title' => ['attribute' => 'title', 'format' => 'raw'],
            'location' => ['attribute' => 'location', 'format' => 'raw'],
            'date_of_activity' => ['attribute' => 'date_of_activity', 'format' => 'raw'],
            'concerned_office' => ['attribute' => 'concerned_office', 'format' => 'raw'],
            // 'highlights_of_activity' => ['attribute' => 'highlights_of_activity', 'format' => 'raw'],
            // 'description' => ['attribute' => 'description', 'format' => 'raw'],
            // 'photos' => ['attribute' => 'photos', 'format' => 'raw'],
            'prepared_by' => ['attribute' => 'prepared_by', 'format' => 'raw'],
            'noted_by' => ['attribute' => 'noted_by', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'date:raw',
            'for:raw',
            'subject:raw',
            'title:raw',
            'location:raw',
            'date_of_activity:raw',
            'concerned_office:raw',
            'highlights_of_activity:jsonEditor',
            'description:raw',
            'images:raw',
            'prepared_by:raw',
            'noted_by:raw',
        ];
    }

    public function getImages()
    {
        if (($files = $this->imageFiles) != null) {
            return App::foreach($files, 
                fn ($file) => Html::a(Html::image($file, ['w' => 100, 'h' => 100, 'ratio' => 'false'], [
                    'class' => 'img-thumbnail'
                ]), $file->viewerUrl, [
                    'target' => '_blank'
                ])
            );
        }
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['JsonBehavior']['fields'] = [
            'highlights_of_activity', 
            'photos',
        ];

        $behaviors['DateBehavior'] = [
            'class' => 'app\behaviors\DateBehavior',
            'attributes' => [
                'date',
                'date_of_activity'
            ]
        ];
        
        return $behaviors;
    }

    public function getImageFiles()
    {
        if (($photos = $this->photos) != null) {
            return File::findAll(['token' => $photos]);
        }
    }

    public function getPrintableUrl($fullpath=true)
    {
        if ($this->checkLinkAccess('printable')) {
            $paramName = $this->paramName();
            $url = [
                implode('/', [$this->controllerID(), 'printable']),
                $paramName => $this->{$paramName}
            ];
            return ($fullpath)? Url::to($url, true): $url;
        }
    }
}