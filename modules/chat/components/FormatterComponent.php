<?php 

namespace app\modules\chat\components;

class FormatterComponent extends \app\components\FormatterComponent
{
	public function asImplodeLimit($list, $limit = 2) 
    {
        $list = is_array($list) ? $list: [$list];

        if (count($list) > $limit) {
        	$remaining = count($list) - $limit;

        	$list = [$list[0], $list[1], '('. number_format($remaining) .') other' . (($remaining > 1) ? 's': '')];
        }

        return $this->asImplode($list);
    }
}