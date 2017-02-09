<?php
require_once  'config.php';
require_once  SHOP_LIB.'mysql.php';

class actionBase {
    
    public function setOrderStatus($id,$status){
        $db = ShopDB::getInstance();
        $db->run("UPDATE `orders` SET `status`=".$status." WHERE status != 3 AND id=".$id);            
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
            $db->run("INSERT INTO `taskcreatedeal`(`order_id`, `comment`, `orderinfo`, `products`) VALUES (".$id.",'".$comment."','".json_encode($order)."','".json_encode($products)."')");
        }                            
    }
    
     
}
