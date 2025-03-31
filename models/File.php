<?php

namespace app\models;

use Imagine\Gd;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\widgets\Anchor;
use app\helpers\Url;
use yii\imagine\Image;
use yii\helpers\StringHelper;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%files}}".
 *
 * @property int $id
 * @property string $name
 * @property string $extension
 * @property int $size
 * @property string|null $location
 * @property string $token
 * @property int $record_status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class File extends ActiveRecord
{
    const EXTENSIONS = [
        'image' => ['jpeg', 'jpg', 'gif', 'bmp', 'tiff','png', 'ico'],
        'file' => ['doc', 'docx', 'pdf', 'xls', 'xlsx', 'csv', 'sql', 'jpeg', 'jpg', 'gif', 'bmp', 'tiff','png', 'ico'],
    ];
    const IMAGE_HOLDER = 'https://via.placeholder.com/100';

    public $unlink = true;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%files}}';
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['truncatedName'] = 'truncatedName';
        $fields['display'] = 'display';
        $fields['downloadUrl'] = 'downloadUrl';

        return $fields;
    }


    public function config()
    {
        return [
            'controllerID' => 'file',
            'mainAttribute' => 'name',
            'paramName' => 'token',
            'excelIgnoreAttributes' => ['icon']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['size',], 'integer'],
            [['name', 'extension', 'size'], 'required'],
            [['location'], 'string'],
            [['name', 'tag'], 'string', 'max' => 255],
            [['tag'], 'safe'],
            [['extension'], 'string', 'max' => 16],
            ['extension', 'in', 'range' => array_merge(
                self::EXTENSIONS['image'],
                self::EXTENSIONS['file'],
            )],
        ]);
    }

    public function getNameWithExtension()
    {
        return implode('.', [
            $this->name,
            $this->extension,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'model_id' => 'Model ID',
            'model' => 'Model',
            'name' => 'Name',
            'tag' => 'Tag',
            'extension' => 'Extension',
            'size' => 'Size',
            'location' => 'Location',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\FileQuery(get_called_class());
    } 

    public function getImageFiles()
    {
        return Files::find()
            ->where(['extension' => self::EXTENSIONS['image']])
            ->all();
    }

    public function getDisplayPath($w='', $h='')
    {
        $w = $w ?: $this->width;
        $h = $h ?: $this->height;

        return Url::image($this, ['w' => $w, 'h' => $h]);
        // return App::baseUrl($this->location);
    }

    public function getDisplayRootPath()
    {
        $doc_path = FileHelper::normalizePath((App::isWeb()? Yii::getAlias('@webroot'): Yii::getAlias('@consoleWebroot')) 
        . '/default/file-preview/');

        return FileHelper::normalizePath(($this->isImage)? $this->rootPath: $this->getDisplay([], false, $doc_path));
    }

    public function getDisplay($params = [], $fullpath=false, $path='@web/default/file-preview/')
    {
        $path = strcmp($path, DIRECTORY_SEPARATOR) === 0 ? $path: $path . DIRECTORY_SEPARATOR;

        switch ($this->extension) {
            case 'css': $path .= 'css.png'; break;
            case 'zip': $path .= 'zip.png'; break;
            case 'sql': $path .= 'sql.png'; break;
            case 'csv': $path .= 'csv.png'; break;
            case 'xlsx': $path .= 'xlsx.png'; break;
            case 'xls': $path .= 'xls.png'; break;
            case 'docx':
            case 'doc':
            case 'txt': 
                $path .= 'doc.png'; break;
            case 'html': $path .= 'html.png'; break;
            case 'js': $path .= 'js.png'; break;
            case 'mp4': $path .= 'mp4.png'; break;
            case 'pdf': $path .= 'pdf.png'; break;
            case 'xml': $path .= 'xml.png'; break;
            default:
                // return Url::image($this, $params);
                break;
        }

        return $path;
    }

    public function show($params=[], $w=150)
    {
        if ($this->isDocument) {
            $params['style'] = $params['style'] ?? "width:{$w}px;height:auto";

            return Html::img(Url::image($this, ['w' => $w]), $params);
            // return Html::img($this->display, $params);
        }
        else {
            return Html::img(Url::image($this, ['w' => $w]), $params);
            // return Html::img(['file/display', 'token' => $this->token, 'w' => $w,], $params);
        }
    }

    public function getUrlImage($params=[])
    {
        return Url::image($this, $params);
    }

    public function getTruncatedName($char=25)
    {
        return StringHelper::truncate($this->name, $char);
    }

    public function getPreviewImage()
    {
        return Html::image($this, [
            'w' => 50, 
            'h' => 50,
            'ratio' => 'false',
            'quality' => 90
        ], [
            'class' => 'img-thumbnail',
            'loading' => 'lazy'
        ]);
    }

    public function gridColumns()
    {
        return [
            'icon' => [
                'attribute' => 'name', 
                'label' => 'Preview', 
                'format' => 'raw',
                'value' => 'previewImage',
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
            
            'extension' => ['attribute' => 'extension', 'format' => 'raw'],
            'size' => ['attribute' => 'size', 'format' => 'fileSize'],
            'tag' => ['attribute' => 'tag', 'format' => 'raw'],
            'location' => ['attribute' => 'location', 'format' => 'raw'],
            // 'token' => ['attribute' => 'token', 'format' => 'raw'],
        ];
    }

    public function detailColumns()
    {
        return [
            'previewImage:raw',
            'name:raw',
            'tag:raw',
            'extension:raw',
            'size:raw',
            'location:raw',
            'token:raw',
        ];
    }

    public function getFileSize()
    {
        return App::formatter('asFileSize', $this->size);
    }

    public function getRootPath()
    {
        $paths = [
            (App::isWeb()? Yii::getAlias('@webroot'): Yii::getAlias('@consoleWebroot')),
            $this->location
        ];

        return FileHelper::normalizePath(implode(DIRECTORY_SEPARATOR, $paths));
    }

    public function getExists()
    {
        if ($this->rootPath) {
            return file_exists($this->rootPath);
        }
    }

    public function getIsDocument()
    {
        return in_array($this->extension, self::EXTENSIONS['file']);
    }

    public function getIsImage()
    {
        return in_array($this->extension, self::EXTENSIONS['image']);
    }

    public function getWidth()
    {
        return $this->dimension['width'];
    }

    public function getHeight()
    {
        return $this->dimension['height'];
    }

    public function getDimension()
    {
        $width = 0;
        $height = 0;

        if ($this->exists) {
            list($width, $height) = getimagesize($this->displayRootPath);
        }
        return [
            'width' => ($this->isImage)? $width: 0,
            'height' => ($this->isImage)? $height: 0,
        ];
    }

    public function getImageRatio($w, $quality=100, $extension='png', $rotate=0)
    {
        if ($this->exists) {
            $imagineObj = new Imagine();
            $image = $imagineObj->open($this->displayRootPath);
            $image->resize($image->getSize()->widen($w));
            
            if ($rotate != 0) {
                $image->rotate($rotate);
            }
            return $image->show($extension, ['quality' => $quality]); 
        }
    }

    public function getImageCrop($w, $h, $quality=100, $extension='png', $rotate=0)
    {
        if ($this->exists) {
            $image = Image::crop($this->displayRootPath, $w, $h); 
            if ($rotate != 0) {
                $image->rotate($rotate);
            }

            return $image->show($extension, ['quality' => $quality]); 
        }
    }

    public function getImage($w, $h, $quality=100, $extension='png', $rotate=0)
    {
        if ($this->exists) {
            $image = Image::getImagine() 
                ->open($this->displayRootPath)
                ->resize(new Box($w, $h));

            if ($rotate != 0) {
                $image->rotate($rotate);
            }

            return $image->show($extension, ['quality' => $quality]);
        }
    }
    
    public function getIsSql()
    {
        return in_array($this->extension, ['sql']);
    }

    public function getCanDelete()
    {
        if ($this->extension == 'sql') {
            return false;
        }

        return parent::getCanDelete();
    }

    public function getDownloadUrl($scheme = false)
    {
        return Url::to(['file/download', 'token' => $this->token], $scheme);
    }

    public function download()
    {
        if ($this->exists) {
            App::response()->sendFile($this->rootPath, implode('.', [$this->name, $this->extension]));

            return true;
        }
        return false;
    }

    public static function findByToken($token)
    {
        return self::find()
            ->where(['token' => $token])
            ->one();
    }

    public static function findByKeywordsImage($keywords='', $attributes, $limit=3)
    {
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit) {
            return self::find()
                ->select("{$attribute} AS data")
                ->groupBy('data')
                ->where(['LIKE', $attribute, $keywords])
                ->andWhere(['extension' => self::EXTENSIONS['image']])
                ->andWhere(['created_by' => App::identity('id')]) // current user file
                ->limit($limit)
                ->asArray()
                ->all();
        });
    }

    public function getMimeType()
    {
        if ($this->isDocument) {
            return implode('/', ['file', $this->extension]);
        }

        return implode('/', ['image', $this->extension]);
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if ($this->unlink) {
            if (file_exists($this->rootPath)) {
                unlink($this->rootPath);
            }
        }
    }

    public static function totalSize()
    {
        $model = self::find()
            ->select(['SUM(size) as total'])
            ->asArray()
            ->one();

        return App::formatter('asFileSize', $model['total'] ?? 0);
    }

    public function getCreatedAt()
    {
        return App::formatter('asFulldate', $this->created_at);
    }

    public function getUpperCaseName()
    {
        return strtoupper($this->name);
    }

    public function getUpperCaseExtension()
    {
        return strtoupper($this->extension);
    }

    public function getViewerUrl($fullpath=true)
    {
        $paramName = $this->paramName();
        $url = [
            implode('/', [$this->controllerID(), 'viewer']),
            $paramName => $this->{$paramName}
        ];
        return ($fullpath)? Url::to($url, true): $url;
    }

    public static function tagFilterBtn($activeTag='', $type='all')
    {
        $activeTag = $activeTag ?: 'Filter Tag';

        if (($tags = self::filter('tag', ['extension' => ($type == 'all' ? '': self::EXTENSIONS['image'])])) != null) {

            $action = $type == 'all' ? 'my-files': 'my-image-files';
            $list = Html::foreach ($tags, function($tag) use($action, $activeTag) {
                return Html::a($tag, Url::to([$action, 'tag' => $tag]), [
                    'class' => 'dropdown-item ' . (($activeTag == $tag)? 'dropdown-item-hover': '')
                ]);
            });
            $all = Html::a('- ALL -', Url::to([$action, 'tag' => '']), [
                'class' => 'dropdown-item ' . (($activeTag == '')? 'dropdown-item-hover': '')
            ]);
            return <<< HTML
                <div class="text-right mt-2">
                    <div class="btn-group ">
                        <button type="button" class="btn btn-primary btn-sm">{$activeTag}</button>
                        <button type="button" class="btn btn-primary  btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            {$all}
                            {$list}
                        </div>
                    </div>
                </div>
            HTML;
        }
    }

    public function getLocationPath()
    {
        return Url::home(true) . $this->location;
    }
}