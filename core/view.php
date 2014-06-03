<?php

class View
{
    //public $template_view; // здесь можно указать общий вид по умолчанию.
    
    function generate($content_view, $template_view, $data = null)
    {
        /*
        if(is_array($data)) {
            // преобразуем элементы массива в переменные
            extract($data);
        }
        */
        $inc = 'application/views/templates/'.$template_view;
        if(file_exists($inc)){
           include_once $inc; 
        } else {
           print 'Шаблон '.$inc.' не найден.'; 
        }
    }
}