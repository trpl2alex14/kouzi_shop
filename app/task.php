<?php
require_once  'config.php';
require_once  SHOP_LIB.'ErrorLog.php';

require_once  SHOP_LIB.'mysql.php';
require_once  SHOP_LIB.'Log.php';
require_once  SHOP_INC.'function.php';
require_once  SHOP_INC.'CRMconnector.bx24.php';
require_once  SHOP_INC.'actionBase.php';

$log = new shopLog();
$log->info('Start task: '.get_reqest('sendreq'));

if(get_reqest('sendreq')=='addDeal'){
    $orderid=0;
    $crm = CRMconnector::getInstance();
    $db = ShopDB::getInstance();  
    $aBase = new actionBase();
    $res = $db->run("SELECT * FROM taskcreatedeal WHERE status=0");      
    while(!$db->isError() && $row = $res->fetch_assoc()){
        $db->run("UPDATE taskcreatedeal SET status=1 WHERE id=".$row['id']);        
        $products  = unserialize( base64_decode($row['products']));
        $orderinfo = unserialize( base64_decode($row['orderinfo']));      
        $crm->setArticle($products);
        $crm->setOrder($orderinfo);
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



