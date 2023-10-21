<?php

namespace Platform\util;

use Platform\exception\PlatformException;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;

class ClientFactory
{
    static $logContent = [];
    private $baseUri;
    private $timeout = 5;

    public function __construct($baseUri, $timeout, LogInterface $log)
    {
        $this->baseUri = $baseUri;
        $this->timeout = $timeout;
        Log::setInstance($log);
    }

    /**
     * 获取guzzle连接
     * @param string|null $authorization 认证
     * @param array $header 请求头，['content-type' => 'application/json; charset=UTF-8', 'accept' => 'text/plain', 'timeout' => 1.5, ...]
     * @return Client
     * @throws PlatformException
     * @author lca
     * @date 2023/7/24 17:31
     */
    public function getClient(string $authorization = null, array $header = []): Client
    {
        $baseUri = $this->baseUri;
        if (!$baseUri) {
            throw new PlatformException('未配置中台域名!');
        }

        $headers = array_merge([
            'accept' => 'text/plain',
            'timeout' => $this->timeout
        ], $header);

        if ($authorization) {
            $headers['Authorization'] = urldecode($authorization);
        }

        return self::getClientHandler($baseUri,  $headers);
    }

    /**
     * @param $baseUri
     * @param $logChannel
     * @param array $headers
     * @return Client
     */
    public function getClientHandler($baseUri, array $headers): Client
    {
        return new Client([
            'base_uri' => $baseUri,
            'headers' => $headers,
            'http_errors' => false,
            'on_stats' => function (TransferStats $stats){
                $request = $stats->getRequest();
                $logsContent["__HEADER__"] = $request->getHeaders();
                $logsContent["__REQUEST__"] = [
                    "PATH" => $request->getUri()->getPath(),
                    "METHOD" => $request->getMethod(),
                    "QUERY" => $request->getUri()->getQuery(),
                    "BODY" => json_decode($request->getBody(), true)
                ];

                //判断是否有正常的响应对象
                if ($stats->hasResponse()) {
                    $response = $stats->getResponse();
                    $statusCode = $response->getStatusCode();
                    $logsContent["__RESPONSE__"] = [
                        "__STATUS_CODE__" => $statusCode,
                        "__BODY__" => json_decode($response->getBody(), true)
                    ];

                } else {
                    if ($stats->getHandlerErrorData() instanceof \Exception) {
                        $error = $stats->getHandlerErrorData()->getMessage();
                    } else {
                        $error = $stats->getHandlerErrorData();
                    }
                    $logsContent["__RESPONSE__"] = [
                        "ERROR" => $error
                    ];
                }
                static::$logContent = json_encode($logsContent, 320);
                Log::instance()->info(static::$logContent);

            }
        ]);
    }


}
