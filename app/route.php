<?php

require_once  'include/function.php';
require_once  'config.php';


if(get_reqest('form')){
    $form_name = get_reqest('form');
    ob_start();
        require_once ('template/'.$form_name.'.tpl.php');
        $data_form = ob_get_contents();
    ob_end_clean();
    $data = array('status' => 'success', 'data' => $data_form, 'form' => $form_name, 'array' => load_array($form_name));
    echo json_encode($data);
    die();
}


function load_array($form_name){
    $data = array();
    if($form_name == 'index'){
        $data[] = array(
            'id'   => 1,
            'name' => 'КОУЗИ 250В',
            'img'  => 'k250.jpg',
            'info' => 'КОУЗИ 250Вт - прогреет площадь 5м2',
            'price'=> 5200,
            'articul'=> 100
        );
        $data[] = array(
            'id'   => 2,
            'name' => 'КОУЗИ 250В',
            'img'  => 'k250.jpg',
            'info' => 'КОУЗИ 250Вт - прогреет площадь 5м2',
            'price'=> 5200,
            'articul'=> 100
        );
        $data[] = array(
            'id'   => 3,
            'name' => 'КОУЗИ 250В',
            'img'  => 'k250.jpg',
            'info' => 'КОУЗИ 250Вт - прогреет площадь 5м2',
            'price'=> 5200,
            'articul'=> 100
        );
        $data[] = array(
            'id'   => 4,
            'name' => 'Терморегулятор',
            'img'  => 'term.jpg',
            'info' => 'контролирует заданную температуру в помещении',
            'price'=> 1500,
            'articul'=> 500
        );        
    }
    return $data;
}