<?php
if(isset($_POST['q'])) {
  $q = $_POST['q'];  
}  else {
  $q = '';  
} 
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Статистика пользователей</title>

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
if (CONTROLLER == 'lookout') {
    ?>
        <div class="pull-right"><a href="?auth/signout/" class="btn btn-danger">Выход</a></div>
    <?php 
}
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

    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">Поиск</a></li>
      <li><a href="#profile" data-toggle="tab">Подразделения</a></li>
       <li><a href="http://old-spravka.vfp.ru">Старая справка</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="home">
          <div class="clearfix"></div>
          <br>
            <div class="row">
                <form action="/?users/search/" method="POST" enctype="multipart/form-data" role="form">
                <div class="col-lg-11">
                    <input name="q" type="text" placeholder="" value="<?=$q?>" class="form-control">
                </div>
                <div class="col-lg-1">
                    <input name="" type="submit" value="Найти" class="btn btn-sm btn-success btn-block">
                </div>  
                </form> 
            </div>
      </div>
      <div class="tab-pane fade" id="profile">
          <div class="clearfix"></div>
          <br>
          <?php print $data['TREE']; ?> 
      </div>
    </div>
                
       


    </div>
    <?php 
    $inc = 'application/views/'.$content_view;
    if(file_exists($inc)) include_once $inc; 
    ?>   
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
