<?php
    if(isset($data['USER_TALOG'][0])) 
    {
        //print '<pre>'.count($data['USER_TALOG']);
        //print_r($data['USER_TALOG']);
        print '<table width="100%" class="table table-striped">';
        foreach($data['USER_TALOG'] as $month)
            { 
                ?>
                    <tr class="success">
                        <td colspan="2"><?=$month['Month']?></td>
                    </tr>
                    <tr>
                        <td>Приход</td>
                        <td>Уход</td>
                    </tr>
                <?php 
                foreach($month['arr'] as $row)
                    { 
                        if(!isset($row->MAX_EV_DATETIME)){
                            $row->MAX_EV_DATETIME = '';
                        }
                        if(!isset($row->MIN_EV_DATETIME)){
                            $row->MIN_EV_DATETIME = '';
                        }
                        print '<tr>'; 
                        print '<td>'.$row->MIN_EV_DATETIME.'</td>'; 
                        print '<td>'.$row->MAX_EV_DATETIME.'</td>';
                        print '</tr>';                    
                    }
            }
        print '</table>';    
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

?>