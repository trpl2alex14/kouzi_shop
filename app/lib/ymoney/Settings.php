<?php

define('LOG_PAYMENT',"../../log/logpay.log");

class paySettings {

    public $SHOP_PASSWORD = SHOP_KEY_WORD;
    public $SHOP_ID = SHOP_ID; 
    public $SHOP_SCID = SHOP_SCID;
    public $SHOP_PTYPE = "";
    public $YMONEY_URL = SHOP_YDURL; 
    public $SECURITY_TYPE;
    public $LOG_FILE;    
    public $request_source;


    function __construct($SECURITY_TYPE = "MD5" /* MD5 | PKCS7 */, $request_source = "php://input") {
        $this->SECURITY_TYPE = $SECURITY_TYPE;
        $this->request_source = $request_source;
        $this->LOG_FILE = dirname(__FILE__).LOG_PAYMENT;
    }
}