<?php
class Controller_get_photo extends Controller
{
    function __construct()
    {
        $this->db    = new DB();
    }
    
    function action_index()
    {
         # Пользователи
        $sql = 'SELECT PHOTO, USER_ID FROM USER_PHOTOS WHERE USER_ID = 132 ORDER BY USER_ID ';
        $data = $this->db->query($sql);
        
        while ($row = ibase_fetch_object($data)) {
            $fileName = md5($row->PHOTO);

            $photo = $row->PHOTO;
            $img = imagecreatefromstring($photo);
            header('Content-Type: image/gif');
            imagejpeg($img, NULL, 100);
            imagedestroy($img);

        }
        //$row = DB::query('SELECT * FROM USERS'); , a.POSITION
        //return $row;
        //$data = $this->model->get_data($res);	
        
    }
}
                                                                                                                                                                                                                 