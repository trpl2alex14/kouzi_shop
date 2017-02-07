<?php


class bx24class {    
    protected $json_data;
    protected $html_content;
    
    public function __construct(){ 
        $this->json_data='';
    }
    
    protected function redirect($url){
            Header("HTTP 302 Found");
            Header("Location: ".$url);
            die();
    }

    protected function query($method, $url, $data = null, $jsonDecode = false){
            $curlOptions = array(
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false
            );

            if($method == "POST"){
                    $curlOptions[CURLOPT_POST] = true;
                    $curlOptions[CURLOPT_POSTFIELDS] = http_build_query($data);
            }
            elseif(!empty($data)){
                    $url .= strpos($url, "?") > 0 ? "&" : "?";
                    $url .= http_build_query($data);
            }
            $curl = curl_init($url);
            curl_setopt_array($curl, $curlOptions);
            $result = curl_exec($curl);
            return ($jsonDecode ? json_decode($result, 1) : $result);
    }

    protected function call($method, $params){
            $domain = $this->get_token("domain");
            return $this->query("POST", PROTOCOL.$domain."/rest/".$method, $params, true);
    }

    protected function save_token($query_data){
        $query_data["ts"] = time();
        $json_text = json_encode($query_data);
        file_put_contents(TOKEN_FILE,$json_text);        
    }

    protected function load_token($var=NULL){        
        if(is_file(TOKEN_FILE)){
            $json = file_get_contents(TOKEN_FILE);
            $this->json_data = json_decode($json, TRUE);
            if($var){
                return $this->json_data[$var];
            }else{
                return NULL;
            }
        }else {
            trigger_error('Ошибка:'.TOKEN_FILE. ' - отсутствует ключ CRM');
        }
        return NULL;
    }

    protected function get_token($var){        
        if($this->json_data == ''){ 
          return $this->load_token($var);
        }else{    
            return $this->json_data[$var];        
        }
    }    
    
    public function oauth_init(){
        $domain = SHOP_CRM;
        $params = array(
           "response_type" => "code",
           "client_id" => CLIENT_ID,
           "redirect_uri" => REDIRECT_URI,
        );
        $path = "/oauth/authorize/";
        $this->redirect(PROTOCOL.$domain.$path."?".http_build_query($params));  
        die();
    } 
    
    public function oauth_code (){
        $code      = htmlspecialchars($_REQUEST["code"]);
        $domain    = htmlspecialchars($_REQUEST["domain"]);
        $params = array(
           "grant_type"    => "authorization_code",
           "client_id"     => CLIENT_ID,
           "client_secret" => CLIENT_SECRET,
           "redirect_uri"  => REDIRECT_URI,
           "scope"         => SCOPE,
           "code"          => $code
        ); 
        $path = "/oauth/token/";
        $query_data = $this->query("GET", PROTOCOL.$domain.$path, $params, true);        
        if(isset($query_data["access_token"]))   { 
             $this->save_token($query_data); 
             return $query_data["access_token"];
        }
        return false;
    }

    public function oauth_refresh() {
        $this->load_token();
        $params = array(
                "grant_type"    => "refresh_token",
                "client_id"     => CLIENT_ID,
                "client_secret" => CLIENT_SECRET,
                "redirect_uri"  => REDIRECT_URI,
                "scope"         => SCOPE,
                "refresh_token" => $this->get_token("refresh_token")
        );       
        $domain = $this->get_token("domain");
        $path = "/oauth/token/";
        $query_data = $this->query("GET", PROTOCOL.$domain.$path, $params, true);

        if(isset($query_data["access_token"])){		
                $this->save_token($query_data);
                return $query_data["access_token"];                
        }else{
            return false;
        }
    }    
    
    public function oauth_access(){
        $this->load_token();
        if(time() > $this->get_token("ts") + $this->get_token("expires_in") + 30){
            return $this->oauth_refresh();
        }else
            return $this->get_token("access_token");
    }    
    
    public function status(){
        $buff = $this->oauth_access().'<br>';
        $buff .= date('c',time()).'<br>';                
        $buff .= date('c',($this->load_token("ts")+$this->load_token("expires_in")+30)).'<br>';        
        return $buff;
    }
    
