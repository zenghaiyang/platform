<?php
/**
 * Desc: 岗位
 * User: lca
 * Date-Time: 2023/10/10 14:33
 */
namespace Platform\user\dto;

use Platform\util\BaseDto;

class PostionDto extends BaseDto
{
    /** @var ?int 岗位员工ID */
    public $id;
    /** @var ?int 员工id */
    public $employeeId;
    /** @var ?int 岗位id */
    public $positionId;
    /** @var ?string 岗位名称 */
    public $positionName;
    /** @var ?string 岗位英文名称 */
    public $positionEnName;
    /** @var ?int 岗位类型 */
    public $positionType;
    /** @var ?int 岗位类型名称 */
    public $positionTypeName;
    /** @var ?string 描述信息 */
    public $positionDescription;
    /** @var ?string 任职时间 */
    public $workingTime;
    /** @var ?bool 是否主岗位 */
    public $isMasterPosition;
    /** @var ?int 岗位身份ID */
    public $identityId;
    /** @var ?string 岗位身份 */
    public $identityName;
    /** @var OrgDto 组织信息 */
    public $org;
    /** @var CompanyDto 公司信息 */
    public $company;
    /** @var OrgDto[] 组织树 由近到远 不包含离自己最近的那个节点 */
    public $orgTree = [];
    /** @var ?string 岗位code */
    public $positionCode;
    /** @var ?string 职级 */
    public $level;
    /** @var ?string 状态 */
    public $isEnable;

    /**
     * 是否是主岗位
     * @return bool
     */
    public function isMaster(): bool
    {
        return $this->isMasterPosition === true;
    }

    /**
     * 获取组织树包含离自己最近的那个节点
     */
    public function getOrgTreeAndSelf(): array
    {
        $orgTree = $this->orgTree;
        array_unshift($orgTree,$this->org);

        return $orgTree;
    }
}
