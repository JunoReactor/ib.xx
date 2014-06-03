<?php
class Model_LookOut extends Model
{
    var $ClassName = 'even';
    var $Status = FALSE;
    var $DocumentTitle = 'Статистика пользователей';
    
    public function get_data($data = FALSE){	
        $r = array();
        $td_class = ' class = "even"';
        while (@$row = ibase_fetch_object($data)) {
            $row = Utils::IconvObg('windows-1251', 'utf-8', $row);
            $Status = $this->GetUserStatus($row->AREA_NAME);
            $td_class = ' class = "'.$this->GetTdClassName().'"'; 
            if ($this->Status) {
                $StatusImgCode = 'user_assept';
            } else {
                $StatusImgCode = 'user_close';
            }
            //$row->PHONE.'<br>'.$row->PHONE2.'<br>'.$row->MOBIL_PHONE;
            $Phone = $this->GetPhone(array(
              $row->PHONE,
              $row->PHONE2,
              $row->MOBIL_PHONE  
            ));
            if($Phone === FALSE) $Phone = '<img src="/img/minus-icon.png" title="Данные отсутствуют">';
            $ex_when = explode(' ', $row->B_WHEN);
            
            $r[] = array(
               'Class' => $td_class, 
               'StatusImgCode' => $StatusImgCode,
               'Status' => $Status,
               'ID' => $row->USER_ID,
               'FullName' =>  $row->FULL_NAME,    
               'Comment' => strip_tags($row->COMMENT),
               'Photo' => 'Photo',
               'Position' => $row->POS_NAME,
               'Dept' => $row->DEP_NAME,
               'Email' => $row->ADDRESS,
               'When' => $row->B_WHEN,
               'TA_Mark' => $row->TA_MARK,
               'Data' => Date::Russian($ex_when[0]),
               'Time' => $ex_when[1],
               'Area' => $row->AREA_NAME,
               'Phone' => $Phone 
            );
        }
        return $r;
    }

    # Статистика посещений на отдельного юзера
    public function get_user_taLog($data){
        $r = array();
        while ($row = ibase_fetch_object($data)) {
            $row = Utils::IconvObg('windows-1251', 'utf-8', $row);
            $date = date_parse($row->MAX_EV_DATETIME);
            //
            
            $key = $this->AddDateList($date['month']);
            switch ($row->TA_TYPE) {
                case '1': $row->TA_TYPE_NAME = 'Приход'; break;    
                case '2': $row->TA_TYPE_NAME = 'Уход'; break;
            } 
            $r[$key]['arr'][] = $row;
            $r[$key]['Month'] = DATE::GetMonthRussian($this->GetDateFromID($key));
            
        }
        if(!isset($key)) return FALSE;

        foreach ($r as $k => $v) {
            $r[$k]['arr'] = $this->AddNullData($r[$k]['arr']);
            $r[$k]['arr'] = $this->DeleteUnnecessary($r[$k]['arr']);
        }

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
        $no_data = 'нет данных';
        $i = 1;
        $n = 0;
        foreach ($arr as $key => $LogElement) {
            if($i > 2){$i = 1;}
            $Day = $this->GetDayNumber($LogElement);
            if($i == 1){
                if(empty($LogElement->MIN_EV_DATETIME)) {
                   @$r[$n]->MIN_EV_DATETIME = $no_data; 
                } else {
                    $time = Date::GetTimeFromDate($LogElement->MIN_EV_DATETIME);
                    if($time == FALSE) {
                        @$r[$n]->MIN_TIME = $no_data;   
                    } else {
                        @$r[$n]->MIN_TIME = $time;   
                    }
                    if ($Day != FALSE) {
                       @$r[$n]->DAY = $Day;  
                    }
                }
            }
            if($i == 2){
                if(empty($LogElement->MAX_EV_DATETIME)) {
                   @$r[$n]->MAX_EV_DATETIME = $no_data; 
                } else {
                   if(isset($NextMaxElement)) {
                        ###
                   } else {
                        $time = Date::GetTimeFromDate($LogElement->MAX_EV_DATETIME);
                        if($time == FALSE) {
                            @$r[$n]->MAX_TIME = $no_data;   
                        } else {
                            $c_date1 = Date::GetTodayDateTime();
                            $c_date2 = $LogElement->MAX_EV_DATETIME;
                            
                            if(Date::Compare($c_date1, $c_date2) == FALSE){
                              @$r[$n]->MAX_TIME = $no_data;   
                            } else {
                              @$r[$n]->MAX_TIME = $time;   
                            }
                              
                        }
                        if ($Day != FALSE) {
                           @$r[$n]->DAY = $Day;  
                        }
                        if ($Day != FALSE) {
                           @$r[$n]->DAY = $Day;  
                        }
                   } 
                }
                $n++;
            }
            $i++;        
        } 
        return $r;
    }
    
    protected function GetDayNumber($LogElement){
        $Day = Date::GetDayFromDate($LogElement->MAX_EV_DATETIME);
        if(empty($Day)){
            $Day = Date::GetDayFromDate($LogElement->MIN_EV_DATETIME); 
        }
        $Day = Utils::NumberDoubleValue($Day);
        return $Day;
    }

    protected $EV_DATE = array();
    protected function AddDateList($AddDate){
        $key = array_search($AddDate,$this->EV_DATE);
        if($key === FALSE)
        {
           $this->EV_DATE[] = $AddDate; 
           $key = array_search($AddDate,$this->EV_DATE);
           return $key;
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
        }
        return $r;
    }   
    
    public function GetTdClassName(){
        if($this->ClassName === 'even') {
           $this->ClassName = 'odd'; 
        } else {
           $this->ClassName = 'even'; 
        } 
        return $this->ClassName;
    }
    
    public function SetTdClassName($ClassName){
       $this->ClassName = $ClassName; 
    }
    
    private function GetUserStatus($Area){
            if($Area === 'Улица') {
                $this->SetTdClassName('red');
                $Status = 'Отсутствует';
                $this->Status = FALSE;
            } else {
                $Status = 'Присутствует';
                $this->Status = TRUE;
            }
            return $Status;
    }
    
    private function GetPhone($Phones = array()){
        $Phones = array_filter($Phones,function($var){return !empty($var);});
        if(count($Phones) == 0) return FALSE;
        $r = implode('<br >',$Phones);
        return $r;
    }
}