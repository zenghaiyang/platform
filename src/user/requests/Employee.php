<?php
/**
 * Desc: 对接用户中心员工接口
 * User: lca
 * Date-Time: 2023/9/25 11:01
 */

namespace Platform\user\requests;

use Platform\exception\PlatformException;
use Platform\enum\JobStatus;
use Platform\user\ApiPath;
use Platform\user\dto\EmpInfoDto;
use Platform\user\dto\EmpListDto;
use Platform\user\dto\FsDto;
use Platform\user\dto\OrgDto;
use Platform\user\dto\PostionDto;
use Platform\user\dto\UserDetailDto;
use Platform\user\dto\UserShortInfoDto;
use Platform\util\BaseDto;
use Platform\util\BaseRequest;
use Platform\util\ClientFactory;
use Platform\util\Log;
use Psr\Http\Message\ResponseInterface;

class Employee extends BaseRequest
{
    /**
     * 查询员工分页列表
     * @param array $param 入参：['page' => 分页页码, 'list_rows' => 分页条数, 'name' => 员工中文名, ...]
     * @return \GuzzleHttp\Promise\PromiseInterface
     * @see [{@see EmpListDto}, ...]
     * @author lca
     * @date 2023/9/25 15:00
     */
    public function getEmpInfoPageList(array $param): \GuzzleHttp\Promise\PromiseInterface
    {
        isset($param['organization_id']) && $jsonParam['id'] = $param['organization_id']; //组织id：查询组织下面的员工
        isset($param['employee_id']) && $jsonParam['empId'] = $param['employee_id']; //员工id
        $jsonParam['name'] = $param['name'] ?? ''; //员工中文名
        $jsonParam['enName'] = $param['en_name'] ?? ''; //员工英文名
        $jsonParam['employeeCode'] = $param['employee_code'] ?? ''; //员工工号
        isset($param['job_status']) && $jsonParam['jobStatus'] = $param['job_status']; //员工在职状态
        $jsonParam['pageIndex'] = $param['page'] ?? BaseDto::PAGE_INDEX; //分页页码
        $jsonParam['pageSize'] = $param['list_rows'] ?? BaseDto::PAGE_SIZE; //分页条数

        $client = ClientFactory::getClient($this->token);
        $client = $client->postAsync(ApiPath::GET_EMP_INFO_PAGE_LIST, ['json' => $jsonParam]);

        $client = $client->then(function (ResponseInterface $response) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);

            if (array_key_exists('code', $data) && $data['code'] == 200) {
                $return = [];
                foreach ($data['data']['list'] ?? [] as $item) {
                    $return[] = $this->getEmpListDto($item ?? []);
                }

                return $return;
            }

            Log::instance()->error(ClientFactory::$logContent);
            throw new PlatformException($data['msg']);
        });

        return $client;
    }

    /**
     * 员工列表信息处理
     * @param array $employee 员工列表信息
     * @return EmpListDto
     * @author lca
     * @date 2023/9/25 14:59
     */
    private function getEmpListDto(array $employee): EmpListDto
    {
        $empListDto = new EmpListDto();

        $empListDto->employee = new EmpInfoDto();
        $empListDto->employee->load([
            'empId' => $employee['id'] ?? null,
            'name' => $employee['name'] ?? null,
            'employeeCode' => $employee['employeeCode'] ?? null,
            'telPhone' => $employee['telPhone'] ?? null
        ]);
        $empListDto->employee->jobStatus = new JobStatus();
        $empListDto->employee->jobStatus->setProperties($employee['jobStatus'] ?? null);

        $empListDto->user = new UserShortInfoDto();
        $empListDto->user->load([
            'userId' => $employee['userId'] ?? null,
            'empId' => $employee['id'] ?? null,
            'name' => $employee['name'] ?? null,
            'employeeCode' => $employee['employeeCode'] ?? null,
            'telPhone' => $employee['telPhone'] ?? null
        ]);

        $empListDto->organization = new OrgDto();
        $empListDto->organization->load([
            'orgId' => $employee['orgId'] ?? null,
            'orgName' => $employee['orgName'] ?? null
        ]);

        $empListDto->position = new PostionDto();
        $empListDto->position->load([
            'positionId' => $employee['posintioId'] ?? null,
            'positionName' => $employee['posintioName'] ?? null,
            'employeeId' => $employee['id'] ?? null
        ]);

        $empListDto->feiShu = new FsDto();
        $empListDto->feiShu->load([
            'openId'            => $employee['fsOpenId'] ?? null,
            'appLoginAppOpenId' => $employee['fsAppLoginAppOpenId'] ?? null
        ]);

        return $empListDto;
    }

    /**
     * 通过岗位Code、id集合查找员工
     * @param $param
     * @return \GuzzleHttp\Promise\PromiseInterface
     * @author: 杨荣钦
     * @date: 2023/10/12 16:23
     */
    public function getEmpInfoByPositionCodeIds($param)
    {
        $jsonParam = [];
        if (!empty($param['position_codes'])) { //岗位code集合
            $jsonParam['PositionCodes'] = $param['position_codes'];
        }
        if (!empty($param['position_ids'])) { //岗位Id集合
            $jsonParam['PositionIds'] = $param['position_ids'];
        }
        if (!empty($param['keyword'])) { //模糊匹配用户名称、用户英文名、账户
            $jsonParam['KeyWord'] = $param['keyword'];
        }
        if (!empty($param['org_id'])) { //组织id
            $jsonParam['OrgId'] = $param['org_id'];
        }

        //默认为false，传true副岗位也会查询
        $jsonParam['IsIncludeSubPosition'] = !empty($param['is_include_sub_position']);

        //默认为false，选择包含下级组织Orgid需要有值
        $jsonParam['IsIncludeSubOrgNode'] = !empty($param['is_include_sub_org_node']);

        $client = ClientFactory::getClient($this->token);
        $client = $client->postAsync(ApiPath::GET_EMP_INFO_BY_POSITION_CODE_IDS, ['json' => $jsonParam]);

        $client = $client->then(function (ResponseInterface $response) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);
//            dd($data);
            if (array_key_exists('code', $data) && $data['code'] == 200) {
                $return = [];
                foreach ($data['data'] ?? [] as $item) {
                    $temp          = new UserDetailDto();
                    $temp->empInfo = new EmpInfoDto();
                    $temp->empInfo->load([
                        'empId'  => $item['empId'],
                        'name'   => $item['empCnName'],
                        'enName' => $item['empEnName'],
                    ]);
                    $temp->empInfo->jobStatus = new JobStatus();
                    $temp->empInfo->jobStatus->setProperties($item['jobStatus'] ?? null);

                    $temp->organization = new OrgDto();
                    $temp->organization->load([
                        'orgId'   => $item['coId'] ?? null,
                        'orgName' => $item['coName'] ?? null
                    ]);

                    $temp->user = new UserShortInfoDto();
                    $temp->user->load([
                        'userId'       => $item['userId'] ?? null,
                        'enName'       => $item['userEnName'] ?? null,
                        'name'         => $item['userCnName'] ?? null,
                        'employeeCode' => $item['employeeCode'] ?? null,
                    ]);

                    $temp->positions = new PostionDto();
                    $temp->positions->load([
                        'positionId'       => $item['positionId'] ?? null,
                        'positionCode'     => $item['positionCode'] ?? null,
                        'positionName'     => $item['positionName'] ?? null,
                        'positionEnName'   => $item['positionEnName'] ?? null,
                        'isMasterPosition' => $item['isMasterPosition'] ?? null
                    ]);

                    $return[] = $temp;
                }

                return $return;
            }

            Log::instance()->error(ClientFactory::$logContent);
            throw new PlatformException($data['msg']);
        });

        return $client;
    }
}
