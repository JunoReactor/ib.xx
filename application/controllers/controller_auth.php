<?php
class Controller_Auth extends Controller
{
    public function __construct()
    {
        $this->view  = new View();
        $this->db    = new DB();
        if (Auth::AuthUser() != FALSE) {
            define('CONTROLLER', 'lookout');
            //Utils::Redirect('?users/');  
           // print 'Авторизирован';
        } else {
            define('CONTROLLER', 'users');
            //print 'Не авторизирован';
        }
        //print '<meta charset="utf-8">';
    }
    
    public function action_index()
    {
        $this->view->generate('', 'auth.php');
    }
    
    public function action_signout()
    {
        Auth::SignOut();
        Utils::Redirect('?auth/');
    }
    

}
                                                                                                                                                                                                                 