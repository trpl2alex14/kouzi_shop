<?php
require_once  'config.php';
require_once  SHOP_LIB.'mysql.php';
require_once  SHOP_LIB.'Log.php';
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
        $this->log = new shopLog();
    }
    
    private function log($str) {
        $this->log->info($str);
    }    

    private function mail($title,$str) {
        $this->log->mail($title,$str);
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
        if($order){
            return $this->insertOrderInfo($order);                               
        }else{                    
            return $this->copyOrderInfo($this->cid);
        }        
    }        
               
    public function sendArticle($article,$order = NULL){
        $db = ShopDB::getInstance();       
        $id_order = $this->getLastOrder($this->cid);
        if($id_order > 0 && $order){
            $this->updateOrder($id_order,$order);
        }else if($id_order == 0){
             $id_order = $this->createOrder($this->cid,$order);            
        }
        
        if($id_order > 0){            
            $this->insertArticle($id_order, $article);
            $this->setStatus(true);
            return $id_order;
        }else{
            trigger_error('Клиент ID:'.$this->cid. ' новый Order не создан ');
            $this->setStatus(false);
            return 0;
        }
    }

    public function sendOrder($article,$order){  
        $this->log("sendArticle: CID-".$this->cid." Article: ".print_r($article,true));        
        $this->log("sendOrder: CID-".$this->cid." Order: ".print_r($order,true));
        $id = $this->sendArticle($article,$order);
        if($id>0){
            $this->setOrderStatus($id,1);
        }
        $this->log("resultOrder: OID-".$id);
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
        return $comment;
    }

    public function getTax($article){             
        $sum = 0;
        foreach($article as $item){            
            $sum +=$item['price']*$item['count'];
        }
        $sum *= TAX_SIZE;        
        return $sum;
    }
    
    public function createDeal($orderid){             
        $order = $this->getOrder($orderid);
        $article = $this->getArticleOrder($orderid);      
        global $NOTAX_CITY;
        if($order['payment']==1 && !in_array($order['city'],$NOTAX_CITY)){            
            $article[] = array(
                'id' =>   TAX_ID,
                'name' => 'Комиссия',
                'count' => 1,
                'price' => $this->getTax($article),
                'comment' =>''
            );          
        }
        $article[] = array(
            'id' =>   LOGISTIC_ID,
            'name' => 'Доставка',
            'count' => 1,
            'price' => $this->getPriceCity($order['city'], $order['logistic']),
            'comment' =>$order['city']
        );        
        $i=1;
        $comment = 'Заказ №'.$orderid.' <br>';           
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
        $this->log("createDeal: OID-".$orderid." Info:".$comment);
        $this->mail("Интернет магазин. Новая сделка №: ".$orderid,$comment);
        
        if($order && $order['status']<STATUS_ORDER_SEND){
            $this->addTaskCreateDeal($orderid,$comment,$order,$article_crm);
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
    
    public function startTask(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TASK_SCRIPT_URL);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);          
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 500);
        curl_exec($ch);
        curl_close($ch);        
    }            
}
