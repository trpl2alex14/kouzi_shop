<?php

require_once  'include/function.php';
require_once  'config.php';


if(get_reqest('form')){
    $form_name = get_reqest('form');
    ob_start();
        require_once ('template/'.$form_name.'.tpl.php');
        $data_form = ob_get_contents();
    ob_end_clean();
    $data = array(  'status' => 'success', 
                    'data'   => $data_form, 
                    'form'   => $form_name);
    if($form_name == 'index'){
        $data['array']   = load_array($form_name);
        $data['article'] = getArticleClient(get_cookie("ID_CART"));       
    }
    echo json_encode($data);
    die();
}


function getArticleClient($id){
    $data = array();
    if($id){
        $data[] = array(
            'id'   => 10121,
            'count'=> 2,
        );   
        $data[] = array(
            'id'   => 500,
            'count'=> 3,
        );    
    }else{
        setcookie('ID_CART',5,time()+60*60*24*30);
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
        'name' => 'КОУЗИ 250В',
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
    $tmp['name']  ='КОУЗИ М1 250В Белый';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;
    
    $tmp['id']    =10112;
    $tmp['price'] =5500;
    $tmp['name']  ='КОУЗИ М1 250В RAL';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
  
    $tmp['id']    =10121;
    $tmp['price'] =5400;
    $tmp['name']  ='КОУЗИ М1 250ВK Белый';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
   
    $tmp['id']    =10122;
    $tmp['price'] =5700;
    $tmp['name']  ='КОУЗИ М1 250ВK RAL';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
//
    $tmp['id']    =10211;
    $tmp['price'] =5200;
    $tmp['name']  ='КОУЗИ М2 250В Белый';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;
    
    $tmp['id']    =10212;
    $tmp['price'] =5500;
    $tmp['name']  ='КОУЗИ М2 250В RAL';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
  
    $tmp['id']    =10221;
    $tmp['price'] =5400;
    $tmp['name']  ='КОУЗИ М2 250ВK Белый';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
   
    $tmp['id']    =10222;
    $tmp['price'] =5700;
    $tmp['name']  ='КОУЗИ М2 250ВK RAL';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
//
    $tmp['id']    =10311;
    $tmp['price'] =5200;
    $tmp['name']  ='КОУЗИ М3 250В Белый';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;
    
    $tmp['id']    =10312;
    $tmp['price'] =5500;
    $tmp['name']  ='КОУЗИ М3 250В RAL';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
  
    $tmp['id']    =10321;
    $tmp['price'] =5400;
    $tmp['name']  ='КОУЗИ М3 250ВK Белый';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
   
    $tmp['id']    =10322;
    $tmp['price'] =5700;
    $tmp['name']  ='КОУЗИ М3 250ВK RAL';
    $tmp['articul']=$tmp['id'];
    $data[] = $tmp;    
    
    
    $data[] = array(
        'id'   => 200,
        'name' => 'КОУЗИ 320В',
        'img'  => 'k250.jpg',
        'info' => 'КОУЗИ 320Вт - прогреет площадь 7м2',
        'about' => '<h2>Описание</h2><p>КОУЗИ 250Вт - прогреет площадь 5м2</p><ol><li>Модели: М1,М2,М3</li><li>Масса: 8 кг</li><li>КПД прибора: 99,7-99,9%</li><li>Габариты(ШхВхГ):<br>    М1 - 700х580х30мм<br>    М2 - 750х500х30мм<br>    М3 - 950х350х33мм</li></ol>',
        'price'=> 5200,
        'articul'=> 200,
        'type'   => 'd'
    );
    $data[] = array(
        'id'   => 300,
        'name' => 'КОУЗИ 450В',
        'img'  => 'k250.jpg',
        'info' => 'КОУЗИ 450Вт - прогреет площадь 10м2',
        'about' => '<h2>Описание</h2><p>КОУЗИ 250Вт - прогреет площадь 5м2</p><ol><li>Модели: М1,М2,М3</li><li>Масса: 8 кг</li><li>КПД прибора: 99,7-99,9%</li><li>Габариты(ШхВхГ):<br>    М1 - 700х580х30мм<br>    М2 - 750х500х30мм<br>    М3 - 950х350х33мм</li></ol>',
        'price'=> 5200,
        'articul'=> 300,
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
    return $data;
}