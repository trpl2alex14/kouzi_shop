<?php
define('HOST_DEV', $_SERVER['REMOTE_ADDR'] == '127.0.0.1');
define('IN_DEV', (HOST_DEV) ? 'On':'Off');
error_reporting(-1);
ini_set('display_errors', IN_DEV);

require_once  'include/function.php';
require_once  'include/ral.php';
require_once  'include/initshop.php';
require_once  'include/reqshop.php';
require_once  SHOP_LIB.'ErrorLog.php';
require_once  'config.php';

$errorClass   = new ErrorLog('log/error.log', 1, (HOST_DEV?1:0), (HOST_DEV?0:1), 'av@itentaro.ru');
$errorMethod  = 'handler';
set_error_handler(array($errorClass, $errorMethod));

if(get_reqest('form')){
    $form_name = get_reqest('form');        
    
    $shop = InitShop::getInstance($form_name);
    
    $data_form = $shop->getContent();
    $id_client = $shop->getIdClient(get_cookie(ID_CART));
    
    $data = array(  
        'status' => 'success', 
        'data'   => $data_form, 
        'form'   => $form_name,
        'clientid' => $id_client
    );
          
    if($form_name == 'index'){        
        $data['ral']      = $ralclassic; 
        $data['city']     = $shop->getCity();        
        $data['article']  = $shop->getArticleClient($id_client);      
        $data['order']    = $shop->getOrderClient($id_client);        
        $data['array']    = $shop->getProducts();               
    }
    echo json_encode($data);
    die();
}elseif(get_reqest('sendreq')){
    $data = array();
    $status = 'error';

    $json_str = $_POST;          
    $json = json_decode($json_str['jsonData'],true);    
    
    $shop = ReqShop::getInstance($json['clientid']);
                
    switch (get_reqest('sendreq')){
        case 'article':    

            $shop->sendArticle($json['article']);
        break;
        case 'order':            
            $shop->sendOrder($json['article'],$json['order']);
        break;
        case 'createdeal':
            $shop->createDeal();
        break;    
        case 'pay':
            $shop->payOrder();
        break;  
    }    
    $data['status'] = $shop->getStatus();
    echo json_encode($data);
    die();    
}else{           
    trigger_error('Не верный запрос');
    die();
}