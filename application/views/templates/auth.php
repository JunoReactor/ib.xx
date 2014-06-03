<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Авторизация :: Статистика пользователей</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/tpl_style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
    <!--<a href="/">главная</a> / <a href="?users/">пользователи</a>--> 
    <!--?photo/get/1 
    clearfix
    -->

   <div class="page-header">
<?php
if($_SERVER['QUERY_STRING'] == '') {
?>
    <div class="row text-center main">
        <a href="/"><img src="/img/vfp_logo.png" alt="ВФП"></a>
    </div>
<?php
}  else {
?>
    <div class="row text-center">
        <a href="/"><img src="/img/vfp_logo.png" alt="ВФП" width="160"></a>
    </div>
<?php
} 
?>
    <!--<p class="text-right"><a href="http://old-spravka.vfp.ru">Старая справка</a></p>-->
       
    <form role="form" action="/?auth/" method="POST" enctype="multipart/form-data">
    <!-- Nav tabs -->
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="form-group">
            <input type="text" name="login" class="form-control" id="exampleInputEmail1" placeholder="Логин" value="<?=@$_POST['login']?>">
        </div>
        <div class="form-group">
          <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Пароль">
        </div>  
      </div>
    </div>
    
    <di v class="row">
      <div class="col-md-4 col-md-offset-4"> 
        <button type="submit" class="btn btn-success">Авторизация</button>
      <?php
            if(isset($_COOKIE['LOGIN'])) {
                print '<a href="?auth/signout/" class="btn btn-danger">Выход</a>';
            }
      ?></div>
    </div>
    </form>

    </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="js/bootstrap/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap/bootstrap-tooltip.js"></script>
    <script type="text/javascript" src="/js/bootstrap/bootstrap-popover.js"></script>
    <script type="text/javascript" src="/js/progect_script.js"></script>
  </body>
</html>
