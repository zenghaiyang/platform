<?php

namespace Platform\util;

class Config
{
    public $baseUri;
    public $log;
    public $timeout;

    public function __construct($uri, $timeout,LogInterface $log)
    {
        $this->baseUri = $uri;
        $this->log = $log;
        $this->timeout = $timeout;
    }

}
