<?php
error_reporting(0);


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
        $data['array']    = load_array($form_name);        
        $data['article']  = getArticleClient($data['clientid']);                         
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


function getArticleClient($id){
    $data = array();
    if($id){
        $data[] = array(
            'id'   => 10121,
            'count'=> 2,
            'comment' => ''
        );   
        $data[] = array(
            'id'   => 500,
            'count'=> 3,
            'comment' => ''
        );    
    }
    return $data;
}

function load_array($form_name){
    $size = array(
        'type' => 'size',   
        'name' => 'Размер корпуса',
        'item' => [
            array('text' => 'M1 - 700x580x30', 'artmod'=> 100),
            array('text' => 'M2 - 750x500x30', 'artmod'=> 200),
            array('text' => 'M3 - 950x350x33', 'artmod'=> 300)
        ]
    );
    
    $size1 = array(
        'type' => 'size',   
        'name' => 'Размер корпуса',
        'item' => [            
            array('text' => 'Н2 - 750x500x40', 'artmod'=> 400),
            array('text' => 'Н3 - 1150x350x40', 'artmod'=> 500)
        ]
    );    
    
    $button = array(
        'type' => 'key',   
        'name' => 'Модификация',
        'item' => [
            array('text' => 'Под монтаж с терморегулятором', 'artmod'=> 10),            
            array('text' => 'Для включения в разетку(+200 руб.)', 'artmod'=> 20)            
        ]
    );

    $color = array(
        'type' => 'color',   
        'name' => 'Цвет',
        'item' => [
            array('text' => 'Цвет белый', 'artmod'=> 1),            
            array('text' => 'Цветной RAL(+300 руб.)', 'artmod'=> 2)            
        ]
    );
    
    $data = array();
    $tmp = array(
        'id'   => 10000,
        'name' => 'КОУЗИ 250Вт',
        'img'  => 'k250.jpg',
        'info' => 'КОУЗИ 250Вт - прогреет площадь 5м2',
        'about' => '<h2>Описание</h2><p>КОУЗИ 250Вт - прогреет площадь 5м2</p><ol><li>Модели: М1,М2,М3</li><li>Масса: 8 кг</li><li>КПД прибора: 99,7-99,9%</li><li>Габариты(ШхВхГ):<br>    М1 - 700х580х30мм<br>    М2 - 750х500х30мм<br>    М3 - 950х350х33мм</li></ol>',
        'price'=> 5200,
        'articul'=> 10000,
        'type'   => 'd',
        'model'  => [$size, $button, $color]
    );
                    
    $data[] = $tmp;
    $tmp['type']  ='i';
    $tmp['model'] = NULL;
    
    $tmp['id']    =10111;
    $tmp['price'] =5200;
    $tmp['name']  ='КОУЗИ М1 250Вт Белый';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;
    
    $tmp['id']    =10112;
    $tmp['price'] =5500;
    $tmp['name']  ='КОУЗИ М1 250Вт ';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
  
    $tmp['id']    =10121;
    $tmp['price'] =5400;
    $tmp['name']  ='КОУЗИ М1 250ВтK Белый';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
   
    $tmp['id']    =10122;
    $tmp['price'] =5700;
    $tmp['name']  ='КОУЗИ М1 250ВтK ';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
//
    $tmp['id']    =10211;
    $tmp['price'] =5200;
    $tmp['name']  ='КОУЗИ М2 250Вт Белый';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;
    
    $tmp['id']    =10212;
    $tmp['price'] =5500;
    $tmp['name']  ='КОУЗИ М2 250Вт ';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
  
    $tmp['id']    =10221;
    $tmp['price'] =5400;
    $tmp['name']  ='КОУЗИ М2 250ВтK Белый';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
   
    $tmp['id']    =10222;
    $tmp['price'] =5700;
    $tmp['name']  ='КОУЗИ М2 250ВтK ';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
//
    $tmp['id']    =10311;
    $tmp['price'] =5200;
    $tmp['name']  ='КОУЗИ М3 250Вт Белый';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;
    
    $tmp['id']    =10312;
    $tmp['price'] =5500;
    $tmp['name']  ='КОУЗИ М3 250Вт ';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
  
    $tmp['id']    =10321;
    $tmp['price'] =5400;
    $tmp['name']  ='КОУЗИ М3 250ВтK Белый';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
   
    $tmp['id']    =10322;
    $tmp['price'] =5700;
    $tmp['name']  ='КОУЗИ М3 250ВтK ';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
    
    
    $data[] = array(
        'id'   => 200,
        'name' => 'КОУЗИ 320Вт',
        'img'  => 'k250.jpg',
        'info' => 'КОУЗИ 320Вт - прогреет площадь 7м2',
        'about' => '<h2>Описание</h2><p>КОУЗИ 250Вт - прогреет площадь 5м2</p><ol><li>Модели: М1,М2,М3</li><li>Масса: 8 кг</li><li>КПД прибора: 99,7-99,9%</li><li>Габариты(ШхВхГ):<br>    М1 - 700х580х30мм<br>    М2 - 750х500х30мм<br>    М3 - 950х350х33мм</li></ol>',
        'price'=> 5200,
        'articul'=> 200,
        'type'   => 'd'
    );
    $data[] = array(
        'id'   => 300,
        'name' => 'КОУЗИ 450Вт',
        'img'  => 'k250.jpg',
        'info' => 'КОУЗИ 450Вт - прогреет площадь 10м2',
        'about' => '<h2>Описание</h2><p>КОУЗИ 250Вт - прогреет площадь 5м2</p><ol><li>Модели: М1,М2,М3</li><li>Масса: 8 кг</li><li>КПД прибора: 99,7-99,9%</li><li>Габариты(ШхВхГ):<br>    М1 - 700х580х30мм<br>    М2 - 750х500х30мм<br>    М3 - 950х350х33мм</li></ol>',
        'price'=> 5200,
        'articul'=> 300,
        'type'   => 'd'
    );
    $data[] = array(
        'id'   => 400,
        'name' => 'КОУЗИ 750Вт',
        'img'  => 'k250.jpg',
        'info' => 'КОУЗИ 750Вт - прогреет площадь 15м2',
        'about' => '<h2>Описание</h2><p>КОУЗИ 250Вт - прогреет площадь 5м2</p><ol><li>Модели: М1,М2,М3</li><li>Масса: 8 кг</li><li>КПД прибора: 99,7-99,9%</li><li>Габариты(ШхВхГ):<br>    М1 - 700х580х30мм<br>    М2 - 750х500х30мм<br>    М3 - 950х350х33мм</li></ol>',
        'price'=> 6900,
        'articul'=> 400,
        'type'   => 'd'
    );    
    $data[] = array(
        'id'   => 500,
        'name' => 'Терморегулятор',
        'img'  => 'term.jpg',
        'info' => 'контролирует заданную температуру в помещении',
        'about' => '<h2>Описание</h2><p>контролирует заданную температуру в помещении</p>',
        'price'=> 1500,
        'articul'=> 500,
        'type'   => 'v'
    );        
    $data[] = array(
        'id'   => 600,
        'name' => 'Ножки',
        'img'  => 'term.jpg',
        'info' => 'контролирует заданную температуру в помещении',
        'about' => '<h2>Описание</h2><p>контролирует заданную температуру в помещении</p>',
        'price'=> 400,
        'articul'=> 600,
        'type'   => 'v'
    );     
    return $data;
}