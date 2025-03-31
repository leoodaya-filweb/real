<?php

namespace app\components;

use app\helpers\App;
use app\helpers\Html;
use app\widgets\JsonEditor;
use yii\helpers\Inflector;
use yii\helpers\Json;

class FormatterComponent extends \yii\i18n\Formatter
{
    public $nullDisplay = '';
    
    public function asStripTags($value)
    {
        return strip_tags($value);
    }

    public function asAgo($value)
    {
        $today = new \DateTime('now');
        $datetime = new \DateTime($value);
        $interval = $today->diff( $datetime );
        $suffix = ( $interval->invert ? ' ago' : ' to go' );
        
        if ( $v = $interval->y >= 1 ) return self::pluralize( $interval->y, 'year' ) . $suffix;
        if ( $v = $interval->m >= 1 ) return self::pluralize( $interval->m, 'month' ) . $suffix;
        if ( $v = $interval->d >= 28 ) return self::pluralize( 4, 'week' ) . $suffix;
        if ( $v = $interval->d >= 21 ) return self::pluralize( 3, 'week' ) . $suffix;
        if ( $v = $interval->d >= 14 ) return self::pluralize( 2, 'week' ) . $suffix;
        if ( $v = $interval->d >= 7 ) return self::pluralize( 1, 'week' ) . $suffix;
        if ( $v = $interval->d >= 1 ) return self::pluralize( $interval->d, 'day' ) . $suffix;
        if ( $v = $interval->h >= 1 ) return self::pluralize( $interval->h, 'hour' ) . $suffix;
        if ( $v = $interval->i >= 1 ) return self::pluralize( $interval->i, 'minute' ) . $suffix;

        if ($interval->s == 0) {
            return 'Just now';
        }
        
        return $this->pluralize( $interval->s, 'second' ) . $suffix;
    }

    private function pluralize( $count, $text )
    {
        return $count . (($count == 1)? " {$text}": " {$text}s");
    }

    public function asDateFormat($date='', $format='F d, Y h:i:s A')
    {
        return date($format, strtotime($date));
    }

    public function asFulldate($value)
    {
        return $this->asDateToTimezone($value);
    }

    public function asDateToTimezone($date='', $format='F d, Y h:i A', $timezone="")
    {
        $timezone = $timezone ?: App::setting('system')->timezone;

        $date = ($date)? $date: date('Y-m-d H:i:s');

        $usersTimezone = new \DateTimeZone($timezone);
        $l10nDate = new \DateTime();
        $l10nDate->setTimestamp(strtotime($date));
        $l10nDate->setTimeZone($usersTimezone);

        return $l10nDate->format($format);
    }

    public function asController2Menu($value)
    {
        $string = ucwords(
            str_replace('-', ' ', Inflector::titleize($value))
        );

        $string = str_replace('Controller', '', $string);

        return trim($string);
    }

    public function asBoolString($value)
    {
        return $value ? 'True': 'False';
    }

    public function asEncode($value)
    {
        return Json::encode($value);
    }

    public function asDecode($value)
    {
        return Json::decode($value, true);
    }

    public function asJsonEditor($value)
    {
        return JsonEditor::widget([
            'data' => $value,
        ]);
    }

    public function asQuery2ControllerID($value)
    {
        $get_called_class = explode("\\", $value);
        return Inflector::camel2id(substr(end($get_called_class), 0, -5));
    }