    public function getContent(){
        return $this->html_content;
    }    
       
    ////-----------------------------api bx24--------------------------------
    
    public function getCrmProduct($id){
        $this->load_token();    
        $access_token = $this->oauth_access();        
        $data = $this->call("crm.product.get", array(
                "auth" => $access_token,
                "id" => $id
        ));    
        if(isset($data["result"])){
            return $data["result"];
        }else{
            return 0;
        }    
    }    
    
    public function getCrmProductToArticul($articul){
        $this->load_token();    
        $access_token = $this->oauth_access();        
        $data = $this->call("crm.product.list", array(
                "auth" => $access_token,
                "order" => array( "NAME" => "ASC" ),
		"filter"  => array("PROPERTY_272" => $articul),
		"select" => [ "ID", "NAME", "PRICE" ]
        ));    
        if($data["total"]>0){
            return $data["result"][0];
        }
        return NULL;        
    }      
    
    public function getCrmClientAtrToPhone($phone,$atr){
        $this->load_token();    
        $access_token = $this->oauth_access();        
        $data = $this->call("crm.contact.list", array(
                "auth" => $access_token,
                "order" => array( "DATE_CREATE" => "ASC" ),
		"filter"  => array("PHONE" => $phone),
		"select" => ["ID", "NAME", "LAST_NAME",  "COMPANY_ID"]
        ));            
        if($data["total"]>0){
            return (int)$data["result"][0][$atr];
        }
        return 0;        
    }    
    
    public function getCrmClientIdToPhone($phone){
        return $this->getCrmClientAtrToPhone($phone, "ID");        
    }    
    
    public function getCrmClientCompanyToPhone($phone){
        return $this->getCrmClientAtrToPhone($phone, "COMPANY_ID");        
    }        
    
    public function createCrmClient($fields){
        $this->load_token();    
        $access_token = $this->oauth_access();                
        $data = $this->call("crm.contact.add", array(
                "auth" => $access_token,
                "fields" =>  $fields,
                "params" => array(
                    "REGISTER_SONET_EVENT" => "Y"
                )
        ));        
        if(isset($data["result"])){
            return (int)$data["result"];
        }else{
            return 0;
        }
    }    
    
    public function createCrmCompany($fields){
        $this->load_token();    
        $access_token = $this->oauth_access();                
        $fields['OPENED'] = 'Y'; 
        $data = $this->call("crm.company.add", array(
                "auth" => $access_token,
                "fields" =>  $fields,
                "params" => array(
                    "REGISTER_SONET_EVENT" => "Y"
                )
        ));    
        if(isset($data["result"])){
            return (int)$data["result"];
        }else{
            return 0;
        }        
    }    
    
    public function createCrmDeal($fields){
        $this->load_token();    
        $access_token = $this->oauth_access();                
        $fields['OPENED'] = 'Y'; 
        $data = $this->call("crm.deal.add", array(
                "auth" => $access_token,
                "fields" =>  $fields,
                "params" => array(
                    "REGISTER_SONET_EVENT" => "Y"
                )
        ));    
        if(isset($data["result"])){
            return (int)$data["result"];
        }else{
            return 0;
        }        
    }    
    
    public function addCrmDealItems($id, $row){
        $this->load_token();    
        $access_token = $this->oauth_access();                        
        $data = $this->call("crm.deal.productrows.set", array(
                "auth" => $access_token,
                "id" =>     $id,                
                "rows" =>  $row
        ));  
        if(isset($data["result"])){
            return (int)$data["result"];
        }else{
            return 0;
        }        
    }      
    
    
    public function route(){
        if(isset($_REQUEST["code"])){
           if($this->oauth_code()){
                $this->redirect(REDIRECT_URI);      
                die();       
           }
        }elseif(isset($_REQUEST["refresh"])){
            if($this->oauth_refresh()){
                $this->redirect(REDIRECT_URI);
                die();        
            }	
        }        
        $action = isset($_REQUEST["action"]) ? htmlspecialchars($_REQUEST["action"]) : "";                           
        switch($action){
            case 'init':
                $this->oauth_init();
            break;            
            case 'status':
                $this->html_content = $this->status();                
            break;         
        }        
    }    
    
    
}

