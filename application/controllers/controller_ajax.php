<?php
class Controller_Ajax extends Controller
{
    public function __construct()
    {
        $this->model = new Model_Ajax();
        $this->view  = new View();
        $this->db    = new DB();
        print '<meta charset="utf-8">';
    }
    
    public function action_index()
    {
        # Пользователи
        $this->view->generate('view_users_bootstrap.php', 'ajax.php');
    }
    
    public function action_talog($ID)
    {
        $sql = $this->sql_get_UserTaLog($ID);
        $res = $this->db->query($sql);
        $data['USER_TALOG'] = $this->model->get_user_taLog($res);
        $this->view->generate('view_ajax_talog.php', 'ajax.php', $data);
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
}
                                                                                                                                                                                                                 