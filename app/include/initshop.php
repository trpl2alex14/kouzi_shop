<?php
require_once  'config.php';
require_once  SHOP_LIB.'mysql.php';



class InitShop {        
    private $data_form;
    
    protected static $_instance;
    
    public static function getInstance($form_name = 'index'){
        if($form_name == ''){
            $form_name = 'index';
        }
        if( self::$_instance === NULL ) {
            self::$_instance = new self($form_name);
        }
        return self::$_instance;
    }
    
    protected function __construct($form_name){ 
        ob_start();
            require_once (SHOP_TEMPLATE.$form_name.'.tpl.php');
            $this->data_form = ob_get_contents();
        ob_end_clean();             
    }    
    
    public function getContent(){
        return $this->data_form;
    }
 
    public function setIdCart($id){
        setcookie(ID_CART,$id,time()+60*60*24*30);
    }
        
    public function getIdClient($id_cookie){
        if($id_cookie){
            return $id_cookie;
        }else{
            $new_id = 5;  /////random new client!!!
            $this->setIdCart($new_id);
            return $new_id;
        }
    }    
    
    public function getCity(){
        $city = array();
        $price = array();
        $curier = array();
        $time = array();        
        $db = ShopDB::getInstance();                
        $res = $db->run("SELECT * FROM city");            
        if(!$db->isError()){
            $res->data_seek(0);
            while ($row = $res->fetch_assoc()) {        
                $city[] = $row['name'];
                $price[] = $row['price'];
                $curier[] = $row['curier'];
                $time[] = $row['time'];
            }           
        }   
        $data = array(
            'name' => $city,
            'price' => $price,
            'curier' => $curier,
            'time' => $time
        );
        return $data;
    }    
    
    public function getArticleClient($id){
        $data = array();
        $db = ShopDB::getInstance();                
        $res = $db->run("SELECT * FROM articles,orders WHERE articles.id_order=orders.id AND orders.status=0 AND orders.id_clients=".$id);      //add db      
        if(!$db->isError()){
            $res->data_seek(0);
            while ($row = $res->fetch_assoc()) {        
                $data[] = array(
                    'id'        => $row['articul'],
                    'count'     => $row['count'],
                    'comment'   => $row['comment']
                );                
            }           
        }           
        return $data;
    }
}

