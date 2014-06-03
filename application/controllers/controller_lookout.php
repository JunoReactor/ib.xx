<?php
class Controller_LookOut extends Controller
{
    public function __construct()
    {
        $this->model = new Model_LookOut();
        $this->view  = new View();
        $this->db    = new DB();
        if (Auth::AuthUser() != FALSE) {
            define('CONTROLLER', 'lookout');
            //print 'Авторизирован';
        } else {
            define('CONTROLLER', 'users');
            Utils::Redirect('?users/');
            //print 'Не авторизирован';
        }
       // print '<meta charset="utf-8">';
        //print ''.$_SERVER["REMOTE_ADDR"].'';
    }
    
    public function action_index()
    {
        $this->view->generate('view_users_lookout.php', 'lookout.php');
    }
    
    public function action_search()
    {
        $err = $this->check_q(@$_POST['q']);
        if($err === FALSE) {
            $q = trim($_POST['q']);
            if($q == '#') {
                $q = '';
            }
            $q = mb_convert_case($q, MB_CASE_UPPER, "UTF-8");
            $sql = $this->sql_search($q);
            $res = $this->db->query($sql);
            $data['USERS'] = $this->model->get_data($res);
            $UsersID = $this->GetUsersID($data['USERS']);
            $sql = $this->sql_search_taLog($UsersID);
            $res = $this->db->query($sql);
            $data['TALOG'] = $this->model->get_users_talog($res);
            //$data['TALOG'] = $this->SortTALOG($data['TALOG']);
            $data['TALOG'] = $this->crop_talog($data['TALOG']);
            if(count($data) == 0) $this->view->generate('view_users_bootstrap_error.php', 'lookout.php', array(1));
            $this->view->generate('view_users_lookout.php', 'lookout.php', $data); 
        } else {
            $this->view->generate('view_users_bootstrap_error.php', 'lookout.php', $err);  
        }
    } 
    
    public function action_get($ID)
    {
        $sql = $this->sql_get_userData($ID);
        $res = $this->db->query($sql);
        $data['USER_INFO'] = $this->model->get_data($res);
        $sql = $this->sql_get_UserTaLog($ID);
        $res = $this->db->query($sql);
        //$data['USER_EVLOG'] = $this->model->get_user_evlog($res);
        $data['USER_TALOG'] = $this->model->get_user_taLog($res);
        if (isset($_POST['TA_TYPE'])) {
            $TA_TYPE = IntVal($_POST['TA_TYPE']); 
            $this->InsertElementTaType($TA_TYPE,$ID);
        } 
        $this->view->generate('view_users_talog_lookout.php', 'lookout.php', $data);
    }
    
    protected function InsertElementTaType($TaType,$UserID)
    {
        if($TaType == 1 || $TaType == 2) {
            $MaxID = $this->GetMaxSnTaType();
            $MaxID++;
            $sql = 'INSERT INTO TALOG (EV_DATETIME,USER_ID,TA_TYPE,SN) VALUES (\''.date("Y-m-d H:i:s").'\',\''.$UserID.'\',\''.$TaType.'\', \''.$MaxID.'\')';
            $res = $this->db->query($sql);
            Utils::Redirect();
            return $res;  
        } else {
            return FALSE;
        }
    }
    
    protected function GetMaxSnTaType() {
       $sql = 'SELECT MAX(SN) AS SN FROM TALOG';
       $res = $this->db->query($sql);
       while ($row = ibase_fetch_object($res)) {
           return $row->SN;
       } 
    }


    protected function GetUsersID($data)
    {
        $r = array();
        foreach ($data as $user) {
            $r[] = $user['ID'];
        }
        return $r;
    }
    
    protected function crop_talog($data)
    {
       $UsersData = array();
       if(isset($data)) {
            foreach ($data as $key1 => $users) {
                foreach ($users as $key2 => $talog) {
                    $DateKey = Date::CropDate($data[$key1][$key2]['EV_DATETIME']);
                    $Type = $data[$key1][$key2]['TA_TYPE'];
                    $UsersData[$key1][$DateKey][$Type][] = array(
                        'TYPE_NAME' => $data[$key1][$key2]['TA_TYPE_NAME'],
                        'DATETIME'  => $data[$key1][$key2]['EV_DATETIME']
                    );     
                } 
            } 
       }
       return $data; 
    }
    
    protected function check_q($q)
    {
        $err = array();
        if($q == '#')   return FALSE;
        if($q === FALSE)   $err[] = 1;
        if(empty($q))      $err[] = 2;
        if(mb_strlen($q, "UTF-8") < 1) $err[] = 3;
        if(!empty($err)) return $err;
        return FALSE;
    }
    
