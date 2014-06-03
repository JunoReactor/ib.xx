<?php
class Controller_Users extends Controller
{
    public function __construct()
    {
        $this->model = new Model_Users();
        $this->view  = new View();
        $this->db    = new DB();
        if (Auth::AuthUser() != FALSE) {
            define('CONTROLLER', 'lookout');
           // print 'Авторизирован';
        } else {
            define('CONTROLLER', 'users');
            //print 'Не авторизирован';
        }
        //print '<meta charset="utf-8">';
    }
    
    public function action_index()
    {
        # Пользователи
        /*$sql = 'SELECT
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
                ORDER BY a.FULL_NAME
                ';
                #WHERE b.USER_ID = 257
                #AND c.STATUS = 1
                #
        //print $sql; 
        $res = $this->db->query($sql);
        //$row = DB::query('SELECT * FROM USERS'); , a.POSITION
        //return $row;
        $data = $this->model->get_data($res);
        */
        $Departments = $this->GetDepartments();
        $data['TREE'] = $this->model->CreateTreeDepartments($Departments, 0);
        $this->view->generate('view_users_bootstrap.php', 'bootstrap.php', $data);
    }
    
    public function action_get($ID)
    {
        # Пользователи
        # AND c.STATUS = 1
        #
        $this->RedirectAuthUser($ID);
        $sql = $this->sql_get_userData($ID);

        $res = $this->db->query($sql);
        $data['USER_INFO'] = $this->model->get_data($res);
        
        $sql = $this->sql_get_UserTaLog($ID);
        $res = $this->db->query($sql);
        //$data['USER_EVLOG'] = $this->model->get_user_evlog($res);
        $data['USER_TALOG'] = $this->model->get_user_taLog($res);
        $Departments = $this->GetDepartments();
        $data['TREE'] = $this->model->CreateTreeDepartments($Departments, 0);
        $this->view->generate('view_users_talog_bootstrap.php', 'bootstrap.php', $data);

    }
    
    public function action_search()
    {
        $Departments = $this->GetDepartments();
        $data['TREE'] = $this->model->CreateTreeDepartments($Departments, 0);
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
            //print $sql;
            $res = $this->db->query($sql);
            $data['TALOG'] = $this->model->get_users_talog($res);
            //print_r($data['TALOG']);
            //$data['TALOG'] = $this->SortTALOG($data['TALOG']);
            $data['TALOG'] = $this->crop_talog($data['TALOG']);
            //print count($data);
            if(count($data) == 0) $this->view->generate('view_users_bootstrap_error.php', 'bootstrap.php', array(1));
            $this->view->generate('view_users_bootstrap.php', 'bootstrap.php', $data); 
        } else {
            $this->view->generate('view_users_bootstrap_error.php', 'bootstrap.php', $err);  
        }
    } 
    
    public function action_departments($ID)
    {
        if($ID == FALSE) return FALSE;  
        $sql = $this->sql_get_userDepartmentsData($ID);
        $res = $this->db->query($sql);
        $data['USERS'] = $this->model->get_data($res);
        //$data['USER_INFO'] = $this->model->get_data($res);
        $Departments = $this->GetDepartments();
        $data['TREE'] = $this->model->CreateTreeDepartments($Departments, 0);
        $this->view->generate('view_users_bootstrap.php', 'bootstrap.php', $data);
    }
    
    protected function RedirectAuthUser($ID = FALSE) 
    {
        if($ID == FALSE) {
            return;
        }
        if (CONTROLLER == 'lookout') {
            Utils::Redirect('?lookout/get/'.$ID);
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
    
    protected function sql_get_userDepartmentsData($ID){
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
                  WHERE a.DEPT_ID = '.$ID.' 
                  ORDER BY a.USER_ID
                  ';//ORDER BY a.FULL_NAME
        return $sql;
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
        # Последние прохождения
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

    protected function GetDepartments(){
        $sql = 'SELECT '
                    . '"NAME" AS DEPT_NAME, '
                    . 'DEPT_ID, '
                    . 'PARENT_ID '
                . 'FROM DEPARTMENTS';
        $res = $this->db->query($sql);
        $r = array();
        while ($row = ibase_fetch_object($res)) {
            $row = Utils::IconvObg('windows-1251', 'utf-8', $row);
            $ParentID = $row->PARENT_ID;
            if($ParentID == NULL){ $ParentID = 0; }
            $r[$ParentID][$row->DEPT_ID] = array(
                'DEPT_NAME' => $row->DEPT_NAME,
                'DEPT_ID'   => $row->DEPT_ID,
                'PARENT_ID' => $ParentID
            );
        }  
        //$tree = $this->CreateTreeDepartments($r, 0);
        //return $tree;        
        return $r;

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
        $sql .= 'ORDER BY a.FULL_NAME';
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
                                                                                                                                                                                                                 