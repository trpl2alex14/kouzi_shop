<?php
require_once  'config.php';
require_once  SHOP_LIB.'mysql.php';

class ReqShop {
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
    
    public function createOrder($order){
        $db = ShopDB::getInstance();
        $sqlr =  "INSERT INTO `ordersinfo`(`type`, `fname`, `lname`, `pname`, `phone`, `email`, `cname`, `inn`, `companyname`, `cphone`, `cemail`,"
                ." `city`, `address`, `comment`, `logistic`, `payment`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $id_order = 0;
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
        $db->run("INSERT INTO `orders`(`id_client`, `id_info`, `status`, `date`) VALUES (".$this->cid.", ".$id_ordersinfo.", 0, NOW())");
        return $db->getDB()->insert_id;                    
    }
    
    public function getLastOrder(){
        $db = ShopDB::getInstance();
        $res = $db->run("SELECT id FROM orders WHERE orders.status IN (0,1,2) AND id_client=".$this->cid." order by orders.date DESC LIMIT 1");            
        if(!$db->isError() && $row = $res->fetch_assoc()){
            return $row['id'];
        }else{
            return $this->createOrder(NULL);            
        }
    }
    
    public function sendArticle($article){
        $db = ShopDB::getInstance();                        
        $id_order = $this->getLastOrder();
        
        if($id_order > 0){            
            $db->run("DELETE FROM articles WHERE articles.id_order = ".$id_order);            
            foreach($article as $item){                
                $db->run("INSERT INTO `articles`(`articul`, `count`, `comment`, `id_order`, `status`) VALUES (".$item['id'].", ".$item['count'].",\"".$item['comment']."\", ".$id_order.", 0)");
            }
            $this->setStatus(true);
        }else{
            trigger_error('Клиент ID:'.$this->cid. ' новый Order не создан ');
            $this->setStatus(false);
        }
    }

    public function sendOrder($article,$order){
        ///
        $this->setStatus(true);        
    }

    public function createDeal(){
        ///
        $this->setStatus(true);
    }
    
    public function payOrder(){
        ///
        $this->setStatus(true);
    }    
    

    
}
