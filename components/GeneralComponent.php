<?php

namespace app\components;

use app\helpers\App;

class GeneralComponent extends \yii\base\Component
{
    public function getAllTables()
    {
        $tables = App::getTableNames();
        $tables = array_combine($tables, $tables);
        return $tables;
    }

    public function timezoneList($type='alpha_key') 
    {
        $arr = timezone_identifiers_list();

        switch ($type) {
            case 'alpha_key':
                return array_combine($arr, $arr);
                break;

            case 'numeric_key':
                return $arr;
                break;
            
            default:
                return $arr;
                break;
        }
    }

    public function dateDiff($date1, $date2, $format="days")
    {
        
        $date1 = new \DateTime($date1);
        $date2 = new \DateTime($date2);

        $diff = $date1->diff($date2);

        return $diff->$format;
    }
}