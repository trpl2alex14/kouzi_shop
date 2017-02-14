<?php
require_once  'config.php';
require_once  SHOP_LIB.'mysql.php';

class actionBase {
    
    public function createOrder($cid,$order){
        $db = ShopDB::getInstance();
        $id_ordersinfo = $this->createOrderInfo($order);
        $db->run("INSERT INTO `orders`(`id_client`, `id_info`, `status`, `date`) VALUES (".$cid.", ".$id_ordersinfo.", ".STATUS_ORDER_INIT.", NOW())");
        return $db->getDB()->insert_id;                    
    }    
    
    public function getLastOrder($cid){
        $db = ShopDB::getInstance();
        $res = $db->run("SELECT id FROM orders WHERE orders.status IN (".STATUS_ORDER_INIT.",".STATUS_ORDER_PROCESS.",".STATUS_ORDER_ERROR.") AND id_client=".$cid." order by orders.date DESC LIMIT 1");            
        if(!$db->isError() && $row = $res->fetch_assoc()){
            return $row['id'];
        }else{
            return 0;
        }
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

    public function insertArticle($id_order,$article){
        $db = ShopDB::getInstance();                
        $db->run("DELETE FROM articles WHERE articles.id_order = ".$id_order);            
        foreach($article as $item){
            if($item['count']>0){
                $db->run("INSERT INTO `articles`(`articul`, `count`, `comment`, `id_order`, `status`) VALUES (".$item['id'].", ".$item['count'].",\"".$item['comment']."\", ".$id_order.", 0)");
            }
        }                                
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
    
    public function insertOrderInfo($order){
        $db = ShopDB::getInstance();
        $sqlr =  "INSERT INTO `ordersinfo`(`type`, `fname`, `lname`, `pname`, `phone`, `email`, `cname`, `inn`, `companyname`, `cphone`, `cemail`,"
                ." `city`, `address`, `comment`, `logistic`, `payment`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";        
        $id_ordersinfo = 0;
                
        if( $order && $stmt = $db->getDB()->prepare($sqlr)){
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
        return $id_ordersinfo;
    }

    public function copyOrderInfo($cid){                  
        $id_ordersinfo = 0;        
        $db = ShopDB::getInstance();
        $res = $db->run("SELECT orders.id_info FROM orders,ordersinfo WHERE ordersinfo.id=orders.id_info  AND id_client=".$cid." order by orders.date DESC LIMIT 1"); 
        if(!$db->isError() && $row = $res->fetch_assoc()){
            $db->run("INSERT INTO `ordersinfo`(`type`, `fname`, `lname`, `pname`, `phone`, `email`, `cname`, `inn`, `companyname`, `cphone`, `cemail`, `city`, `address`, `comment`, `logistic`, `payment`, `date`)"
                   . " SELECT `type`, `fname`, `lname`, `pname`, `phone`, `email`, `cname`, `inn`, `companyname`, `cphone`, `cemail`, `city`, `address`, `comment`, `logistic`, `payment`, NOW() FROM ordersinfo WHERE ordersinfo.id=".$row['id_info']); 
            $id_ordersinfo = $db->getDB()->insert_id;       
        }                        
        return $id_ordersinfo;
    }

    
    
    public function setOrderStatus($id,$status){
        $db = ShopDB::getInstance();
        $db->run("UPDATE `orders` SET `status`=".$status." WHERE (status != 3 OR status<".$status.") AND id=".$id);            
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
                    'comment'   => $row['comment'],
                    'name'      => $row['name']
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
                    'cemail'   => $row['cemail'],
                    'status'   => (int)$row['status']
                );                           
        }else{
            trigger_error('Заказ ID:'.$orderid. ' - отсутствует в базе');            
        }    
        return $data;
    }   

    public function getPriceCity($city,$type){
        $db = ShopDB::getInstance();                
        $res = $db->run("SELECT * FROM city WHERE name='".$city."'");            
        if(!$db->isError() && $row = $res->fetch_assoc()){
            if($type==0){
                return (int)$row['price'];
            }else{
                return (int)$row['price']+(int)$row['curier'];
            }                                    
        }   
        return 0;
    }    

    public function getSumOrder($orderid){
        $db = ShopDB::getInstance();                
        $res = $db->run("SELECT sum(products.price*articles.count) FROM products,articles WHERE products.articul=articles.articul AND articles.id_order=".$orderid." ");            
        if(!$db->isError() && $row = $res->fetch_row()){           
            return (int)$row[0];
        }   
        return 0;
    } 
    
    public function addTaskCreateDeal($id,$comment,$order,$products){
        $db = ShopDB::getInstance();                        
        $res = $db->run("SELECT * FROM ordercrm WHERE order_id=".$id);      
        if(!$db->isError() && $row = $res->fetch_assoc()){
            trigger_error('Заказ ID:'.$id. ' - уже добавлен в базу');
        }else{
            $order=base64_encode( serialize($order));
            $products=base64_encode( serialize($products));
            $db->run("INSERT INTO `taskcreatedeal`(`order_id`, `comment`, `orderinfo`, `products`) VALUES (".$id.",'".$comment."','".$order."','".$products."')");
        }                            
    }
    
     
}
