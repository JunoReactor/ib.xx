<?php

class Utils {
    public static function IconvArr($in,$out,$arr,$set_globl_char=false)
    {
      if ($set_globl_char!=false) {

        if ($this->char==$out) {
          return $arr;
        }else{
          $this->char = $out;
        }
      }
      if (is_array($arr)) {
        foreach($arr as $key => $value)
        {
            if(is_array($value)) {
                $arr[$key] = $this->iconv_arr($in,$out,$value);
            } else {
              $arr[$key] = iconv($in,$out,$value);
            }

        }
        return $arr;
      } else { return false; }
    }
    
    public static function IconvObg($in,$out,$arr,$set_globl_char=false)
    {
      if ($set_globl_char!=false) {

        if ($this->char == $out) {
          return $arr;
        }else{
          $this->char = $out;
        }
      }
      if (is_object($arr)) {
        foreach($arr as $key => $value)
        {
            if(is_object($value)) {
                $arr->$key = $this->iconv_arr($in,$out,$value);
            } else {
              $arr->$key = iconv($in,$out,$value);
            }

        }
        return $arr;
      } else { return false; }
    }
    
    public static function NumberDoubleValue($Number)  
    {
        if(mb_strlen($Number) == 1){
          $Number = '0'.$Number;  
        }
        return $Number;
    }
    
    public static function Redirect($URL = FALSE)
    {
        if ($URL == FALSE) {
            header('Location: ?'.$_SERVER["QUERY_STRING"] );  
        } else {
            header('Location: '.$URL);  
        }        
        exit;
    } 
    
}
