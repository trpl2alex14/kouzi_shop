<?php
require_once  'config.php';
require_once  SHOP_LIB.'mysql.php';
require_once  SHOP_LIB.'Log.php';
require_once  SHOP_INC.'CRMconnector.bx24.php';
require_once  SHOP_INC.'payConnector.ymoney.php';
require_once  SHOP_INC.'actionBase.php';


class ReqShop  extends actionBase{
    private $cid;
    private $status;
    private $log;
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
        $this->log = new shopLog();
    }
    
    private function log($str) {
        $this->log->info($str);
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
            $res = $db->run("SELECT orders.id_info FROM orders,ordersinfo WHERE ordersinfo.id=orders.id_info  AND id_client=".$this->cid." order by orders.date DESC LIMIT 1"); 
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
        $res = $db->run("SELECT id FROM orders WHERE orders.status IN (".STATUS_ORDER_INIT.",".STATUS_ORDER_PROCESS.",".STATUS_ORDER_ERROR.") AND id_client=".$this->cid." order by orders.date DESC LIMIT 1");            
        if(!$db->isError() && $row = $res->fetch_assoc()){
            return $row['id'];
        }else{
            return 0;
        }
    }
    
    public function createOrder($order){
        $db = ShopDB::getInstance();
        $id_ordersinfo = $this->createOrderInfo($order);
        $db->run("INSERT INTO `orders`(`id_client`, `id_info`, `status`, `date`) VALUES (".$this->cid.", ".$id_ordersinfo.", ".STATUS_ORDER_INIT.", NOW())");
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
        $id = $this->sendArticle($article,$order);
        if($id>0){
            $this->setOrderStatus($id,1);
        }
        return $id;
    }
         
    public function getCommentInfoOrder($order){
        $comment='<hr>';               
        if($order['type']==0){
            $comment.= 'Получатель: '.$order['lname'].' '.$order['fname'].' '.$order['pname'].'<br>';
            $comment.= 'Контакты: '.$order['phone'].' '.$order['email'].'<br>';
        }  else {
            $comment.= 'Получатель: '.$order['companyname'].' ИНН:'.$order['inn'].' '.$order['cname'].'<br>';
            $comment.= 'Контакты: '.$order['cphone'].' '.$order['cemail'].'<br>';            
        }
        if($order['logistic']==0){
            $comment.= 'Доставка до склада: '.$order['city'].'<br>'; 
        }else{
            $comment.= 'Доставка до адреса: '.$order['city'].' '.$order['address'].'<br>'; 
        }
        if($order['payment']==0){
            $comment.= 'Оплата: полная<br>'; 
        }else{
            $comment.= 'Оплата: только доставка<br>'; 
        }
        $comment.= 'Доп. информация: '.$order['comment'].'<br>';          
    }

    public function createDeal($orderid){             
        $order = $this->getOrder($orderid);
        $article = $this->getArticleOrder($orderid);                
        $article[] = array(
            'id' =>   LOGISTIC_ID,
            'name' => 'Доставка',
            'count' => 1,
            'price' => $this->getPriceCity($order['city'], $order['logistic']),
            'comment' =>$order['city']
        );        
        
        $i=1;
        $comment = '';           
        $article_crm = array();                
        foreach($article as $item){            
            $article_crm[] = array(
                'id'    =>$item['id'],
                'count' =>$item['count'],
                'price' =>$item['price']
            );
            $comment.= ($i++).' '.$item['name'].' '.$item['comment'].' - '.$item['count'].' x '.$item['price'].' <br>';            
        } 

        $comment .= $this->getCommentInfoOrder($order);
        $this->log($comment);
        
        if($order && $order['status']<STATUS_ORDER_SEND){
            $crm = CRMconnector::getInstance();        
            $crm->setArticle($article_crm);
            $crm->setOrder($order);
            $crm->sendDeal($orderid,$comment);            
            $this->setOrderStatus($orderid,STATUS_ORDER_SEND);
            $this->setStatus(true);                
        }else{
            $this->setStatus(false);
            trigger_error('Заказ ID:'.$orderid. ' - уже закрыт или отсутствует');
        }            
    }    
      
    public function payOrder($orderid){
        $this->createDeal($orderid);
        $pay = payConnector::getInstance();
        $order = $this->getOrder($orderid);
        if($order){
            $sum = (int)$this->getSumOrder($orderid);
            $logistic = (int)$this->getPriceCity($order['city'], $order['logistic']);
            $total = ($order['payment']==0)?$sum+$logistic:$logistic;
            $data = array(
                'orderNumber'    => $orderid,
                'customerNumber' => $order['lname'].' '.$order['fname'].' '.$order['pname'],
                'cps_phone'      => $order['phone'], 
                'sum'            => $total  
            );
            if($order['email']!=''){
                $data['cps_email'] = $order['email'];
            }
            if($total > 0){
                $this->setOrderStatus($orderid,STATUS_ORDER_SEND);
                $this->setStatus(true);                                
                return $pay->sendForm($data);
            }else{
                trigger_error('Заказ ID:'.$orderid. ' - 0 стоимость заказа');
            }
        }
        $this->setStatus(false);
    }    
        
}
