<?php

    function get_reqest($arg){
        if(isset($_POST[$arg])){
            return htmlspecialchars($_POST[$arg]);
        }else if(isset($_GET[$arg])){
            return htmlspecialchars($_GET[$arg]);
        } else return false;
    } 

