<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\widgets\Anchor;
use app\widgets\JsonEditor;
use app\helpers\Url;

/**
 * This is the model class for table "{{%themes}}".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string|null $base_path
 * @property string|null $base_url
 * @property string|null $path_map
 * @property string|null $bundles
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Theme extends ActiveRecord
{
    const KEEN = [
        'demo1-main',
        'demo1-main-fluid',
        'light',
        'light-fluid',
        'dark',
        'dark-fluid',
        'no-aside-light',
        'no-aside-light-fluid',
        'no-aside-dark',
        'no-aside-dark-fluid',
        'demo2-fixed',
        'demo2-fluid',
        'demo3-fixed',
        'demo3-fluid',
    ];

    const MAIN = 1;
    const MAIN_FLUID = 2;
    const LIGHT = 3;
    const LIGHT_FLUID = 4;
    const DARK = 5;
    const DARK_FLUID = 6;
    const NO_ASIDE_LIGHT = 7;
    const NO_ASIDE_LIGHT_FLUID = 8;
    const NO_ASIDE_DARK = 9;
    const NO_ASIDE_DARK_FLUID = 10;
    const DEMO2_FIXED = 11;
    const DEMO2_FLUID = 12;
    const DEMO3_FIXED = 13;
    const DEMO3_FLUID = 14;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%themes}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'theme',
            'mainAttribute' => 'name',
            'paramName' => 'slug',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['name', 'description', 'base_path', 'base_url'], 'required'],
            [['base_path', 'base_url'], 'string'],
            [['bundles', 'path_map', 'photos'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'base_path' => 'Base Path',
            'base_url' => 'Base Url',
            'path_map' => 'Path Map',
            'bundles' => 'Bundles',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\ThemeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ThemeQuery(get_called_class());
    }

    public function getCanDelete()
    {
        return false;
    }

    public function getActivateUrl()
    {
        return Url::to(['theme/activate', 'slug' => $this->slug], true);
    }
     
    public function gridColumns()
    {
        return [
            'preview' => [
                'attribute' => 'name', 
                'label' => 'preview', 
                'format' => 'raw',
                'value' => 'previewImage'
            ],
            'name' => [
                'attribute' => 'name', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->name,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
            'description' => ['attribute' => 'description', 'format' => 'raw'],
            // 'base_path' => ['attribute' => 'base_path', 'format' => 'raw'],
            // 'base_url' => ['attribute' => 'base_url', 'format' => 'raw'],
            // 'path_map' => ['attribute' => 'path_map', 'format' => 'raw'],
            // 'bundles' => ['attribute' => 'bundles', 'format' => 'raw'],
        ];
    }
    
    public function getpath_mapdata()
    {
        return JsonEditor::widget([
            'data' => $this->path_map,
        ]);
    }

    public function getBundlesdata()
    {
        return JsonEditor::widget([
            'data' => $this->bundles,
        ]);
    }

    public function detailColumns()
    {
        return [
            'name:raw',
            'description:raw',
            'base_path:raw',
            'base_url:raw',
            'path_mapData:raw',
            'bundlesData:raw',
            'images:raw',
        ];
    }

    public function getImageFiles()
    {
        if (($photos = $this->photos) != null) {
            $files = [];

            foreach ($photos as $token) {
                if (($file = File::findByToken($token)) != null) {
                    $files[] = $file;
                }
            }

            return $files;
        }
    }

    public function getImages()
    {
        if (($photos = $this->photos) != null) {
            $images = [];

            foreach ($photos as $photo) {
                $images[] = Html::image($photo, ['w' => 100, 'h' => 100, 'ratio' => 'false'], [
                    'class' => 'img-thumbnail'
                ]);
            }

            return implode(' ', $images);
        }
    }

    public function getPreviewImage($params = ['w' => 100], $options = [])
    {
        $token = '';
        if (($photos = $this->photos) != null) {
            $token = $photos[0];
        }

        return Html::image($token, $params, $options);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['JsonBehavior']['fields'] = [
            'path_map', 
            'bundles',
            'photos',
        ];

        $behaviors['SluggableBehavior'] = [
            'class' => 'yii\behaviors\SluggableBehavior',
            'attribute' => 'name',
            'ensureUnique' => true,
        ];
        return $behaviors;
    }
}