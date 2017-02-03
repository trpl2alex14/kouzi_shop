<?php

    function get_reqest($arg){
         if(isset($_GET[$arg])){
            return htmlspecialchars($_GET[$arg]);
        } else if(isset($_POST[$arg])){
            return htmlspecialchars($_POST[$arg]);
        }else return false;
    } 

    
    function get_cookie($arg){
        if(isset($_COOKIE[$arg])){
            return htmlspecialchars($_COOKIE[$arg]);
        } else return false;
    }
