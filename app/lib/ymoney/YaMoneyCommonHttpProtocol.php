<?php


require_once dirname(__FILE__).'/Utils.php';
require_once  SHOP_LIB.'Log.php';


abstract class YaMoneyCommonHttpProtocol {

    private $action;
    private $settings;
    private $log;
    private $error_msg = null;
    protected $payform = '<form action="{url}" method="post">
        <input name="shopId" value="{shopId}" type="hidden"/>
        <input name="scid" value="{scid}" type="hidden"/>
        <input name="sum" value="{sum}" type="hidden">
        <input name="customerNumber" value="{customerNumber}" type="hidden"/>
        <input name="paymentType" value="{paymentType}" type="hidden"/>
        <input name="orderNumber" value="{orderNumber}" type="hidden"/>
        <input name="cps_phone" value="{cps_phone}" type="hidden"/>
        <input name="cps_email" value="{cps_email}" type="hidden"/>
        <input type="submit" value="Pay"/>
        </form>';    

    abstract protected function checkOrder($request);    
    abstract protected function paymentAviso($request); 
    
    public function __construct(paySettings $settings) {        
        $this->settings = $settings;
        $this->log = new shopLog();
    }        

    public function getPayForm() {     
        $data =array(
            'url'         => $this->getUrlYM(),
            'shopId'      => $this->settings->SHOP_ID,
            'scid'        => $this->settings->SHOP_SCID,
            'paymentType' => $this->settings->SHOP_PTYPE
        );        
        $search = array();
        $replace = array();
        foreach($data as $key => $item){
            $search[] = '{'.$key.'}';
            $replace[] = $item;
        }        
        return str_replace($search, $replace, $this->payform);
    }     
    
    public function getUrlYM() {        
        return $this->settings->YMONEY_URL;
    }    
    
    public function setErrorStr($error) {        
        $this->error_msg = $error;
    }            
    
    public function processRequest($request) {
        $this->action = $request['action'];
        $this->log("Start " . $this->action);
        $this->log("Security type " . $this->settings->SECURITY_TYPE);
        if ($this->settings->SECURITY_TYPE == "MD5") {
            $this->log("Request: " . print_r($request, true));           
            if (!$this->checkMD5($request)) {
                $response = $this->buildResponse($this->action, $request['invoiceId'], 1);
                $this->sendResponse($response);
            }
        }        
        $result = 0;
        if ($this->action == 'checkOrder') {
            $result = $this->checkOrder($request);             
        } else {
            $result = $this->paymentAviso($request);
        }
        $response = $this->buildResponse($this->action, $request['invoiceId'], $result, $this->error_msg);
        $this->sendResponse($response);
    }

    
    private function checkMD5($request) {
        $str = $request['action'] . ";" .
            $request['orderSumAmount'] .";". 
            $request['orderSumCurrencyPaycash'] .";".
            $request['orderSumBankPaycash'] .";". 
            $request['shopId'] .";".
            $request['invoiceId'] .";". 
            $request['customerNumber'] .";". 
            $this->settings->SHOP_PASSWORD;
        $this->log("String to md5: " . $str);
        $md5 = strtoupper(md5($str));

        if ($md5 != strtoupper($request['md5'])) {
            $this->log("Wait for md5:" . $md5 . ", recieved md5: " . $request['md5']);
            return false;
        }
        return true;
    }


    private function buildResponse($functionName, $invoiceId, $result_code, $message = null) {
        try {
            $performedDatetime = Utils::formatDate(new DateTime());
            $response = '<?xml version="1.0" encoding="UTF-8"?><' . $functionName . 'Response performedDatetime="' . $performedDatetime .
                '" code="' . $result_code . '" ' . ($message != null ? 'message="' . $message . '"' : "") . ' invoiceId="' . $invoiceId . '" shopId="' . $this->settings->SHOP_ID . '"/>';
            return $response;
        } catch (\Exception $e) {
            $this->log($e);
        }
        return null;
    }
  

    public function log($str) {
        $this->log->info($str);
    }

    private function sendResponse($responseBody) {
        $this->log("Response: " . $responseBody);
        header("HTTP/1.0 200");
        header("Content-Type: application/xml");
        echo $responseBody;
        exit;
    }
}
