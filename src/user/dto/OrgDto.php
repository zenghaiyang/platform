<?php
/**
 * Desc: 组织信息
 * User: lca
 * Date-Time: 2023/10/10 14:15
 */
namespace Platform\user\dto;
use Platform\util\BaseDto;

class OrgDto extends BaseDto
{
    /** @var ?int 组织ID */
    public $orgId;
    /** @var ?int 组织类型 */
    public $orgType;
    /** @var ?string 组织代码 */
    public $orgCode;
    /** @var ?string 组织名称 */
    public $orgName;
    /** @var ?string 组织英文名 */
    public $orgEnName;
    /** @var ?string 简称 */
    public $orgShortName;
    /** @var ?string 说明 */
    public $orgDescription;
}
