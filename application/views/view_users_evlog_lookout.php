<!--<p>Выбрано позиций: <?=count($data['USER_INFO'])?></p>-->
<div class="clearfix"></div>

<?php
if(!empty($data['USER_INFO'])) {
    foreach($data['USER_INFO'] as $row)
    {  
?> 
        <div class="col-md-6 user-card">     
        <table width="100%" class="table table-striped">
            <tr>
                <td rowspan="5" class="user-photo" width="190">
                    <img src="/?photo/get/<?=$row['ID']?>" align="center" title="<?=$row['Status']?>" width="180" height="240">
                    <div class="<?=$row['StatusImgCode']?>"></div>
                </td>
                <td><a href="?users/get/<?=$row['ID']?>" target="_blank"><h4><?=$row['FullName']?></h4></a></td>
            </tr><tr>    
                <td><?=$row['Dept']?>&nbsp;</td>
            </tr><tr>    
                <td><?=$row['Position']?>&nbsp;</td>
            </tr><tr>    
                <td>
                    <p><img src="/img/Google-Maps-icon.png" title="Местонахождение"> <?=$row['Area']?></p>
                    <p><img src="/img/calendar-selection-day-icon.png" title="Дата последнего визита"> <font class="Data"><?=$row['Data']?></font></p>
                    <p><img src="/img/Time-icon.png" title="Время последнего визита"> <font class="Time"><?=$row['Time']?></p></font>
                </td>
            </tr><tr>
                <td>
                    <p><img src="/img/Telephone-icon.png" title=""> <?=$row['Phone']?></p>
                    <p><?=$row['Email']?></p>
                    &nbsp;
                </td>
            </tr>
        </table>
        </div> 

        <div class="col-md-6 user-card">     
        <table width="100%" class="table table-striped">

<?php  
    }
    
    if(!empty($data['USER_TALOG'])) 
    {
        foreach($data['USER_TALOG'] as $row)
        { 
        ?>
            <tr>
                <td><?=$row->EV_DATETIME?></td>
                <td><?=$row->TA_TYPE_NAME?></td>
            </tr>
        <?php 
        }
    }
?>
        </table>
    </div> 
<?php
} else {
    if($data == 1){
       print '<div class="alert alert-warning"><p class="text-center">Пустой запрос.</p></div>';  
    }
    if($data == 2){
       print '<div class="alert alert-info"><p class="text-center">По вашему запросу ничего не найдено, попробуйте изменить или упростить запрос.</p></div>';  
    }  
    if($data == 3){
       print '<div class="alert alert-info"><p class="text-center">Запрос должен быть больше трех символов.</p></div>';  
    }  
    /*
<div class="alert alert-success">...</div>
<div class="alert alert-info">...</div>
<div class="alert alert-warning">...</div>
<div class="alert alert-danger">...</div>
*/   
}
?>