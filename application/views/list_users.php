<?php
foreach($data as $row)
{   
?><option value="<?=$row['ID']?>"><?=$row['FullName']?></option><?php      
}
?>