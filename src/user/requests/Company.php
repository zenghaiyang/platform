<?php
/**
 * Desc: 对接用户中心公司接口
 * User: 李锡荣
 * Date-Time: 2023/10/07 11:01
 */

namespace Platform\user\requests;

use Platform\exception\PlatformException;
use Platform\user\ApiPath;
use Platform\user\dto\CompanyDto;
use Platform\util\BaseRequest;
use Platform\util\ClientFactory;
use Platform\util\Log;
use Psr\Http\Message\ResponseInterface;

class Company extends BaseRequest
{
    /**
     * 查询所有公司列表
     * @param array $param
     * @return \GuzzleHttp\Promise\PromiseInterface
     * @author 李锡荣
     * @date 2023/10/7 10:12
     */
    public function getCompanyList(array $param): \GuzzleHttp\Promise\PromiseInterface
    {
        $jsonParam=[];
        if(isset($param['ids'])){
            $jsonParam['ids'] = $param['ids']; //公司Id,集合
        }
        if(isset($param['companyName'])&&!empty($param['companyName'])){
            $jsonParam['companyName'] = $param['companyName']; //公司名称
        }

        if(isset($param['companyEnName'])&&!empty($param['companyEnName'])){
            $jsonParam['companyEnName'] = $param['companyEnName']; //公司英文名称
        }

        $client = ClientFactory::getClient($this->token);
        $client = $client->postAsync(ApiPath::GET_ALL_COMPANY_LIST, ['json' => $jsonParam]);

        $client = $client->then(function (ResponseInterface $response) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);

            if (array_key_exists('code', $data) && $data['code'] == 200) {
                $return = [];
                foreach ($data['data'] ?? [] as $item) {
                    $return[] = (new CompanyDto())->load([
                        'companyId'          => $item['orgId'],
                        'companyType'        => $item['type'],
                        'companyName'        => $item['cnName'],
                        'companyEnName'      => $item['enName'],
                        'companyDescription' => $item['description'],
                        'shortName'          => $item['shortName'],
                        'addressCn'          => $item['addressCn'],
                        'addressEn'          => $item['addressEn'],
                        'provincName'        => $item['provincName'],
                        'cityName'           => $item['cityName'],
                    ]);
                }

                return $return;
            }

            Log::instance()->error(ClientFactory::$logContent);
            throw new PlatformException($data['msg']);
        });

        return $client;
    }


}
