<!----><p><small>Выбрано позиций: <?=count($data['USERS'])?></small></p>
<div class="clearfix"></div>

<?php
if(!empty($data['USERS'])) {
$i = 1;
$i_max = 2;
    foreach($data['USERS'] as $row)
    {  
        if ($i > $i_max) $i = 1;
        if($i == 1) print '<div class="row">';
        $col_md = 12/$i_max;
?> 
        <div class="col-md-<?=$col_md?> user-card">     
        <table width="100%" class="table table-striped">
            <tr>
                <td rowspan="5" class="user-photo" width="190">
                        <img src="/?photo/get/<?=$row['ID']?>" align="center" width="180" height="240" data-toggle="tooltip" rel="tooltip" data-placement="top" title="<?=$row['Status']?>">
                    <div class="<?=$row['StatusImgCode']?>"></div>
                </td>
                <td>
                    <a href="?lookout/get/<?=$row['ID']?>" rel="tooltip" data-toggle="tooltip" title="Перейти на странцу с подробной информацией о пользователе..."><h4><?=$row['FullName']?></h4></a>
                </td>
            </tr><tr>    
                <td><?=$row['Dept']?>&nbsp;</td>
            </tr><tr>    
                <td><?=$row['Position']?>&nbsp;</td>
            </tr><tr>    
                <td>
                    
                    <p class="pull-left"><img src="/img/Google-Maps-icon.png" title="Местонахождение" data-toggle="tooltip" rel="tooltip" data-placement="top"> <?=$row['Area']?></p>
                    <div class="clearfix"></div>
                    <p class="pull-left"><img src="/img/calendar-selection-day-icon.png" title="Дата последнего визита" rel="tooltip" data-toggle="tooltip" data-placement="top"> <font class="Data"><?=$row['Data']?></font></p>
                    <div class="clearfix"></div>
                    <p class="pull-left"><img src="/img/Time-icon.png" title="Время последнего визита" rel="tooltip" data-toggle="tooltip" data-placement="top"> <font class="Time"><?=$row['Time']?></font></p>
                    <?php
                        /*if(isset($data['TALOG'][$row['ID']])) {
                            ?><button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target=".bs-stat-modal-lg<?=$row['ID']?>">Статистика посещений</button> <?php
                        } else {
                            //print '[нет данных по посещаемости]';<!--<!----> -->
                            ?><button class="btn btn-sm btn-primary pull-right" disabled="disabled" data-toggle="modal" data-target=".bs-stat-modal-lg<?=$row['ID']?>">Статистика посещений</button><?php
                        }*/
                    /*?><button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target=".bs-stat-modal-lg<?=$row['ID']?>">Статистика посещений</button><?php
                    */?>
                </td>
            </tr><tr>
                <td>
                    <p class="pull-left"><img src="/img/Telephone-icon.png" title=""> <?=$row['Phone']?></p>
                    <p class="pull-left">&nbsp;<a href="mailto:<?=$row['Email']?>"><?=$row['Email']?></a></p>
                    <?php
                    if(!empty($row['Comment'])){
                        ?>
                        <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target=".bs-example-modal-lg<?=$row['ID']?>">Примечания</button>
                        <?php 
                    }
                    ?>
                </td>
            </tr>
        </table>
        
       <?php
        //if(isset($data['TALOG'][$row['ID']])){
            ?>
                <div class="modal fade bs-stat-modal-lg<?=$row['ID']?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-center">
                    <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <h4 class="modal-title" id="myModalLabel"><a href="?users/get/<?=$row['ID']?>"><?=$row['FullName']?></a>: Краткая статистика</h4>
                            </div>
                            <div class="modal-body">
                                <table width="100%" class="table table-striped">
                                <?php
                                foreach($data['TALOG'][$row['ID']] as $key => $log_row)
                                    {  
                                        print '<tr><td>'.$data['TALOG'][$row['ID']][$key]['EV_DATETIME'].'</td><td>'.$data['TALOG'][$row['ID']][$key]['TA_TYPE_NAME'].'</td></tr>'; 
                                    }
                                ?>
                                </table>

                                <p class="pull-left"><a href="?users/get/<?=$row['ID']?>">Подробная статистика...</a></p>
                                <div class="clearfix"></div>
                                <div class="modal-footer">
                                     <a href="#" class="btn btn-sm pull-right" data-dismiss="modal" aria-hidden="true">Закрыть</a>
                                </div>
                            </div>
                    </div>
                  </div>
                </div>
            <?php 
       // } 
        if(!empty($row['Comment'])){
            ?>
                <div class="modal fade bs-example-modal-lg<?=$row['ID']?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-center">
                    <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <h4 class="modal-title" id="myModalLabel"><?=$row['FullName']?>: Примечания</h4>
                            </div>
                            <div class="modal-body">
                                <?=$row['Comment']?>
                            </div>
                    </div>
                  </div>
                </div>
            <?php 
        }
        ?>
        </div>                    
<?php  
        if($i == $i_max) print '</div>';
        $i++;
    }
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