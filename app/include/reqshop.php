<?php
require_once  'config.php';
require_once  SHOP_LIB.'mysql.php';
require_once  SHOP_INC.'CRMconnector.bx24.php';

class ReqShop{
    private $cid;
    private $status;
    protected static $_instance;
    
    public static function getInstance($cid){
        if( self::$_instance === NULL ) {
            self::$_instance = new self($cid);
        }
        return self::$_instance;
    }
    
    protected function __construct($cid){ 
        $this->cid = $cid;
        $this->setStatus(false);
        $db = ShopDB::getInstance();                
        $db->run("SELECT * FROM clients WHERE id=".$cid);            
        if($db->isError()){
            trigger_error('Клиент ID:'.$cid. ' не найден в Базе');
            return;
        }
    }
    
    public function setStatus($type){
        if($type){
            $this->status = 'success';
        }else {
            $this->status = 'error';
        }
    }    

    public function getStatus(){
        return $this->status;    
    }   
    
    
    public function createOrderInfo($order){
        $db = ShopDB::getInstance();
        $sqlr =  "INSERT INTO `ordersinfo`(`type`, `fname`, `lname`, `pname`, `phone`, `email`, `cname`, `inn`, `companyname`, `cphone`, `cemail`,"
                ." `city`, `address`, `comment`, `logistic`, `payment`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";        
        $id_ordersinfo = 0;
        
        if($order){
            if($stmt = $db->getDB()->prepare($sqlr)){
                $stmt->bind_param("isssssssssssssii",
                        $order['type'],
                        $order['fname'],
                        $order['lname'],
                        $order['pname'],
                        $order['phone'],
                        $order['email'],
                        $order['cname'],
                        $order['inn'],
                        $order['companyname'],
                        $order['cphone'],
                        $order['cemail'],
                        $order['city'],
                        $order['address'],
                        $order['comment'],
                        $order['logistic'],
                        $order['payment']);
                $stmt->execute();
                $id_ordersinfo =  $stmt->insert_id;
                $stmt->close();
            }else{
                trigger_error('Клиент ID:'.$this->cid. ' Order info не добавленно');
            }                                   
        }else{                    
            $res = $db->run("SELECT orders.id_info FROM orders,ordersinfo WHERE ordersinfo.id=orders.id_info AND orders.status = 3 AND id_client=".$this->cid." order by orders.date DESC LIMIT 1"); 
            if(!$db->isError() && $row = $res->fetch_assoc()){
                $db->run("INSERT INTO `ordersinfo`(`type`, `fname`, `lname`, `pname`, `phone`, `email`, `cname`, `inn`, `companyname`, `cphone`, `cemail`, `city`, `address`, `comment`, `logistic`, `payment`, `date`)"
                       . " SELECT `type`, `fname`, `lname`, `pname`, `phone`, `email`, `cname`, `inn`, `companyname`, `cphone`, `cemail`, `city`, `address`, `comment`, `logistic`, `payment`, NOW() FROM ordersinfo WHERE ordersinfo.id=".$row['id_info']); 
                $id_ordersinfo = $db->getDB()->insert_id;       
            }                
        }
        return $id_ordersinfo;
    }
    
    public function updateOrderInfo($id,$order){
        $db = ShopDB::getInstance();
        $sqlr =  "UPDATE `ordersinfo` SET `type`=?, `fname`=?, `lname`=?, `pname`=?, `phone`=?, `email`=?, `cname`=?, `inn`=?, `companyname`=?, `cphone`=?, `cemail`=?,"
                ." `city`=?, `address`=?, `comment`=?, `logistic`=?, `payment`=?, `date`=NOW() WHERE id=?";                
                
        if($stmt = $db->getDB()->prepare($sqlr)){
            $stmt->bind_param("isssssssssssssiii",
                    $order['type'],
                    $order['fname'],
                    $order['lname'],
                    $order['pname'],
                    $order['phone'],
                    $order['email'],
                    $order['cname'],
                    $order['inn'],
                    $order['companyname'],
                    $order['cphone'],
                    $order['cemail'],
                    $order['city'],
                    $order['address'],
                    $order['comment'],
                    $order['logistic'],
                    $order['payment'],
                    $id);
            $stmt->execute();            
            $stmt->close();
        }else{
            trigger_error('Order ID:'.$id. ' не обнавлен');
        }                                           
    }
    
    public function getLastOrder(){
        $db = ShopDB::getInstance();
        $res = $db->run("SELECT id FROM orders WHERE orders.status IN (0,1,2) AND id_client=".$this->cid." order by orders.date DESC LIMIT 1");            
        if(!$db->isError() && $row = $res->fetch_assoc()){
            return $row['id'];
        }else{
            return 0;
        }
    }
    
