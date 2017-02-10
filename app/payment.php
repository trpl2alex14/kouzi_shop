<?php
define('HOST_DEV', $_SERVER['REMOTE_ADDR'] == '127.0.0.1');
define('IN_DEV', (HOST_DEV) ? 'On':'Off');
error_reporting(-1);
ini_set('display_errors', IN_DEV);

require_once  'config.php';
require_once  SHOP_LIB.'mysql.php';
require_once  SHOP_LIB.'Log.php';
require_once  SHOP_INC.'payConnector.ymoney.php';
require_once  SHOP_LIB.'ErrorLog.php';

$errorClass   = new ErrorLog(ERROR_LOG, 1, (HOST_DEV?1:0), (HOST_DEV?0:1), ERROR_MAIL);
$errorMethod  = 'handler';
set_error_handler(array($errorClass, $errorMethod));


$pay = payConnector::getInstance();
$pay->Request($_REQUEST);

