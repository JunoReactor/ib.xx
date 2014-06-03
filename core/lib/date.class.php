<?php
class Date {

    public function __construct()
    {
       
    }
    
    public static function GetToday()
    {
        return date("Y-m-d");
    }
    
    public static function GetTodayDateTime()
    {
        return date("Y-m-d G:i:s");
    }
    
    public static function GetYesterday()
    {
        $my_time = time() - 86400; 
        $yesterday = date ("Y-m-d", $my_time); 
        return $yesterday;
    }
    
    public static function GetPeriod($Period = 3,$type = 'M')
    {      
        $date = date("d-m-Y");
        if($type = 'M') $type = 'month';
        if($type = 'D') $type = 'day';

        $time = strtotime($date.' 00:00 -'.$Period.' '.$type);
        $yesterday = date("Y-m-d", $time); 
        return $yesterday;
    }
    
    public static function CropDate($date)
    {
       $r = explode(' ', $date);
       return $r[0];
    }
    
    # $date_1 > $date_2
    public static function Compare($date_1,$date_2)
    {
        $st_date_1 = strtotime($date_1);
        $st_date_2 = strtotime($date_2); 
        if ($st_date_1 > $st_date_2) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public static function GetFullDateFromDatetime($date)
    {
       $ex = explode(' ', $date);
       return $ex[0];
    }
    
    # Подавать дату в формате: 2014-04-15 $type = year, month, day, hour
    public static function GetMonthFromDate($date,$type = 'month')
    {
        //$ex = explode('-', $date);
        $d  = date_parse($date);
        return $d[$type];
    }
    
    public static function GetMonthRussian($MonthNum)
    {
        switch ($MonthNum){
            case 01: $m='Январь'; break;
            case 02: $m='февраль'; break;
            case 03: $m='Март'; break;
            case 04: $m='Апрель'; break;
            case 05: $m='Май'; break;
            case 06: $m='Июнь'; break;
            case 07: $m='Июль'; break;
            case 08: $m='Август'; break;
            case 09: $m='Сентябрь'; break;
            case 10: $m='Октябрь'; break;
            case 11: $m='Ноябрь'; break;
            case 12: $m='Декабрь'; break;
        }
        return $m;
    }    
    
    public static function Russian()
    {
        $date=explode(".", date("d.m.Y"));
        switch ($date[1]){
            case 1: $m='января'; break;
            case 2: $m='февраля'; break;
            case 3: $m='марта'; break;
            case 4: $m='апреля'; break;
            case 5: $m='мая'; break;
            case 6: $m='июня'; break;
            case 7: $m='июля'; break;
            case 8: $m='августа'; break;
            case 9: $m='сентября'; break;
            case 10: $m='октября'; break;
            case 11: $m='ноября'; break;
            case 12: $m='декабря'; break;
        }
        return $date[0].'&nbsp;'.$m.'&nbsp;'.$date[2];
    }
    
    public static function Convert($date)
    {
        $date_ex = explode('-', $date);
        return  self::RussianDate($date_ex[2].'.'.$date_ex[1].'.'.$date_ex[0]);
    }
    /*
    [year] => 2006
    [month] => 12
    [day] => 12
    [hour] => 10
    [minute] => 0
    [second] => 0
    [fraction] => 0.5
    [warning_count] => 0
    [warnings] => Array()
    [error_count] => 0
    [errors] => Array()
    [is_localtime] =>
     */
    public static function GetDayFromDate($DateInput) {
        $DateOut = date_parse($DateInput);
        return $DateOut['day'];
    }
    
    public static function GetTimeFromDate($DateInput) {
        
        $DateOut = date_parse($DateInput);
        if ($DateOut['error_count'] > 0) {
            return FALSE;
        } 
        if(mb_strlen($DateOut['minute']) == 1){
            $DateOut['minute'] = '0'.$DateOut['minute'];  
        }
        return ' '.$DateOut['hour'].':'.$DateOut['minute'].' ';
    }
}