<?php

/// DB config
define('SHOP_DB_HOST',"localhost");
define('SHOP_DB_NAME',"demo_shop");
define('SHOP_DB_USER',"demouser");
define('SHOP_DB_PASS',"4O5g2U9w");

///dir shop
define('SHOP_DIR',__DIR__."/");
define('SHOP_LIB',SHOP_DIR."lib/");
define('SHOP_TEMPLATE',SHOP_DIR."template/");
define('SHOP_INC',SHOP_DIR."include/");

define('APP_SERVER', 'https://kouzi.ru/');
define('APP_URL_FOLDER', 'addon/shopdemo/');
define('TASK_SCRIPT_URL',APP_SERVER.APP_URL_FOLDER.'task.php?sendreq=addDeal');

/// log files
define('ERROR_LOG','log/error.log');
define('ERROR_MAIL','av@itentaro.ru');
define('INFO_MAIL','av@itentaro.ru');
define('ALL_LOG','log/alllog.log');

//user cookies id
define('ID_CART',"ID_CART");  

////crm config BX24
define('SHOP_CRM', 'kouzi.bitrix24.ru');
define("CLIENT_ID", "local.589c56fc122287.42559076");
define("CLIENT_SECRET", "hMmcQc58zAA7Vwsq1b4eAKZlnFc2UQGxwS14pzBqzM7oo83EAb");
define('LIB_BX_FOLDER', 'lib/BX24class/');
define('APP_FOLDER', SHOP_DIR.LIB_BX_FOLDER);
define('REDIRECT_URI', APP_SERVER.LIB_BX_FOLDER);
define('SCOPE', 'log,user,department,sonet_group');
define('PROTOCOL', 'https://');
define('TOKEN_FILE',APP_FOLDER.'key.json');

///CRM status
define('DEAL_STAT', 2);   //create Deal 
define('LOGISTIC_ID' ,1); //id articul deliveri crm
define('TAX_ID' ,2); //id articul tax crm
define('TAX_SIZE' ,0.05); //% tax
$NOTAX_CITY = array('Челябинск'); //city no tax

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
define('SHOP_URL',APP_SERVER.'shop');

define('USER_ERROR_MSG',"Server Error 500");


