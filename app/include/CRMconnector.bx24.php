<?php

define('TOKEN_FILE',APP_FOLDER.'key.json');

require_once  'config.php';
require_once  SHOP_LIB.'BX24class/bx24class.php';

class CRMconnector extends bx24class{
    protected static $_instance;
    protected $products;
    protected $client_id;
    protected $company_id;
    protected $order;


    public static function getInstance(){
        if( self::$_instance === NULL ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function __construct(){ 
        parent::__construct();
    }
    
    public function getProductToArticul($articul){
        $data = $this->getCrmProductToArticul($articul);        
        if($data){
            return $data;
        }else{
            trigger_error('Артикул ID:'.$articul. ' - отсутствует в CRM');
            return NULL;
        }
    }    
    
    public function getProductIdToArticul($articul){
        $data = $this->getCrmProductToArticul($articul);        
        if($data){
            return $data['ID'];
        }else{
            trigger_error('Артикул ID:'.$articul. ' - отсутствует в CRM');
            return 0;
        }
    }
    
    public function getProductName($id){
        $data = $this->getCrmProduct($id);
        if(isset($data['NAME'])){
            return $data['NAME'];
        }else{
            return 'Артикул не найден';
        }
    }    
    
    public function setArticle($articles){
        $this->products = $articles;
    } 
    
    public function setOrder($order){
        $this->order = $order;
    }

    
    public function createClient(){                
        $this->client_id = $this->getCrmClientIdToPhone($this->order['phone']);
        
        if($this->client_id == 0){
            $fields = array(
                "LAST_NAME"   =>$this->order['lname'],
                "NAME"        =>$this->order['fname'],
                "SECOND_NAME" =>$this->order['pname'],
                "PHONE" => array(array("VALUE" => $this->order['phone'] , "VALUE_TYPE" => "MOBILE"))                
            );
            if($this->order['email']){
                $fields["EMAIL"] = array(array("VALUE" => $this->order['email']));
            }
            if($this->company_id > 0){
                $fields["COMPANY_ID"] = $this->company_id;
            }
            $this->client_id = $this->createCrmClient($fields);
            if($this->client_id == 0){
                trigger_error('Ошибка: клиент с телефоном '.$this->order['phone']. ' не создан');
            }
        }
    } 

    public function createCompany(){                
        $this->client_id = $this->getCrmClientIdToPhone($this->order['cphone']);
        if($this->client_id == 0){            
            $fields = array(
                "TITLE" => $this->order['companyname'],
                "COMMENTS" => 'ИНН:'.$this->order['inn']
            );
            $this->company_id = $this->createCrmCompany($fields);
            
            $this->order['phone'] = $this->order['cphone'];
            $this->order['fname'] = $this->order['cname'];
            $this->order['email'] = $this->order['cemail'];               
            $this->createClient();
        } else {
            $this->company_id = $this->getCrmClientCompanyToPhone($this->order['cphone']);
        }        
    }     

    public function createDeal($orderid,$comment){                        
        $fields = array(
            "TITLE"    => 'Интернет магазин - Заказ № '.$orderid,
            "STAGE_ID" => DEAL_STAT,
            "COMMENTS" => $comment,
            "UF_CRM_1479793006" => $this->order['city']            
        );
        if($this->company_id > 0){
            $fields["COMPANY_ID"] = $this->company_id;
        }        
        if($this->client_id > 0){
            $fields["CONTACT_ID"] = $this->client_id;
        }        
        
        $id = $this->createCrmDeal($fields);   
        if($id >0 ){            
            $articles = array();
            foreach ($this->products as $item){
                $articles[] = array(
                    "PRODUCT_ID" => $item['id'],
                    "PRICE"      => $item['price'],
                    "QUANTITY"   => $item['count']
                );
            }
            $this->addCrmDealItems($id, $articles);
            return true;
        }else {
             trigger_error('Ошибка: создания сделки Order ID:'.$orderid);
            return false;
        }
    }     
}