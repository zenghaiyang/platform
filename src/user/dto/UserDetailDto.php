<?php

namespace Platform\user\dto;

use components\exception\PlatformException;
use Platform\util\BaseDto;

class UserDetailDto extends BaseDto
{
    /** @var UserShortInfoDto 用户基本信息*/
    public $user;
    /** @var FsDto 飞书信息*/
    public $fsInfo;
    /** @var EmpInfoDto  员工信息*/
    public $empInfo;
    /** @var ?PostionDto 主岗位 */
    public $mainPosition;
    /** @var PostionDto[] 岗位 */
    public $positions;

    /**
     * 获取主岗位信息
     * @return PostionDto
     */
    public function getMasterPosition(): ?PostionDto
    {
        foreach ($this->positions as $v) {
            if ($v->isMaster()) {
                return $v;
            }
        }

        throw new PlatformException('没有获取到主岗位');
    }

    /**
     * 获取公司id
     * @return int
     * @author: 杨荣钦
     * @date: 2023/10/9 14:10
     */
    public function getCompanyId(): int
    {
        $masterPosition = $this->getMasterPosition();

        return $masterPosition->company->companyId ?? $masterPosition->company['companyId'] ?? 0;
    }
}