    public function asFileSize($bytes)
    {
        $gb = 1073741824;
        $mb = 1048576;
        $kb = 1024;

        if ($bytes >= $gb) {
            $bytes = number_format($bytes / $gb, 2) . ' GB';
        }
        elseif ($bytes >= $mb) {
            $bytes = number_format($bytes / $mb, 2) . ' MB';
        }
        elseif ($bytes >= $kb) {
            $bytes = number_format($bytes / $kb, 2) . ' KB';
        }
        elseif ($bytes > 1) {
            $bytes = number_format($bytes) . ' bytes';
        }
        elseif ($bytes == 1) {
            $bytes = number_format($bytes) . ' byte';
        }
        else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function asDaterangeToSingle($date_range, $return='start', $format="Y-m-d")
    {
        if ($date_range && str_contains($date_range, ' - ')) {
            $dates = explode(' - ', $date_range);
            $start = date($format, strtotime($dates[0])); 
            $end = date($format, strtotime($dates[1])); 

            return ($return == 'start')? $start: $end;
        }
    }

    public function asDaterange($date_range, $format='F d, Y')
    {
        $start = $this->asDaterangeToSingle($date_range, 'start', $format);
        $end = $this->asDaterangeToSingle($date_range, 'end', $format);

        if ($start || $end) {
            return implode(' - ', [$start, $end]);
        }
        
    }

    public function asNumber($num, $dec=2)
    {
        $num = (float) $num;
        if(floor($num) == $num) {
            return number_format($num);
        } else {
            return number_format($num, $dec);
        }
    }

    public function AsNumPad3($value)
    {
        return $this->AsStrPad($value, 3);
    }

    public function AsNumPad2($value)
    {
        return $this->AsStrPad($value);
    }

    public function AsStrPad($value, $length=2, $fix='0', $position=STR_PAD_LEFT)
    {
        return str_pad($value, $length, $fix, $position);
    }

    public function AsAge($birthdate='')
    {
        $today = self::asDateToTimezone('', 'Y-m-d');

        $diff = date_diff(date_create($birthdate), date_create($today));
        
        $years = $diff->format('%y');
        $months = $diff->format('%m');
        
        if($years<1){
          return ($months?($months>1?$months.' months ': $months.' month '):'0 month');  
        }

        return $years ?: 0;
    }

    public function asClean($str)
    {
        $replace = [
            '?' => '',
        ];

        return str_replace(array_keys($replace), array_values($replace), $str);
       // $str = str_replace(' ', '-', $str); // Replaces all spaces with hyphens.

       return preg_replace('/[^A-Za-z0-9\-]/', '', $str); // Removes special chars.
    }

    public function asImplode($list, $conjunction = 'and') 
    {
        $list = is_array($list) ? $list: [$list];
        $last = array_pop($list);
        if ($list) {
            return implode(', ', $list) . ' ' . $conjunction . ' ' . $last;
        }
        return $last;
    }

    public function asUl($list)
    {
        $list = is_array($list) ? $list: [$list];

        return Html::ul($list);
    }
    
    
     public function asKelvinToCelcius($kelvin)
    {
        return $kelvin - 273.15;
    }

    public function asKelvinToFahrenheit($kelvin)
    {
        return $this->asCelciusToFahrenheit($this->asKelvinToCelcius($kelvin));
    }

    public function asCelciusToFahrenheit($celcius)
    {
        return ($celcius * 1.8) + 32;
    }

    public function asUcWords($str='')
    {
        return ucwords(strtolower($str));
    }

    public function asOrdinal($number) 
    {
        $number = abs($number) % 100;
        $lastChar = substr($number, -1, 1);
        switch ($lastChar) {
            case '1' : $suffix = ($number == '11') ? 'th' : 'st'; break;
            case '2' : $suffix = ($number == '12') ? 'th' : 'nd'; break;
            case '3' : $suffix = ($number == '13') ? 'th' : 'rd';  break;
            default:
                $suffix = 'th';
                break;
        }

        return implode('', [$number, $suffix]); 
    }

    public function asPeso($num=0)
    {
        return '₱' . $this->asNumber($num);
    }

    public function asEPSG4326($EPSG3857_coordinates)
    {
        $x = $EPSG3857_coordinates['lon'] ?: 0;
        $y = $EPSG3857_coordinates['lat'] ?: 0;

        // convert x to longitude
        $lon = ($x / 20037508.34) * 180;

        // convert y to latitude
        $lat = ($y / 20037508.34) * 180;
        $lat = 180 / pi() * (2 * atan(exp($lat * pi() / 180)) - pi() / 2);

        return [
            'lat' => $lat,
            'lon' => $lon,
        ];
    }

    public function asEPSG3857($EPSG4326_coordinates)
    {
        // Define the coordinates to convert
        $lon = $EPSG4326_coordinates['lon'] ?: 0;
        $lat = $EPSG4326_coordinates['lat'] ?: 0;

        // Convert the coordinates
        $x = $lon * 20037508.34 / 180;
        $y = log(tan((90 + $lat) * pi() / 360)) / (pi() / 180);
        $y = $y * 20037508.34 / 180;

        // Output the converted coordinates
        return [
            'lat' => $y,
            'lon' => $x,
        ];
    }

    public function asCenterCoordinate($coordinates=[])
    {
        if (! $coordinates) {
            return [
                'lat' => 0,
                'lon' => 0,
            ];
        }
        $count_coords = count($coordinates);
        $xcos=0.0;
        $ycos=0.0;
        $zsin=0.0;
        
        foreach ($coordinates as $lnglat) {
            $data = $this->asEPSG4326($lnglat);

            $lat = $data['lat'] * pi() / 180;
            $lon = $data['lon'] * pi() / 180;
            
            $acos = cos($lat) * cos($lon);
            $bcos = cos($lat) * sin($lon);
            $csin = sin($lat);
            $xcos += $acos;
            $ycos += $bcos;
            $zsin += $csin;
        }
        
        $xcos /= $count_coords;
        $ycos /= $count_coords;
        $zsin /= $count_coords;
        $lon = atan2($ycos, $xcos);
        $sqrt = sqrt($xcos * $xcos + $ycos * $ycos);
        $lat = atan2($zsin, $sqrt);
        
        return $this->asEPSG3857([
            'lat' => $lat * 180 / pi(),
            'lon' => $lon * 180 / pi(),
        ]);
    }

    public function asUcFirst($str)
    {
        return ucfirst($str);
    }

    public function asMillisecondsToReadable($milliseconds=0)
    {
        $seconds = floor($milliseconds / 1000);
        if (!$seconds || $seconds < 1) {
            return ;
        }

        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        $result = [];

        $result[] = $days ? number_format($days) . " day" . (($days > 1) ? 's': ''):'';
        $result[] = $hours ? number_format($hours) . " hour" . (($hours > 1) ? 's': ''):'';
        $result[] = $minutes ? number_format($minutes) . " minute" . (($minutes > 1) ? 's': ''):'';
        $result[] = $seconds ? number_format($seconds) . " second" . (($seconds > 1) ? 's': ''):'';

        return $this->asImplode(array_filter($result));
    }
    
    
}