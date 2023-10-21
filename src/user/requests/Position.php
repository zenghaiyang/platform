<?php
/**
 *
 * User: 杨荣钦
 * Date-Time: 2023/10/12 9:10
 */

namespace Platform\user\requests;

use Platform\exception\PlatformException;
use Platform\user\ApiPath;
use Platform\user\dto\PostionDto;
use Platform\util\BaseRequest;
use Platform\util\ClientFactory;
use Platform\util\Log;
use Psr\Http\Message\ResponseInterface;

class Position extends BaseRequest
{
    /**
     * 获取组织和岗位关联信息
     * @param $param
     * @return \GuzzleHttp\Promise\PromiseInterface
     * @author: 杨荣钦
     * @date: 2023/10/12 11:07
     */
    public function getPositionOrg($param)
    {
        $jsonParam = [];
        if (!empty($param['position_ids'])) { //岗位id集
            $jsonParam['positionIds'] = $param['position_ids'];
        }
        if (!empty($param['position_name'])) { //岗位名称
            $jsonParam['positionName'] = $param['position_name'];
        }
        if (!empty($param['org_ids'])) { //组织id集
            $jsonParam['orgIds'] = $param['org_ids'];
        }
        if (!empty($param['org_name'])) { //组织名称
            $jsonParam['orgName'] = $param['org_name'];
        }
        //是否包含下级组织，默认为false
        $jsonParam['IsIncludeSubOrgNode'] = !empty($param['is_include_sub_org_node']);

        $client = ClientFactory::getClient($this->token);
        $client = $client->postAsync(ApiPath::GET_POSITION_ORG, ['json' => $jsonParam]);

        $client = $client->then(function (ResponseInterface $response) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);

            if (array_key_exists('code', $data) && $data['code'] == 200) {
                $return = [];
                foreach ($data['data'] ?? [] as $item) {
                    $return[] = (new PostionDto())->load([
                        'positionId' => $item['positionId'],
                        'positionName' => $item['positionName'],
                        'positionEnName' => $item['positionEnName'],
                        'org' => [
                            'id' => $item['orgId'],
                            'name' => $item['orgName'],
                            'enName' => $item['orgEnName'],
                            'shortName' => $item['orgShortName'],
                        ]
                    ]);
                }

                return $return;
            }

            Log::instance()->error(ClientFactory::$logContent);
            throw new PlatformException($data['msg']);

        });

        return $client;
    }

    /**
     * 所有岗位列表
     * @param $param
     * @return \GuzzleHttp\Promise\PromiseInterface
     * @author: 杨荣钦
     * @date: 2023/10/13 14:23
     */
    public function getListQuery($param = [])
    {
        $jsonParam = [];
        if (!empty($param['name'])) { //岗位名称
            $jsonParam['Name'] = $param['name'];
        }
        if (!empty($param['en_name'])) { //岗位英文名称
            $jsonParam['EnName'] = $param['en_name'];
        }

        if (!empty($param['position_type'])) { //岗位分类
            $jsonParam['PositionType'] = $param['position_type'];
        }

        $jsonParam['IsEnable'] = !empty($param['is_enable']);//是否启用


        $client = ClientFactory::getClient($this->token);
        $client = $client->postAsync(ApiPath::GET_POSITION_LIST, ['json' => $jsonParam]);
        $client = $client->then(function (ResponseInterface $response) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);

            if (array_key_exists('code', $data) && $data['code'] == 200) {
                $return = [];
                foreach ($data['data'] ?? [] as $item) {
                    $return[] = (new PostionDto())->load([
                        'positionId'          => $item['id'],
                        'positionName'        => $item['name'],
                        'positionEnName'      => $item['enName'],
                        'positionCode'        => $item['code'],
                        'level'               => $item['level'],
                        'positionType'        => $item['positionType'],
                        'positionTypeName'    => $item['positionTypeName'],
                        'positionDescription' => $item['description'],
                        'isEnable'            => $item['isEnable'],
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