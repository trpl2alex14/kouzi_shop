<?php

require_once  'config.php';
require_once  SHOP_LIB.'ymoney/Settings.php';
require_once  SHOP_LIB.'ymoney/YaMoneyCommonHttpProtocol.php';
require_once  SHOP_INC.'actionBase.php';

class payConnector extends YaMoneyCommonHttpProtocol{
    
    protected static $_instance;



    public static function getInstance(){
        if( self::$_instance === NULL ) {
            $settings = new paySettings();
            self::$_instance = new self($settings);
        }
        return self::$_instance;
    }        
    
    protected function checkOrder($request){
        if($request['orderNumber'] > 0){
            $orderid = $request['orderNumber'];
            $aBase = new actionBase();            
            $order = $aBase->getOrder($orderid);
            if($order){                
                $sum = (int)$aBase->getPriceCity($order['city'], $order['logistic']);
                if($order['payment']==0) $sum += (int)$aBase->getSumOrder($orderid);
                if($sum == $request['orderSumAmount']){                    
                    return 0;
                }
            }            
        }
        $this->setErrorStr('Заказ с данными параметрами не найден.');
        return 100;
    }
    
    protected function paymentAviso($request){
        if($request['orderNumber'] > 0){
            $orderid = $request['orderNumber'];
            $aBase = new actionBase();            
            $order = $aBase->getOrder($orderid);
            if($order){                
                $sum = (int)$aBase->getPriceCity($order['city'], $order['logistic']);
                if($order['payment']==0){
                    $sum += (int)$aBase->getSumOrder($orderid);
                }
                if($sum == $request['orderSumAmount']){
                    $this->log("Пришла оплата: сделка ID" . $orderid);
                    $this->mail("Интернет магазин. Пришла оплата сделка №: ".$orderid,"Информация:<br>".print_r($request,true));
                    $aBase->setOrderStatus($orderid, STATUS_ORDER_PAY);                    
                    return 0;
                }
            }            
        }
        $this->log("Ошибка: пришла оплата с неизвестной сделки ID" . $request['orderNumber']);
        return 0;
    }
    
    public function sendForm($data){
        if(!isset($data['sum'])) $data['sum'] = '0';
        if(!isset($data['customerNumber'])) $data['customerNumber'] = '0';
        if(!isset($data['orderNumber'])) $data['orderNumber'] = '';
        if(!isset($data['cps_phone'])) $data['cps_phone'] = '';
        if(!isset($data['cps_email'])) $data['cps_email'] = '';
        
        $form = $this->getPayForm();                
        $search = array();
        $replace = array();
        foreach($data as $key => $item){
            $search[] = '{'.$key.'}';
            $replace[] = $item;
        }    
        $result = str_replace($search, $replace, $form);
        $this->log("Send form: ".$result);
        return $result;
    }
    
    public function Request($request){
        $req = array();
        if(isset($request['action'])){
            if($request['action'] == "checkOrder" || $request['action'] == "paymentAviso"){
                
                $req['action'] = $request['action'];                                                
                $req['orderSumAmount']          = isset($request['orderSumAmount']) ? $request['orderSumAmount'] : '';
                $req['orderSumCurrencyPaycash'] = isset($request['orderSumCurrencyPaycash']) ? $request['orderSumCurrencyPaycash'] : '';
                $req['orderSumBankPaycash']     = isset($request['orderSumBankPaycash']) ? $request['orderSumBankPaycash'] : '';
                $req['shopId']         = isset($request['shopId']) ? $request['shopId'] : '';
                $req['invoiceId']      = isset($request['invoiceId']) ? $request['invoiceId'] : '';
                $req['customerNumber'] = isset($request['customerNumber']) ? $request['customerNumber'] : '';
                $req['orderNumber']    = isset($request['orderNumber']) ? $request['orderNumber'] : '';
                $req['md5']    = isset($request['md5']) ? $request['md5'] : '';
                
                $this->processRequest($req);
            }else{
                trigger_error('Ошибка:'.$request['action']. ' неверен');
            }
        }
        exit;        
    }    
}
