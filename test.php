<?php

use Platform\user\requests\Auth;
use Platform\util\LogInterface;

require __DIR__ . '/vendor/autoload.php';

//$class = (new \Platform\user\requests\Auth("1111"))->getUserByToken();

// new auth($token,new config($config))->getUserByToken()


$log = new class  implements LogInterface{
    function info()
    {
        // TODO: Implement info() method.
    }
    function error()
    {
        // TODO: Implement error() method.
    }
};

$config = new \Platform\util\Config('http://center-hb.test.shijizhongyun.com:8080',5,$log);
$class = (new Auth('',$config))
    ->getUserByToken();
$data = $class->wait();
dd($data);