<?php

namespace Platform\user\dto;

use Platform\enum\JobStatus;
use Platform\enum\Sex;
use Platform\enum\WorkType;
use Platform\util\BaseDto;

/**
 * 员工信息
 */
class EmpInfoDto extends BaseDto
{
    /** @var 员工ID */
    public $empId;
    /** @var 员工姓名 */
    public $name;
    /** @var 英文名 */
    public $enName;
    /** @var Sex 性别 */
    public $sex;
    /** @var 年龄 */
    public $age;
    /** @var 电话 */
    public $telPhone;
    /** @var 邮箱 */
    public $email;
    /** @var 身份证号 */
    public $idCard;
    /** @var 工号 */
    public $employeeCode;
    /** @var WorkType 用工类型 */
    public $workType;
    /** @var 工作地址 */
    public $address;
    /** @var JobStatus 在职状态 */
    public $jobStatus;

    /** @var 直属上级 */
    public $managerName;
    /** @var 上级工号 */
    public $managerCode;

    /** @var ?string 学历 */
    public $education;
}
