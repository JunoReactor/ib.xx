<?php
class DB {
    protected $conn_id;
    
    public function __construct()
    {
       $this->connect(); 
    }
    
    public function __destruct()
    {
       ibase_close($this->conn_id); 
       unset($this->conn_id);
    }
    
    protected function connect()
    {
        $database   = "172.16.0.5:C:/database/TR.IB";
        $user       = "SYSDBA";
        $password   = "root312755";
        //
        $this->conn_id = ibase_connect($database, $user, $password);
        if(!$this->conn_id){
                die("Не удалось подключиться к базе, обратитесь к систеному администратору.");
        }
        //ibase_query($this->conn_id, 'SET NAMES UTF8');, 'UTF8' , 'WIN1251'
        //ibase_close($db); ;
    }
    
    public function query($q)
    {
        //$stmt = 'SELECT * FROM USERS';
        $res = ibase_query($this->conn_id, $q);
        //echo "*".ibase_errmsg()."*<br />";
        return $res;
        /*while ($row = ibase_fetch_object($res))
        {
            echo $row->USER_ID.': '
                    .$row->NAME.' '
                    .$row->FAMILY.' '
                    .$row->FNAME.' '
                    .$row->POSITION.' '
                    .$row->PHONE.' '
                    .$row->PHONE2.' '
                    .$row->MOBIL_PHONE.' '
                    //.$row->ADRESS.' '
                    //.$row->FAMILY.' '
                    //.$row->FAMILY.' '
                    ."<br />";
        } */
       // ibase_free_result($sth); 
    }
    
}
