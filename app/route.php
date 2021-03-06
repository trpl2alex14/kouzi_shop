<?php
require_once  'config.php';
require_once  SHOP_LIB.'ErrorLog.php';

require_once  'include/function.php';
require_once  'include/ral.php';
require_once  'include/initshop.php';
require_once  'include/reqshop.php';


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
    $data['status'] = $status;
    ob_start();
    $json_str = $_POST;    
    if(isset($json_str['jsonData'])){
        $json = json_decode($json_str['jsonData'],true);        
        
        $shop = ReqShop::getInstance($json['clientid']);

        switch (get_reqest('sendreq')){
            case 'article':    
                $data['orderid'] = $shop->sendArticle($json['article']);
            break;
            case 'order':            
                $data['orderid'] = $shop->sendOrder($json['article'],$json['order']);
            break;
            case 'createdeal':
                $shop->createDeal($json['orderid']);
                $shop->startTask();
            break;  
            case 'pay':                
                $data['payform'] = $shop->payOrder($json['orderid']);  
                $shop->startTask();
            break;        
        }    
        $data['status'] = $shop->getStatus();    
    }
    $data['html'] = ob_get_contents();
    ob_end_clean();
    echo json_encode($data);
    die();    
}else{
    trigger_error('Не верный запрос');
    die();
}