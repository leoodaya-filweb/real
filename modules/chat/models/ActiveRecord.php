<?php 

namespace app\modules\chat\models;

use app\modules\chat\helpers\App;

abstract class ActiveRecord extends \app\models\ActiveRecord
{
  	public static function findByToken($token)
  	{
  		return static::findOne(['token' => $token]);
  	}

  	public function getCanActivate()
  	{
  		return true;
  	}

  	public static function batchInsert($data=[], $tableName='')
  	{
  		$tableName = $tableName ?: static::tableName();
  		if ($data) {
	        $arr = array_chunk($data, 1000);
	        $columns = array_keys($data[0]);
	        foreach ($arr as $r) {
	            App::createCommand()
	                ->batchInsert($tableName, $columns, $r)
	                ->execute();
	        }
	    }
  	}

  	public function getTimestamp()
  	{
  		return strtotime($this->updated_at);
  	}
}