<?php

namespace Platform\util;

class Log
{

    static $instance;
    static function instance(){
        return self::$instance;
    }

    static function setInstance(LogInterface $instance){
        self::$instance = $instance;
    }

}