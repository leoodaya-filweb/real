<?php

namespace app\helpers;

use Yii;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    public static function valueToKey($arr='')
    {
        return array_combine($arr, $arr);
    }

  /*
    public static function range($range)
    {
    	$arr = [];

    	for ($i=0; $i <= $range; $i++) { 
    		$arr[$i] = $i;
    	}

    	return $arr;
    }
    
   */
   
    public static function range($start, $end=0)
    {
    	$data = [];

    	if ($start == $end) {
    		$data[$start] = $end;
    		return $data;
    	}

    	if ($start < $end) {
    		while ($start < $end) {
	    		$data[$start] = $start;
	    		$start++;
	    	}
	    	return $data;
    	}

    	if ($start > $end) {
    		while ($start > $end) {
	    		$data[$start] = $start;
	    		$start--;
	    	}
    		return $data;
    	}
    } 
    
    
}