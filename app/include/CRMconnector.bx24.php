<?php

require_once  'config.php';
require_once  SHOP_LIB.'BX24class/bx24class.php';

class CRMconnector extends bx24class{
    protected static $_instance;
    
    public static function getInstance(){
        if( self::$_instance === NULL ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    protected function __construct(){ 
        parent::__construct();
    }
    
    public function getProductIdToArticul($articul){
        
    }
    
    public function getProductName($id){
        $data = $this->getProductName($id);
        if(isset($data['NAME'])){
            return $data['NAME'];
        }else{
            return 'Артикул не найден';
        }
    }    
    
    public function setArticle($articles){
        
    } 
    
    public function createClient($order){
        
    } 

    public function createCompany($order){
        
    }     

    public function createDeal($comment){
        
    }     
}
