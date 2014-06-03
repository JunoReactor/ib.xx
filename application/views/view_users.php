<h1>Статистика пользователей</h1>
<!--<div>
	<select id="combobox">
            <option value="0" selected="selected"> </option>
<?php
    foreach($data as $row)
    {   
?> 
        <option value="<?=$row['ID']?>"><?=$row['FullName']?></option>
<?php      
    }
    $q = $_POST['q'];
    if(empty($q)) $q = '';
?>
	</select>
</div>-->
<div class="search">
    <form action="?users/search/" method="POST" enctype="multipart/form-data">
         <input name="q" type="text" placeholder="" value="<?=$q?>">
         <input name="" type="submit" value="Найти">  
    </form>   
</div>

<p>Выбрано позиций: <?=count($data)?></p>
<div class="clear"></div>
<table class="tableDesign_1" id="tablesorter">
    <thead>
        <tr>
            <th>ID</th>
            <!--<th>Табельный номер</th>-->
            <th>Ф.И.О.</th>
            <th>Фото</th>
            <th>Подразделение</th>
            <th>Должность</th>
            <th>Статус</th>
            <th>Зона</th>
            <th>Контакты</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach($data as $row)
    {   
?> 
        <tr<?=$row['Class']?>>
                <td><a href="?users/get/<?=$row['ID']?>" target="_blank"><?=$row['ID']?></a></td>
                <!--<td><?=$row['TA_Mark']?></td>-->
                <td><?=$row['FullName']?></td>
                <td>
                    <!--<div style="float:right;width:310px;height:400px;position:absolute;"></div> 
                    <div class="easyzoom easyzoom--adjacent">-->
                      <!-- ?photo/get/<?=$row['ID']?>  target="_blank"     
                      <a href="/?photo/get/<?=$row['ID']?>">--><img src="/?photo/get/<?=$row['ID']?>" align="center" ><!--</a> width="180" height="240"
                    </div> --> 
                </td>    
                <td><?=$row['Dept']?></td>
                <td><?=$row['Position']?></td>
                <td><?=$row['Status']?> <br /><?=$row['StatusImgCode']?></td>
                <td><?=$row['Area']?><br />
                    <font class="Data"><?=$row['Data']?></font><br/>
                    <font class="Data"><?=$row['Time']?></font>
                </td>
                <td><?=$row['Phone']?><br><?=$row['Comment']?></td>
        </tr>
<?php      
    }
?>
    </tbody>
</table>

<!--<div id="tabs">
	<ul>
		<li><a href="#tabs-1">First</a></li>
		<li><a href="#tabs-2">Second</a></li>
		<li><a href="#tabs-3">Third</a></li>
	</ul>
	<div id="tabs-1">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
	<div id="tabs-2">Phasellus mattis tincidunt nibh. Cras orci urna, blandit id, pretium vel, aliquet ornare, felis. Maecenas scelerisque sem non nisl. Fusce sed lorem in enim dictum bibendum.</div>
	<div id="tabs-3">Nam dui erat, auctor a, dignissim quis, sollicitudin eu, felis. Pellentesque nisi urna, interdum eget, sagittis et, consequat vestibulum, lacus. Mauris porttitor ullamcorper augue.</div>
</div>

<p><a href="#" id="dialog-link" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-newwin"></span>Open Dialog</a></p>

 
<div id="dialog" title="Dialog Title">
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>-->

