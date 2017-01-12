<?php

require_once  'include/function.php';
require_once  'config.php';


if(get_reqest('form')){
    $form_name = get_reqest('form');
    ob_start();
        require_once ('template/'.$form_name.'.tpl.php');
        $data_form = ob_get_contents();
    ob_end_clean();
    $data = array('status' => 'success', 'data' => $data_form, 'form' => $form_name);
    echo json_encode($data);
    die();
}