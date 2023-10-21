<?php

namespace Platform\user\requests;

use Platform\exception\PlatformException;
use Platform\user\ApiPath;
use Platform\user\dto\TokenDto;
use Platform\user\dto\UserShortInfoDto;
use Platform\util\BaseRequest;
use Platform\util\ClientFactory;
use Platform\util\Log;
use Psr\Http\Message\ResponseInterface;

class Auth extends BaseRequest
{
    /**
     * 根据token获取用户信息
     * @return \GuzzleHttp\Promise\PromiseInterface
     * @see UserShortInfoDto
     */
    public function getUserByToken()
    {
        $client = $this->client;
        $client = $client->postAsync(ApiPath::GET_USERS_BY_TOKEN_URI);

        $client = $client->then(function (ResponseInterface $response) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);

            if (array_key_exists('code', $data) && $data['code'] == 200) {
                $user = $data['data'];
                return (new UserShortInfoDto())->load([
                    'userId' => $user['userId'],
                    'empId' => $user['employeeId'],
                    'employeeCode' => $user['account'],
                    'name' => $user['name'],
                    'enName' => $user['enName'],
                    'telPhone' => $user['telPhone']
                ]);
            }
            Log::instance()->error(ClientFactory::$logContent);

            throw new PlatformException($data['msg']);
        });


        return $client;

    }

    /**
     * 用老token换取新token
     * @param $token
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function refresh()
    {
        $client = ClientFactory::getClient($this->token);
        $client = $client->postAsync(ApiPath::REFRESH_TOKEN_URI);
        $client = $client->then(function (ResponseInterface $response) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);
            if (array_key_exists('code', $data) && $data['code'] == 200) {
                return (new TokenDto())->load([
                    'token' => $data['data'],
                ]);
            }
            Log::instance()->error(ClientFactory::$logContent);
            throw new PlatformException($data['msg']);
        });

        return $client;
    }

}
