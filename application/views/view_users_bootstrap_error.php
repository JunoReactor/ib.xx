<?php
foreach($data as $err)
{
    if($err == 2){
       print '<div class="alert alert-warning"><p class="text-center">Пустой запрос.</p></div>';  
    }
    if($err == 1){
       print '<div class="alert alert-info"><p class="text-center">По вашему запросу ничего не найдено, попробуйте изменить или упростить запрос.</p></div>';  
    }  
    if($err == 3){
       print '<div class="alert alert-info"><p class="text-center">Запрос должен быть больше одного символа.</p></div>';  
    }    
}  
 
/*
<div class="alert alert-success">...</div>
<div class="alert alert-info">...</div>
<div class="alert alert-warning">...</div>
<div class="alert alert-danger">...</div>
*/   
?>