<?php

namespace Platform\util;

class BaseRequest
{
    protected $token;
    protected $client;

    public function __construct($token, Config $config)
    {
        $this->token = $token;
        $this->client = (new ClientFactory($config->baseUri,$config->timeout,$config->log))->getClient($token);
    }

}