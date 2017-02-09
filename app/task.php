<?php
define('HOST_DEV', $_SERVER['REMOTE_ADDR'] == '127.0.0.1');
define('IN_DEV', (HOST_DEV) ? 'On':'Off');
error_reporting(-1);
ini_set('display_errors', IN_DEV);

require_once  'config.php';
require_once  SHOP_LIB.'mysql.php';
require_once  SHOP_LIB.'Log.php';
require_once  SHOP_LIB.'ErrorLog.php';
require_once  SHOP_INC.'function.php';
require_once  SHOP_INC.'CRMconnector.bx24.php';
require_once  SHOP_INC.'actionBase.php';

$errorClass   = new ErrorLog(ERROR_LOG, 1, (HOST_DEV?1:0), (HOST_DEV?0:1), ERROR_MAIL);
$errorMethod  = 'handler';
set_error_handler(array($errorClass, $errorMethod));


if(get_reqest('sendreq')=='addDeal'){
    $orderid=0;
    $crm = CRMconnector::getInstance();
    $db = ShopDB::getInstance();  
    $aBase = new actionBase();
    $res = $db->run("SELECT * FROM taskcreatedeal WHERE status=0");      
    while(!$db->isError() && $row = $res->fetch_assoc()){
        $db->run("UPDATE taskcreatedeal SET status=1 WHERE id=".$row['id']);        
        $crm->setArticle(json_decode($row['products'],true));
        $crm->setOrder(json_decode($row['orderinfo'],true));
        $orderid = (int)$row['order_id'];
        $id = $crm->createDeal($orderid,$row['comment']);
        if($id){
            $db->run("INSERT INTO `ordercrm`(`order_id`, `crm_id`) VALUES (".$orderid.",".$id.")");
            $aBase->setOrderStatus($orderid,STATUS_ORDER_SEND);
        }else{
            $db->run("UPDATE taskcreatedeal SET status=0 WHERE id=".$row['id']);        
            $aBase->setOrderStatus($orderid,STATUS_ORDER_ERROR);
        }
    }    
}



