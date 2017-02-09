<?php


class shopLog {

    private $logfile;

    function __construct($file = NULL) {
        if(!$file){
            $file = defined("ALL_LOG")? ALL_LOG : 'log.log';
        }
        $this->logfile = $file;
    }

    public function info($str) {
        if(is_array($str) || is_object($str)) {
            $str = print_r($str,true);
        }
        $str = $str . "\n";
        file_put_contents($this->logfile, '[' . date("Y-m-d H:i:s") . '] ' . $str, FILE_APPEND);
    }
}