<?php
class Model_Ajax extends Model
{
    var $ClassName = 'even';
    var $Status = FALSE;
    var $DocumentTitle = 'Статистика пользователей';
    
    public function get_data($data = FALSE){	

    }
    
    public function get_user_taLog($data){
        $r = array();
        while ($row = ibase_fetch_object($data)) {
            $row = Utils::IconvObg('windows-1251', 'utf-8', $row);
            $date = date_parse($row->MAX_EV_DATETIME);
            
            $key = $this->AddDateList($date['month']);
            switch ($row->TA_TYPE) {
                case '1': $row->TA_TYPE_NAME = 'Приход'; break;    
                case '2': $row->TA_TYPE_NAME = 'Уход'; break;
            } 
            $r[$key]['arr'][] = $row;
            $r[$key]['Month'] = DATE::GetMonthRussian($this->GetDateFromID($key));
            
        }
        if(!isset($key)) return FALSE;
        $r[$key]['arr'] = $this->AddNullData($r[$key]['arr']);
        $r[$key]['arr'] = $this->DeleteUnnecessary($r[$key]['arr']);
        return $r;
    }
    
    protected function AddNullData($ArrData) {
            $date_0 = NULL;
            $user_out = TRUE; # был ли уход
            $r = array();
            @$no_data->MAX_EV_DATETIME = 'нет данных';
            @$no_data->MIN_EV_DATETIME = 'нет данных';

            foreach ($ArrData as $key => $row) {
                $date_control = Date::GetFullDateFromDatetime($row->MAX_EV_DATETIME);

                if($row->TA_TYPE == 1){
                    $date_0 = $date_control; 
                    if($user_out == FALSE){
                       $r[] = $no_data; 
                       $r[] = $row; 
                    }else{
                       $r[] = $row; 
                    }
                    $user_out = FALSE;
                }
                if($row->TA_TYPE == 2){
                    if($date_0 == NULL){
                        $date_0 = $date_control;  
                    }
                    # Если приход был, то должна быть дата
                    if($date_0 != $date_control){
                       $r[] = $no_data; 
                       $r[] = $row; 
                    } else {
                       $r[] = $row;  
                    }
                    $user_out = TRUE;
                }
            }
            return $r;
    }
    
    protected function DeleteUnnecessary($arr) {
        $r = array();
        $date_0 = NULL;
        $i = 1;
        $n = 0;
        foreach ($arr as $key => $LogElement) {
            if($i > 2){$i = 1;}
            if($i == 1){
                if(empty($LogElement->MIN_EV_DATETIME)) {
                   @$r[$n]->MIN_EV_DATETIME = 'нет данных'; 
                } else {
                   @$r[$n]->MIN_EV_DATETIME = $LogElement->MIN_EV_DATETIME; 
                }
            }
            if($i == 2){
                if(empty($LogElement->MAX_EV_DATETIME)) {
                   $r[$n]->MAX_EV_DATETIME = 'нет данных'; 
                } else {
                   if(isset($NextMaxElement)) {
      
                   } else {
                       $r[$n]->MAX_EV_DATETIME = $LogElement->MAX_EV_DATETIME;
                   } 
                   
                }
                $n++;
            }

            
            $i++;        
        } 
        return $r;
    }

    protected $EV_DATE = array();
    protected function AddDateList($AddDate){
        $key = array_search($AddDate,$this->EV_DATE);
        if($key === FALSE)
        {
           $this->EV_DATE[] = $AddDate; 
           return 0;
        } else {
           return $key; 
        }
    }
    
    public function GetDateFromID($ID){
        return $this->EV_DATE[$ID];        
    }

    public function get_user_evlog($data){
        $r = array();
        while ($row = ibase_fetch_object($data)) {
            $r[] = $row;
        }
        return $r;
    }
    
    # Статистика посещений на группу юзеров
    public function get_users_talog($data){
        $r = array();
        while ($row = ibase_fetch_object($data)){
            
            switch ($row->TA_TYPE) {
                case '1': $row->TA_TYPE_NAME = 'Приход'; break;    
                case '2': $row->TA_TYPE_NAME = 'Уход'; break;
            } 

            $r[$row->USER_ID][] = array(
               'EV_DATETIME' => $row->EV_DATETIME,
               'TA_TYPE' => $row->TA_TYPE,
               'TA_TYPE_NAME' => $row->TA_TYPE_NAME
            );
           // $r[] = $row;
        }
        return $r;
    }   
}