    public function createOrder($order){
        $db = ShopDB::getInstance();
        $id_ordersinfo = $this->createOrderInfo($order);
        $db->run("INSERT INTO `orders`(`id_client`, `id_info`, `status`, `date`) VALUES (".$this->cid.", ".$id_ordersinfo.", 0, NOW())");
        return $db->getDB()->insert_id;                    
    }
           
    public function updateOrder($id,$order){
        $db = ShopDB::getInstance();
        $res = $db->run("SELECT id_info FROM orders WHERE id=".$id);            
        if(!$db->isError() && $row = $res->fetch_assoc()){
            if($row['id_info']==0){
                $id_info = $this->createOrderInfo($order);
                $db->run("UPDATE `orders` SET `id_info`=".$id_info." WHERE id=".$id);            
            }else{
                $this->updateOrderInfo($row['id_info'],$order);
            }
        }                
    }    
    
    public function setOrderStatus($id,$status){
        $db = ShopDB::getInstance();
        $db->run("UPDATE `orders` SET `status`=".$status." WHERE status != 3 AND id=".$id);            
    }    
    
    public function sendArticle($article,$order = NULL){
        $db = ShopDB::getInstance();                        
        $id_order = $this->getLastOrder();
        if($id_order > 0 && $order){
            $this->updateOrder($id_order,$order);
        }else if($id_order == 0){
             $id_order = $this->createOrder($order);            
        }
        
        if($id_order > 0){            
            $db->run("DELETE FROM articles WHERE articles.id_order = ".$id_order);            
            foreach($article as $item){                
                $db->run("INSERT INTO `articles`(`articul`, `count`, `comment`, `id_order`, `status`) VALUES (".$item['id'].", ".$item['count'].",\"".$item['comment']."\", ".$id_order.", 0)");
            }
            $this->setStatus(true);
            return $id_order;
        }else{
            trigger_error('Клиент ID:'.$this->cid. ' новый Order не создан ');
            $this->setStatus(false);
            return 0;
        }
    }

    public function sendOrder($article,$order){        
        return $this->sendArticle($article,$order);
    }
    
    public function getArticleOrder($orderid){
        $data = array();
        $db = ShopDB::getInstance();                        
        $res = $db->run("SELECT * FROM articles,products WHERE products.articul=articles.articul AND articles.id_order=".$orderid);      
        if(!$db->isError()){
            $res->data_seek(0);
            while ($row = $res->fetch_assoc()) {        
                $data[] = array(
                    'id'        => $row['articul'],
                    'count'     => (int)$row['count'],
                    'price'     => (int)$row['price'],
                    'comment'   => $row['comment']
                );                 
            }            
        }else{
            trigger_error('Заказ ID:'.$orderid. ' - отсутствуют товары в заказе');            
        }    
        return $data;
    }

    public function getOrder($orderid){   
        $data = NULL;
        $db = ShopDB::getInstance();                        
        $res = $db->run("SELECT * FROM orders,ordersinfo WHERE orders.id_info=ordersinfo.id AND orders.id=".$orderid);      
        if(!$db->isError() && $row = $res->fetch_assoc()){                        
               $data = array(
                    'type'        => (int) $row['type'],
                    'payment'     => (int) $row['payment'],
                    'logistic'    => (int) $row['logistic'],
                    'fname'       => $row['fname'],
                    'lname'       => $row['lname'],
                    'pname'       => $row['pname'],
                    'phone'   => $row['phone'],
                    'email'   => $row['email'],
                    'cname'   => $row['cname'],
                    'inn'   => $row['inn'],
                    'city'   => $row['city'],
                    'address'   => $row['address'],
                    'comment'   => $row['comment'],
                    'companyname'   => $row['companyname'],
                    'cphone'   => $row['cphone'],
                    'cemail'   => $row['cemail']
                );                           
        }else{
            trigger_error('Заказ ID:'.$orderid. ' - отсутствует в базе');            
        }    
        return $data;
    }    
    
    public function createDeal($orderid){  
        $comment = '';        
        $article_crm = array();        
        $crm = CRMconnector::getInstance();
        $article = $this->getArticleOrder($orderid);
        foreach($article as $item){
            $id = $crm->getProductIdToArticul($item['id']);
            $article_crm[] = array(
                'id'    => $id,
                'count' =>$item['count'],
                'price' =>$item['price']
            );
            $comment.= $crm->getProductName($id).' - '.$item['count'].' x '.$item['price'].' ('.$item['comment'].') /n';
        }  
        $crm->setArticle($article_crm);
        $order = $this->getOrder($orderid);
        if($order){            
            if($order['type']==0){
                $crm->createClient($order);
            }else{
                $crm->createCompany($order);
            }
            $crm->createDeal($comment);
        }else{
            $this->setStatus(false);
        }       
        $this->setOrderStatus($orderid,2);
        $this->setStatus(true);
    }
    
    public function payOrder($orderid){
        $this->createDeal($orderid);
        ///
        ///send YD
        ///
        $this->setStatus(true);
    }    
        
}
