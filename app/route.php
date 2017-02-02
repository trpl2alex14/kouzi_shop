<?php
//error_reporting(0);


require_once  'include/function.php';
require_once  'include/ral.php';
require_once  'include/initshop.php';
require_once  'config.php';


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
    $status = 'success';
    
    switch (get_reqest('sendreq')){
        case 'article':
            //post json: clientid,article
        break;
        case 'order':
            //post json: clientid,article,order
        break;
        case 'createdeal':
            //get:clientid
        break;    
        case 'pay':
            //get:clientid
        break;  
        default:
            $status = 'error';
    }
    $data['status'] = $status;
    echo json_encode($data);
    die();    
}else{
    die();
}