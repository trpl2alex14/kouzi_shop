<?php
require_once  'config.php';
require_once  SHOP_LIB.'mysql.php';



class InitShop {        
    private $data_form;
    
    protected static $_instance;
    
    public static function getInstance($form_name = 'index'){
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
        
    public function createIdClient(){
        //$new_id = uniqid("kouzi_", true);
        if (isset($_COOKIE['_ga'])) {
            list($version,$domainDepth, $cid1, $cid2) = explode('.', $_COOKIE["_ga"],4);
            $contents = array('version' => $version, 'domainDepth' => $domainDepth, 'cid' => $cid1.'.'.$cid2);
            $new_id = $contents['cid'];
        }else{
            $new_id = uniqid("kouzi_", true);
        }        
        $this->setIdCart($new_id);          
        $db = ShopDB::getInstance();
        $db->run("INSERT INTO clients (id_cart) VALUES ('".$new_id."')");
        if(!$db->isError()){               
            return $db->getDB()->insert_id;    
        }
        trigger_error('Невозможно создать ID клиента');
        return 0;        
    }
    
    public function getIdClient($id_cookie){
        $db = ShopDB::getInstance();  
        if($id_cookie){        
            $id_cookie = $db->getDB()->real_escape_string($id_cookie);
            $res = $db->run("SELECT * FROM clients WHERE id_cart='".$id_cookie."'");      
            if(!$db->isError()){
                $res->data_seek(0);
                if ($row = $res->fetch_assoc()) {        
                    return $row['id'];                                 
                }           
            }
        }
        return $this->createIdClient();        
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
                $price[] = (int)$row['price'];
                $curier[] = (int)$row['curier'];
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
        $res = $db->run("SELECT * FROM articles,orders WHERE articles.id_order=orders.id AND orders.status IN (0,1,2) AND orders.id_client=".$id." order by orders.date DESC");      
        if(!$db->isError()){
            $res->data_seek(0);
            while ($row = $res->fetch_assoc()) {        
                $data[] = array(
                    'id'        => $row['articul'],
                    'count'     => (int)$row['count'],
                    'comment'   => $row['comment']
                );                
            }           
        }           
        return $data;
    }
    
    public function getOrderClient($id){
        $data = NULL;
        $db = ShopDB::getInstance();                
        $res = $db->run("SELECT ordersinfo.* FROM ordersinfo,orders WHERE ordersinfo.id=orders.id_info AND orders.id_client=".$id." order by orders.date DESC LIMIT 1");      
        if(!$db->isError()){
            $res->data_seek(0);
            if ($row = $res->fetch_assoc()) {        
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
            }           
        }           
        return $data;
    }    
    
    public function getVariations($list){
        $data = NULL;
        $arr = explode(',',$list);
        $db = ShopDB::getInstance();
        if($arr && $stmt = $db->getDB()->prepare("SELECT type,name,text,artmod FROM variations,variationgroup WHERE variationgroup.id=variations.id_group AND variationgroup.id=?")){                                 
                foreach($arr as $id_group) {                    
                    $stmt->bind_param("i", $id_group);
                    $stmt->execute();
                        
                    //$res = $stmt->get_result();                    
                    $tmparr = array();
                    $type = '';
                    $name = '';
                    
                    $stmt->bind_result($stype, $sname, $stext, $sartmod);
        
                    while ($stmt->fetch()) {          
                        $type = $stype;
                        $name = $sname;                        
                        $tmparr[] = array(
                            'text'   => $stext,
                            'artmod' => $sartmod
                        ); 
                    }
                    $data[] = array(
                        'type' =>$type,
                        'name' =>$name,
                        'item' =>$tmparr
                    );
                } 
                $stmt->close();            
        }
        return $data;
    }

    public function getProducts(){
        $data = array();
        $db = ShopDB::getInstance();
        $prds = $db->run("SELECT * FROM products, abouts WHERE abouts.id=products.id_about");
        if(!$db->isError()){
            while ($rowp = $prds->fetch_assoc()) {
                    $data[] = array(
                        'id'        => (int) $rowp['articul'],
                        'articul'   => (int) $rowp['articul'],
                        'name'      => $rowp['name'],
                        'img'       => $rowp['img'],
                        'info'      => $rowp['info'],
                        'about'     => $rowp['about'],
                        'price'     => (int) $rowp['price'],                    
                        'type'      => $rowp['type'],
                        'model'     => $this->getVariations($rowp['model'])
                    );            
            }
        }
        return $data;
    }
}

