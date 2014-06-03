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
                    <img src="/?photo/get/<?=$row['ID']?>" align="center" data-placement="top" data-toggle="tooltip" title="" data-original-title="Tooltip on bottom" title="<?=$row['Status']?>" width="180" height="240">
                    <div class="<?=$row['StatusImgCode']?>"></div>
                </td>
                <td><h4><?=$row['FullName']?></h4></td>
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
    
    if(isset($data['USER_TALOG'][0])) 
    {
        foreach($data['USER_TALOG'] as $month)
            { 
                    ?>
                        <tr class="success">
                            <td colspan="3"><?=$month['Month']?></td>
                        </tr>
                        <tr>
                            <td class="text-left"><strong>Дата</strong></td>
                            <!--<td>День недели</td>-->
                            <td class="text-center"><strong>Приход</strong></td>
                            <td class="text-center"><strong>Уход</strong></td>
                        </tr>
                    <?php 
                foreach($month['arr'] as $row)
                    { 
                        if(!isset($row->MIN_TIME)){
                            $row->MIN_TIME = 'нет данных';
                        }
                        if(!isset($row->MAX_TIME)){
                            $row->MAX_TIME = 'нет данных';
                        }
                        print '<tr>'; 
                        print '<td class="text-left">'.$row->DAY.'</td>';
                        print '<td class="text-center">'.$row->MIN_TIME.'</td>';
                        print '<td class="text-center">'.$row->MAX_TIME.'</td>';
                        //print '<td>'.$row->MIN_EV_DATETIME.'</td>'; 
                        //print '<td>'.$row->MAX_EV_DATETIME.'</td>';
                        print '</tr>';                    
                    }
            }

    } else {
                    ?>
                        <tr>
                            <td><div class="alert-warning">Данные отсутствуют</div></td>
                        </tr>

                    <?php
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