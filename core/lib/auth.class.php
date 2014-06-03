<?php

class Auth {
    
    public function __construct()
    {
        
    }
    
    public static function AuthUser()
    {
        if(isset($_POST['login'],$_POST['password'])) 
        {
            $login    = filter_input(INPUT_POST, 'login');
            $password = filter_input(INPUT_POST, 'password');
            return self::PostInpitHandler($login, $password);
        } else {
            return self::CookieInpitHandler();
        }
        return FALSE;
    }
    
    protected static function CookieInpitHandler()
    {
        if (isset($_COOKIE['LOGIN'],$_COOKIE['PASSWORD'])) {
            $login    = trim($_COOKIE['LOGIN']);
            $password = trim($_COOKIE['PASSWORD']);
            if(self::PasswordVerification($login, $password) != FALSE) {
                return TRUE; 
            }
        }
        return FALSE;
    }
    
    protected static function PostInpitHandler($login, $password)
    {
        if(self::PasswordVerification($login, $password) != FALSE) {
            if (!self::SetUserCookie($login, $password)){
                return FALSE;
            } else {
                Utils::Redirect('?users/');  
                return TRUE;
            } 
        } else {
            return FALSE;
        } 
    }
    
    protected static function SetUserCookie($login, $password){
        $count = 0;
        //, $_SERVER['SERVER_NAME']
        if (setcookie('LOGIN', $login, FALSE, "/")){
            $count++;
        }
        if (setcookie('PASSWORD', $password, FALSE, "/")){
            $count++;
        }
        if ($count == 2) {
            return TRUE;
        } else {
            print 'Сookie должны быть включены!';
        }
        return FALSE;
    }
    
    public static function SignOut()
    {
        if (setcookie('LOGIN', FALSE, FALSE, FALSE, $_SERVER['SERVER_NAME'])){
            
        }
        if (setcookie('PASSWORD', FALSE, FALSE, FALSE, $_SERVER['SERVER_NAME'])){
        
        }
    }
    
    protected static function PasswordVerification($login, $password)
    {
        # Ключи: 'Логин' => 'Пароль' 
        $Users = array(
            'shadow' => 'kostya12',        
            'Sec1' => '3561',
            'Sec2' => '0628',
            'Sec3' => '2778',
            'Sec4' => '3064',
            'Sec5' => '1588',
            'Sec6' => '9251'
        );

        foreach ($Users as $l => $p) {
            if($l == $login){
              if($p == $password){
                 return TRUE; 
              }  
            }
        } 
        return FALSE;
    }
    
    public static function Prr()
    {
        
    }
}
