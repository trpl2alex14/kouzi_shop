<?php

/// DB config
define('SHOP_DB_HOST',"localhost");
define('SHOP_DB_NAME',"demo_shop");                                    ////!
define('SHOP_DB_USER',"demouser");                                    ////!
define('SHOP_DB_PASS',"");                                    ////!

///dir shop
define('SHOP_DIR',__DIR__."/");
define('SHOP_LIB',SHOP_DIR."lib/");
define('SHOP_TEMPLATE',SHOP_DIR."template/");
define('SHOP_INC',SHOP_DIR."include/");

define('APP_SERVER', 'https://HOST/');                                    ////!
define('APP_URL_FOLDER', 'addon/shopdemo/');
define('TASK_SCRIPT_URL',APP_SERVER.APP_URL_FOLDER.'task.php?sendreq=addDeal');

/// log files
define('ERROR_LOG','log/error.log');
define('ERROR_MAIL','mail@mail');                                    ////!
define('INFO_MAIL','mail@mail');                                    ////!
define('ALL_LOG','log/alllog.log');

//user cookies id
define('ID_CART',"ID_CART");  

////crm config BX24
define('SHOP_CRM', 'kouzi.bitrix24.ru');
define("CLIENT_ID", "ID");                                    ////!
define("CLIENT_SECRET", "SECRET");                                    ////!
define('LIB_BX_FOLDER', 'lib/BX24class/');
define('APP_FOLDER', SHOP_DIR.LIB_BX_FOLDER);
define('REDIRECT_URI', APP_SERVER.APP_URL_FOLDER.LIB_BX_FOLDER);
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
define('SHOP_KEY_WORD','PASS');                                   /////! 
define('SHOP_ID',1);                                              /////! 
define('SHOP_SCID',1);                                            /////!
define('SHOP_URL',APP_SERVER.'shop');

define('USER_ERROR_MSG',"Server Error 500");


