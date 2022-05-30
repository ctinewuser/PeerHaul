<?php

use Carbon\Carbon;

/**
 * Get date format
 */
function getFormatedDate($value)
{
    $carbon = new Carbon($value);
    return $carbon->toDateTimeString();
}
/**
 * Get format date and time
 */
function getFormatedDateTime($value)
{
    $carbon = new Carbon($value);
    return $carbon->toDayDateTimeString();
}

/**
 * Get date
 */
function dateFormatMDY($string)
{
    return Carbon::parse($string)->format('m/d/Y');
}
/**
 * Get date
 */
function dateFormat($string)
{
    return Carbon::parse($string)->format('Y-m-d');
}
function timeFormat($string)
{
    return Carbon::parse($string)->format('H:i:s');
}
function dayTimeFormat($string)
{
    return Carbon::parse($string)->format('g:i A');
}
/**
 * Get current date
 */
function currentDateFormat()
{
    $mytime = Carbon::now();
    return $mytime->toDateTimeString();
}
/**
 * Get opening days
 */
function getOpenDays($days)
{

    $dayArray = ['0'=>'S','1'=>'M','2'=>'T','3'=>'W','4'=>'TH','5'=>'F','6'=>'S'];
    $dayA = explode(",",$days);
    $newData = [];
    foreach($dayArray as $key => $dayVal){
        if(in_array($key, $dayA)){
            array_push($newData, $dayVal);
        }
    }
    $newData = implode(', ',$newData);
    return $newData;
}
/**
 * Get opening days
 */
function getOpenDaysArray($days)
{

    $dayArray = ['0'=>'S','1'=>'M','2'=>'T','3'=>'W','4'=>'TH','5'=>'F','6'=>'S'];
    $dayA = explode(",",$days);
    $newData = [];
    foreach($dayArray as $key => $dayVal){
        if(in_array($key, $dayA)){
            array_push($newData, $dayVal);
        }
    }
    // $newData = implode(', ',$newData);
    return $newData;
}


function to_time_ago($datetime, $full = false) {

    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
