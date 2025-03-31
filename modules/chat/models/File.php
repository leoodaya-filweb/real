<?php

namespace app\modules\chat\models;

use Yii;
use yii\helpers\StringHelper;
use app\modules\chat\helpers\App;
use app\modules\chat\helpers\Url;

class File extends \app\models\File
{
	public function fields()
	{
		$fields = parent::fields();

        $fields['isImage'] = 'isImage';
		$fields['timeSent'] = 'timeSent';
		$fields['viewerUrl'] = 'viewerUrl';
		$fields['fileSize'] = 'fileSize';
		$fields['createdByName'] = 'createdByName';
		$fields['ago'] = 'ago';
		$fields['truncatedName'] = fn($model) => StringHelper::truncate($model->name, 30);
        $fields['displayPath'] = fn($model) => $model->getDisplayPath(50);
		$fields['imagePath'] = fn($model) => App::ifElse($model->extension == 'gif', $model->locationPath, $model->getDisplayPath(200));

		return $fields;
	}

    public function getIsImage()
    {
        return in_array($this->extension, parent::EXTENSIONS['image']);
    }

	public function getTimeSent()
    {
        $currentDate = App::formatter()->asDateToTimezone('', 'Y-m-d H:i:s');

        $currentYmd = date('Y-m-d', strtotime($currentDate));

        if ($currentYmd == date('Y-m-d', strtotime($this->created_at))) {
            return date('h:i A', strtotime($this->createdAt));
        }

        return date('D h:i A', strtotime($this->createdAt));
    }

    public function getViewerUrl($fullpath=true)
    {
    	return Url::toRoute(['/file/viewer', 'token' => $this->token]);
    }

    public function getDownloadUrl($fullpath=true)
    {
    	return Url::toRoute(['/file/download', 'token' => $this->token]);
    }
}
