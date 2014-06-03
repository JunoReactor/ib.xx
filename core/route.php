<?php
class Route {

    public static function start() {
        $controller_name = 'Users';
        $action_name = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']);
        $routes[1] = str_replace(array('?'),'', $routes[1]);
        
        if (!empty($routes[1])) {
            $controller_name = $routes[1];
        }

        if (!empty($routes[2])) {
            $action_name = $routes[2];
        }

        $action_param = FALSE;
        if (!empty($routes[3])) {
            $action_param = $routes[3];
        }
        
        if($controller_name == 'index.php'){
           //header("Location: http://".$_SERVER['HTTP_HOST'];
           $controller_name = 'Users';
        }

        $model_name = 'model_' . $controller_name;
        $controller_name = 'controller_' . $controller_name;
        $action_name = 'action_' . $action_name;

        $model_file = strtolower($model_name) . '.php';
        $model_path = "application/models/" . $model_file;
        if (file_exists($model_path)) {
            include_once "application/models/" . $model_file;
        }

        $controller_file = strtolower($controller_name) . '.php';
        $controller_path = "application/controllers/" . $controller_file;
        if (file_exists($controller_path)) {
            include_once "application/controllers/" . $controller_file;
        } else {
            /*
              правильно было бы кинуть здесь исключение,
              но для упрощения сразу сделаем редирект на страницу 404
             */
            Route::ErrorPage404();
        }

        // создаем контроллер
        /* if(class_exists($controller_name)) {
          print '1111';
          }else{
          print '2222';
          } */
        $controller = new $controller_name;
        $action = $action_name;

        if (method_exists($controller, $action)) {
            // вызываем действие контроллера
            
            $controller->$action($action_param);
        } else {
            // здесь также разумнее было бы кинуть исключение
            Route::ErrorPage404();
        }
    }

    public static function ErrorPage404() {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:' . $host . '?404');
    }

}
