<?php

namespace Platform\user\requests;

use Platform\exception\PlatformException;
use Platform\enum\JobStatus;
use Platform\enum\Sex;
use Platform\user\ApiPath;
use Platform\user\dto\CompanyDto;
use Platform\user\dto\EmpInfoDto;
use Platform\user\dto\FsDto;
use Platform\user\dto\OrgDto;
use Platform\user\dto\PostionDto;
use Platform\user\dto\UserDetailDto;
use Platform\user\dto\UserShortInfoDto;
use Platform\util\BaseRequest;
use Platform\util\ClientFactory;
use Platform\util\Log;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class User extends BaseRequest
{
    /**
     * 获取指定组织ID下的员工
     * @param int $orgId 组织ID
     * @param bool $includeSubordinates 是否包含下级部门
     * @param int $jobStatus 岗位状态
     * @param bool $isMasterPosition 是否主岗位
     * @return PromiseInterface
     * @throws PlatformException
     * @see UserShortInfoDto
     */
    public function getDepartmentEmp(int $orgId, bool $includeSubordinates = true, int $jobStatus = JobStatus::WORK, bool $isMasterPosition = true): PromiseInterface
    {
        $client = ClientFactory::getClient($this->token);
        $client = $client->postAsync(ApiPath::GET_DEPARTMENT_EMP_URI, ['json' => [
            'orgId' => $orgId,
            'isNeedLower' => $includeSubordinates,
            'jobStatus' => $jobStatus,
            'isMasterPosition' => $isMasterPosition
        ]]);

        return $this->userBodyHandle($client);
    }

    /**
     * 根据ID数组获取用户信息
     * @param array $ids
     * @return PromiseInterface
     * @throws PlatformException
     * @see UserShortInfoDto
     */
    public function getUserByIds(array $ids = []): PromiseInterface
    {
        $client = ClientFactory::getClient($this->token);
        $client = $client->postAsync(ApiPath::GET_USERS_BY_ID_URI, ['json' => [
            'userIds' => $ids,
            'isMasterPosition' => true
        ]]);

        return $this->userBodyHandle($client);
    }

    /**
     * 处理用户数据
     * @param PromiseInterface $client
     * @return PromiseInterface
     */
    private function userBodyHandle(PromiseInterface $client): PromiseInterface
    {
        return $client->then(function (ResponseInterface $response) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);
            $result = [];
            if (array_key_exists('code', $data) && $data['code'] == 200) {
                foreach ($data['data'] as $v) {
                    $result[] = (new UserShortInfoDto())->load($v);
                }
                return $result;
            }

            Log::instance()->error(ClientFactory::$logContent);
            throw new PlatformException($data['msg']);
        });
    }

    /**
     * 获取用户详情
     * @param $userId
     * @return PromiseInterface
     * @see UserDetailDto
     */
    public function getUserDetail($userId): PromiseInterface
    {
        $client = ClientFactory::getClient($this->token);
        $client = $client->postAsync(ApiPath::GET_USER_DETAIL_URL, ['json' => [
            'UserIds' => [$userId],
        ]]);

        $client = $client->then(function (ResponseInterface $response) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);
            if (array_key_exists('code', $data) && $data['code'] == 200) {
                $currentData = $data['data'][0];

                return $this->getUserDetailDto($currentData);
            }

            Log::instance()->error(ClientFactory::$logContent);
            throw new PlatformException($data['msg']);
        });

        return $client;
    }

    /**
     * 批量获取用户详情
     * @param $userIds
     * @return PromiseInterface
     * @see UserDetailDto[] id=>UserDetailDto
     */
    public function multiGetUserDetail($userIds = []): PromiseInterface
    {
        $client = ClientFactory::getClient($this->token);
        $client = $client->postAsync(ApiPath::MULTI_GET_USER_DETAIL_URL, ['json' => [
            'UserIds' => $userIds,
        ]]);

        $client = $client->then(function (ResponseInterface $response) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);
            if (array_key_exists('code', $data) && $data['code'] == 200) {
                $result = [];
                foreach ($data['data'] as $currentData) {
                    $result[$currentData['userInfo']['id']] = $this->getUserDetailDto($currentData);
                }

                return $result;
            }
        });

        return $client;
    }

    /**
     * 根据用户详情接口数据处理成dto
     * @param $currentData
     * @return UserDetailDto
     */
    private function getUserDetailDto($currentData): UserDetailDto
    {
        $currentUserInfo = $currentData['userInfo'];
        $userInfo = (new UserShortInfoDto())->load([
            'userId' => $currentUserInfo['id'],
            'empId' => $currentUserInfo['employeeId'],
            'name' => $currentUserInfo['name'],
            'enable' => $currentUserInfo['isEnable'],
            'telPhone' => $currentUserInfo['telPhone'],
            'employeeCode' => $currentUserInfo['account'],
        ]);

        $empINfoDto = (new EmpInfoDto())->load(
            [
                'empId' => $currentData['empInfo']['id'],
                'name' => $currentData['empInfo']['name'],
                'enName' => $currentData['empInfo']['enName'],
                'age' => $currentData['empInfo']['age'],
                'telPhone' => $currentData['empInfo']['telPhone'],
                'email' => $currentData['empInfo']['mail'],
                'idCard' => $currentData['empInfo']['idCard'],
                'employeeCode' => $currentData['empInfo']['employeeCode'],
                'address' => $currentData['empInfo']['address'],
                'managerName' => $currentData['empInfo']['managerName'],
                'managerCode' => $currentData['empInfo']['managerCode'],
                'education' => $currentData['empInfo']['education'] ?? null,
                'sex' => new Sex(),
                'jobStatus' => new JobStatus()
            ]
        );
        $empINfoDto->sex->setProperties(null, $currentData['empInfo']['sex']);
        $empINfoDto->jobStatus->setProperties(null, $currentData['empInfo']['jobStatus']);

        $fsDto = (new FsDto())->load([
            'unionId' => $currentData['fsInfo']['union_id'],
            'fsEmpId' => $currentData['fsInfo']['fs_employee_id']
        ]);

        $generalPositionInfo = $currentData['generalPositionInfo'];
        $positionData = [];
        foreach ($generalPositionInfo as $v) {
            $position = new PostionDto();
            $position->load($v);
            $orgDto = (new OrgDto())->load($v);

            $companyDto = (new CompanyDto())->load($v);

            $orgTree = [];
            $positionRelateOrgs = (array)$v['positionnRelateOrgs'];
            foreach ($positionRelateOrgs as $org) {
                $orgTree[] = (new OrgDto())->load($org);
            }

            $position->org = $orgDto;
            $position->company = $companyDto;
            $position->orgTree = $orgTree;
            $positionData[] = $position;
        }

        $userDetail = (new UserDetailDto());
        $userDetail->user = $userInfo;
        $userDetail->fsInfo = $fsDto;
        $userDetail->empInfo = $empINfoDto;
        $userDetail->positions = $positionData;

        return $userDetail;
    }

    /**
     * 通过自定义where条件获取用户信息
     * @param array $param 入参
     * @return PromiseInterface
     * @author lca
     * @date 2023/10/10 15:10
     */
    public function getUserInfoByCustomer(array $param): PromiseInterface
    {
        $jsonParam['ids'] = $param['user_ids'] ?? [];
        $jsonParam['employeeIds'] = $param['employee_ids'] ?? [];
        !empty($param['account']) && $jsonParam['Account'] = $param['Account'];
        !empty($param['name']) && $jsonParam['name'] = $param['name'];
        !empty($param['en_name']) && $jsonParam['enName'] = $param['en_name'];
        !empty($param['company_id']) && $jsonParam['companyId'] = $param['company_id'];
        !empty($param['dept_id']) && $jsonParam['deptId'] = $param['dept_id'];
        !empty($param['telephone']) && $jsonParam['telPhone'] = $param['telephone'];
        isset($param['is_enable']) && $jsonParam['isEnable'] = $param['is_enable'];

        $client = ClientFactory::getClient($this->token);
        $client = $client->postAsync(ApiPath::GET_USER_INFO_BY_CUSTOMER, ['json' => $jsonParam ?? []]);

        $client = $client->then(function (ResponseInterface $response) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);

            if (array_key_exists('code', $data) && (string)$data['code'] === '200') {
                $return = [];
                foreach ($data['data'] ?? [] as $user) {
                    $userDetail = new UserDetailDto();
                    $userDetail->user = new UserShortInfoDto();
                    $userDetail->user->load([
                        'userId' => $user['id'],
                        'empId' => $user['empId'],
                        'employeeCode' => $user['account'],
                        'name' => $user['userName'],
                        'enName' => $user['enName']
                    ]);

                    $userDetail->mainPosition = new PostionDto();
                    $userDetail->mainPosition->positionName = $user['jobName'];

                    $userDetail->mainPosition->company = new CompanyDto();
                    $userDetail->mainPosition->company->load([
                        'companyId' => $user['companyId'],
                        'companyName' => $user['companyName'],
                        'shortName' => $user['sCompanyName']
                    ]);

                    $userDetail->mainPosition->org = new OrgDto();
                    $userDetail->mainPosition->org->load([
                        'orgId' => $user['departmentId'],
                        'orgName' => $user['departmentName'],
                        'orgShortName' => $user['sDepartmentName']
                    ]);

                    $return[] = $userDetail;
                }

                return $return;
            }

            Log::instance()->error(ClientFactory::$logContent);
            throw new PlatformException($data['msg']);
        });

        return $client;
    }

    /**
     * 通过公司id或部门id集合获取对应下所有用户id
     * @param array $param
     * @return PromiseInterface
     * @author lca
     * @date 2023/10/16 16:09
     */
    public function getUserIdByOrgIds(array $param): PromiseInterface
    {
        $jsonParam['orgIds'] = array_values($param['organization_ids'] ?? []);
        !empty($param['job_status']) && $jsonParam['jobStatus'] = $param['job_status'];
        !empty($param['is_master_position']) && $jsonParam['isMasterPosition'] = $param['is_master_position'];

        $client = ClientFactory::getClient($this->token);
        $client = $client->postAsync(ApiPath::GET_USER_ID_BY_ORG_IDS, ['json' => $jsonParam ?? []]);

        $client = $client->then(function (ResponseInterface $response) {
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);
            if (array_key_exists('code', $data) && (string)$data['code'] === '200') {
                return $data['data'] ?? [];
            }

            Log::instance()->error(ClientFactory::$logContent);
            throw new PlatformException($data['msg']);
        });

        return $client;
    }
}
