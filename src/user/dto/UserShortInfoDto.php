<?php

namespace Platform\user\dto;

use Platform\enum\Enable;
use Platform\util\BaseDto;

class UserShortInfoDto extends BaseDto
{
    /** @var ?int 用户ID */
    public $userId;
    /** @var ?int 员工id */
    public $empId;
    /** @var ?string 姓名 */
    public $name;
    /** @var ?string 英文名 */
    public $enName;
    /** @var ?bool 是否启用 */
    public $enable = Enable::ON;
    /** @var ?string 联系电话 */
    public $telPhone;
    /** @var ?string 工号(eg:hb0001) */
    public $employeeCode;
}
