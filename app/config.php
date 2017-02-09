<?php

/// DB config
define('SHOP_DB_HOST',"localhost");
define('SHOP_DB_NAME',"kouzishop");
define('SHOP_DB_USER',"root");
define('SHOP_DB_PASS',"");

///dir shop
define('SHOP_DIR',"");
define('SHOP_LIB',SHOP_DIR."lib/");
define('SHOP_TEMPLATE',SHOP_DIR."template/");
define('SHOP_INC',SHOP_DIR."include/");

define('APP_SERVER', 'http://127.0.0.1/');
define('TASK_SCRIPT_URL',APP_SERVER.'task.php?sendreq=addDeal');

/// log files
define('ERROR_LOG','log/error.log');
define('ERROR_MAIL','av@itentaro.ru');
define('ALL_LOG','log/alllog.log');

//user cookies id
define('ID_CART',"ID_CART");  

////crm config BX24
define('SHOP_CRM', 'kouzi.bitrix24.ru');
define("CLIENT_ID", "local.58985b120280b6.48237630");
define("CLIENT_SECRET", "ABciendQvnfWJfidRMhB2YSrNj2RDqjAMobU3FWA7eAxI7HO7L");
define('APP_FOLDER', 'lib/BX24class/');
define('REDIRECT_URI', APP_SERVER.APP_FOLDER);
define('SCOPE', 'log,user,department,sonet_group');
define('PROTOCOL', 'https://');

///CRM status
define('DEAL_STAT', 2);   //create Deal 
define('LOGISTIC_ID' ,1); //id articul deliveri crm

define('STATUS_ORDER_INIT',0);
define('STATUS_ORDER_PROCESS',1);
define('STATUS_ORDER_ERROR',2);
define('STATUS_ORDER_SEND',3);
define('STATUS_ORDER_PAY',4);

// Yndex money config
define('SHOP_YDURL','https://demomoney.yandex.ru/eshop.xml');
define('SHOP_KEY_WORD','GGUx6Wyh4zLa');
define('SHOP_ID',120256);
define('SHOP_SCID',548743);


