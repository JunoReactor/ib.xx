<?php
class Log {

    public function __construct()
    {
       
    }
    
    public static function Add($string)
    {
        $h = fopen(DOCUMENT_ROOT."/log.txt","a");
        if (fwrite($h,$string."\n")) {
        //  echo "Запись произведена успешно";  
        } else {
          echo "Произошла ошибка при записи данных";
        }
        fclose($h);
    }


}