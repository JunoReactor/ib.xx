<h1>Загрузчик фотографий</h1>

<table class="tableDesign_1">
    <thead>
        <tr>
            <td>ID</td>
            <td>Ф.И.О.</td>
            <td>Фото</td>
            <td>Подразделение</td>
            <td>Должность</td>
            <td>Статус</td>
            <td>Зона / Время</td>
            <td>Внутренний тел.</td>
        </tr>
    </thead>
    <tbody>
<?php

    foreach($data as $row)
    {   
?> 
        <tr>
                <td><?=$row['ID']?></td>
                <td><?=$row['FullName']?></td>
                <td><?=$row['Photo']?></td>
                <td><?=$row['Dept']?></td>
                <td><?=$row['Position']?></td>
                <td><?=$row['Status']?></td>
                <td><?=$row['Area']?> / <?=$row['When']?></td>               
                <td><?=$row['Phone']?></td>
        </tr>
<?php      
    }
?>
    </tbody>
</table>

