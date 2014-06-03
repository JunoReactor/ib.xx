<?php
class Controller_Photo_Loader extends Controller
{
    function __construct()
    {
        $this->model = new Model_Photo_Loader();
        $this->view  = new View();
        $this->db    = new DB();
    }
    
    function action_index()
    {
         # Пользователи
        $sql = 'SELECT PHOTO, USER_ID FROM USER_PHOTOS WHERE USER_ID = 132 ORDER BY USER_ID ';

        //print $sql; 
        $data = $this->db->query($sql);
        
        while ($row = ibase_fetch_object($data)) {
            $fileName = md5($row->PHOTO);
            //$file = fopen($_SERVER["DOCUMENT_ROOT"].'/img/user_photo/'.$fileName.'.jpg',"w+") or die('Ошибка записи в фаил: '.$fileName);
            $file = $_SERVER["DOCUMENT_ROOT"].'/img/user_photo/'.$fileName.'.jpg';
          
            //fputs($file,$row->PHOTO);
            //fclose($file);
            print $row->PHOTO;
            $photo = $row->PHOTO;
            $img = imagecreatefromstring($photo);
           // imagejpeg($photo, $file, 100);
            file_put_contents($file,$photo);
        }
        //$row = DB::query('SELECT * FROM USERS'); , a.POSITION
        //return $row;
        //$data = $this->model->get_data($res);	
        $this->view->generate('view_users.php', 'default.php');
    }
}
                                                                                                                                                                                                                 