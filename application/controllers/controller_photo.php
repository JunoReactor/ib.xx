<?php
class Controller_Photo extends Controller
{
    public function __construct()
    {
        $this->model = new Model_Photo();
        $this->view  = new View();
        $this->db    = new DB();
    }
    
    function action_index()
    {

    }
    
    public function action_loader()
    {
        # Пользователи
        $sql = 'SELECT PHOTO, USER_ID FROM USER_PHOTOS WHERE USER_ID = 132 ORDER BY USER_ID ';
        //$document_root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
        //print $sql; 
        $data = $this->db->query($sql);
        
        while ($row = ibase_fetch_object($data)) {
            $fileName = md5($row->PHOTO);
            $file = fopen(DOCUMENT_ROOT.'/img/user_photo/'.$fileName.'.jpg',"w+") or die('Ошибка записи в фаил: '.$fileName);
            //$file = $_SERVER["DOCUMENT_ROOT"].'/img/user_photo/'.$fileName.'.jpg';
            print $file;
            fputs($file,$row->PHOTO);
            fclose($file);
            //print $row->PHOTO;
            //$photo = $row->PHOTO;
           // $img = imagecreatefromstring($photo);
           // imagejpeg($photo, $file, 100);
            //file_put_contents($file,$photo);
        }
        //$row = DB::query('SELECT * FROM USERS'); , a.POSITION
        //return $row;
        //$data = $this->model->get_data($res);	
        $this->view->generate('view_users.php', 'default.php');
    }
    
    public function action_get($ID)
    {
        $sql = 'SELECT PHOTO FROM USER_PHOTOS WHERE USER_ID = '.$ID.' ORDER BY USER_ID ';
        $result = $this->db->query($sql);
        header('Content-Type: image/jpg');
        //$document_root = '/home/_www/hosting/ib.vfp.ru';
        //$document_root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
        $Photo = $this->GetPhoto($result);
        if($Photo !== FALSE) {
            print $Photo;
        } else {
            print file_get_contents(DOCUMENT_ROOT.'/img/user_icon/zaglushka.png');
            /*$sql = 'SELECT FULL_NAME FROM USERS WHERE USER_ID = '.$ID.' ORDER BY USER_ID';
            $result = $this->db->query($sql);
             while ($row = ibase_fetch_object($result)) {
                Log::Add($row->FULL_NAME);
             }*/
        }
    }
    
    # Подавать ресурс с синонимом PHOTO
    public function GetPhoto($res)
    {
        $data = ibase_fetch_object($res);
        if(empty($data->PHOTO)) return FALSE;
        $blob_data = ibase_blob_info($data->PHOTO);
        $blob_hndl = ibase_blob_open($data->PHOTO);
        return ibase_blob_get($blob_hndl, $blob_data[0]);
    }
}
                                                                                                                                                                                                                 