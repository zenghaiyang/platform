<?php
/**
 * Desc: 公司信息
 * User: lca
 * Date-Time: 2023/10/10 14:16
 */
namespace Platform\user\dto;

use Platform\util\BaseDto;

class CompanyDto extends BaseDto
{
    /** @var ?int 公司ID */
    public $companyId;
    /** @var ?int 公司类型 */
    public $companyType;
    /** @var ?string 公司中文名 */
    public $companyName;
    /** @var ?string 公司英文名 */
    public $companyEnName;
    /** @var ?string 编制公司 */
    public $preparationCompanyName;
    /** @var ?string 公司说明 */
    public $companyDescription;
    /** @var ?int 合同公司ID */
    public $contractCompanyId;
    /** @var ?int 合同公司类型 */
    public $contractCompanyType;
    /** @var ?string 合同公司中文名 */
    public $contractCompanyName;
    /** @var ?string 合同公司英文名 */
    public $contractCompanyEnName;
    /** @var ?string 合同公司说明 */
    public $contractCompanyDescription;
    /** @var ?string 公司简称 */
    public $shortName;
    /** @var ?string 中文地址 */
    public $addressCn;
    /** @var ?string 英文地址 */
    public $addressEn;
    /** @var ?string 省份 */
    public $provincName;
    /** @var ?string 城市 */
    public $cityName;
}
