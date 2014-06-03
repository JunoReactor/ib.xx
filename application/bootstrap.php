<?php

//define('DOCUMENT_ROOT', '/home/_www/hosting/ib.vfp.ru');
define('DOCUMENT_ROOT', $_SERVER["DOCUMENT_ROOT"]);

require_once 'core/lib/db.class.php';
require_once 'core/lib/date.class.php';
require_once 'core/lib/log.class.php';
require_once 'core/lib/utils.class.php';
require_once 'core/lib/auth.class.php';
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/route.php';

Route::start();