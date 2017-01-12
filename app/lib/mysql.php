<?php

require_once  './../config.php';

class MyDB 
{
    var $dblogin = SHOP_DB_USER; 
    var $dbpass  = SHOP_DB_PASS; 
    var $db      = SHOP_DB_NAME; 
    var $dbhost  = SHOP_DB_HOST;

    var $link;
    var $query;
    var $err;
    var $result;
    var $data;
    var $fetch;

    function connect() {
        $this->link = new mysqli($this->dbhost, $this->dblogin, $this->dbpass, $this->db);
        if ($this->link->connect_error) {
            die('Connect Error (' . $this->link->connect_errno . ') ' . $this->link->connect_error);
        }         
        $this->link->query('SET NAMES utf8');
    }

    function close() {
    //    mysql_close($this->link);
    }

    function run($query) {
        $this->query = $query;
        $this->result = $this->link->query($this->query);
        $this->err = mysqli_connect_errno();
        return $this->result;
    }
    
    
    function stop() {
        unset($this->data);
        unset($this->result);
        unset($this->fetch);
        unset($this->err);
        unset($this->query);
    }
}