    protected function sql_get_userData($ID){
      if($ID == FALSE) return FALSE;     
        $sql = 'SELECT
                a.FULL_NAME,
                a.DEPT_ID,
                a.USER_ID,
                a.TA_MARK,
                a.BIRTHDAY,
                a.COMMENT,
                a.TA_MARK,
                a.ADDRESS,  
                a.PHONE, a.PHONE2, a.MOBIL_PHONE,
                c."NAME" AS AREA_NAME,
                b."WHEN" AS B_WHEN,
                d."NAME" AS DEP_NAME,
                i."NAME" AS POS_NAME,
                j.PHOTO
                FROM USERS a
                LEFT JOIN  USR_LOCAT b ON a.USER_ID = b.USER_ID
                LEFT JOIN AREAS c ON b.AREA_ID = c.AREA_ID
                LEFT JOIN DEPARTMENTS d ON a.DEPT_ID = d.DEPT_ID
                LEFT JOIN USER_POSITIONS i ON a.POS_CODE = i.POS_CODE
                LEFT JOIN USER_PHOTOS j ON j.USER_ID = a.USER_ID
                WHERE a.USER_ID = '.$ID.' 
                ORDER BY a.USER_ID
                ';//ORDER BY a.FULL_NAME
        return $sql;
    }
    
    protected function sql_get_UserEvLog($ID){
        $sql = 'SELECT a.EV_DATETIME, b.MSG_TEXT 
                FROM EVLOG a 
                LEFT JOIN MESSAGES b ON a.MSG_ID = b.MSG_ID  
                WHERE a.USER_ID = '.$ID.'
                ORDER BY a.EV_DATETIME
                ';
        return $sql;
    }
    
    protected function sql_get_UserTaLog($ID){
        $maxDays = 30;
        for ($i = 0; $i < $maxDays; $i++) {
            $arr_sql[] = 'SELECT
                                MAX(a.EV_DATETIME) AS MAX_EV_DATETIME,
                                MIN(a.EV_DATETIME) AS MIN_EV_DATETIME,
                                a.TA_TYPE
                            FROM TALOG a
                            WHERE a.USER_ID = '.$ID.'
                            AND a.EV_DATETIME > \''.Date::GetPeriod($i,'D').'\'    
                            AND a.EV_DATETIME < \''.Date::GetPeriod(($i-1),'D').'\'  
                            GROUP BY a.TA_TYPE'; 
        }
        $sql = implode(' UNION ALL ', $arr_sql);
        // print $sql;
        return $sql;
    }
    
    protected function sql_search_taLog($UserID = array()){

        $sql = 'SELECT
                   a.USER_ID, 
                   MAX(a.EV_DATETIME) AS EV_DATETIME,
                   a.TA_TYPE
               FROM TALOG a, USERS b
               WHERE a.USER_ID = b.USER_ID
               AND a.EV_DATETIME > \''.Date::GetYesterday().' 00:00:00\'';
        /*if(!empty($UserID)) {
            foreach ($UserID as $ID) {
               $sql .= ' AND a.USER_ID = '.$ID.' '; 
            } 
        }  */  
        $sql .= 'GROUP BY a.TA_TYPE, a.USER_ID, a.EV_DATETIME
               ORDER BY a.EV_DATETIME DESC
               ';
        return $sql;
    }
    
    protected function sql_search($q){
        $q = iconv('utf-8', 'windows-1251', $q);
        $sql = 'SELECT
                a.*,
                c."NAME" AS AREA_NAME,
                b."WHEN" AS B_WHEN,
                d."NAME" AS DEP_NAME,
                i."NAME" AS POS_NAME,
                j.PHOTO 
              FROM USERS a
                LEFT JOIN USR_LOCAT b ON a.USER_ID = b.USER_ID
                LEFT JOIN AREAS c ON b.AREA_ID = c.AREA_ID
                LEFT JOIN DEPARTMENTS d ON a.DEPT_ID = d.DEPT_ID
                LEFT JOIN USER_POSITIONS i ON a.POS_CODE = i.POS_CODE
                LEFT JOIN USER_PHOTOS j ON j.USER_ID = a.USER_ID
                ';

        if ($q != FALSE) {
            $sql .= 'WHERE UPPER(a.FULL_NAME) LIKE \'%'.$q.'%\'
                    OR UPPER(c."NAME") LIKE \'%'.$q.'%\'
                    OR UPPER(d."NAME") LIKE \'%'.$q.'%\'
                    OR UPPER(i."NAME") LIKE \'%'.$q.'%\'    
                    OR a.PHONE LIKE \'%'.$q.'%\'
                    OR a.PHONE2 LIKE \'%'.$q.'%\'
                    OR a.MOBIL_PHONE LIKE \'%'.$q.'%\'
                    ';  
        }

        //$sql .= 'ORDER BY a.FULL_NAME';USER_ID
        $sql .= 'ORDER BY a.FULL_NAME';
        //print_r($sql);
        return $sql;
    }
    
    protected function sql_search_test($q){
        $sql = 'SELECT
                *
              FROM USERS 
              ROWS 1000
               ';
        //print $sql;
        return $sql;
        
    }
}
                                                                                                                                                                                                                 