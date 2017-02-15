<?php
require_once  'config.php';
require_once  SHOP_LIB.'ErrorLog.php';

require_once  SHOP_LIB.'mysql.php';
require_once  SHOP_LIB.'Log.php';
require_once  SHOP_INC.'payConnector.ymoney.php';

$pay = payConnector::getInstance();
$pay->Request($_REQUEST);

