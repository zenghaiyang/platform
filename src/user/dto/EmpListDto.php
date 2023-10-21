<?php
/**
 * Desc: 员工列表信息
 * User: lca
 * Date-Time: 2023/9/25 14:28
 */

namespace Platform\user\dto;

use Platform\util\BaseDto;


class EmpListDto extends BaseDto
{
    /** @var EmpInfoDto 员工信息 */
    public $employee;

    /** @var UserShortInfoDto 用户信息 */
    public $user;

    /** @var OrgDto 组织信息 */
    public $organization;

    /** @var PostionDto 岗位信息 */
    public $position;

    /** @var FsDto 飞书信息 */
    public $feiShu;
}
