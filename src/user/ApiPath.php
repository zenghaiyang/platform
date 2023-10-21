<?php

namespace Platform\user;

class ApiPath
{
    /** @var string 获取指定部门下的员工 */
    public const GET_DEPARTMENT_EMP_URI = '/userApi/User/GetAllUserByOrgId';
    /** @var string 批量获取指定id的用户简要信息 */
    public const GET_USERS_BY_ID_URI = '/userApi/User/GetAllUserByOrgId';
    /** @var string 根据token获取用户信息 */
    public const GET_USERS_BY_TOKEN_URI = '/userApi/User/GetCurrentRequestUser';
    /** @var string token续期 */
    public const REFRESH_TOKEN_URI = '/userApi/OAuth/GetRefreshToken';
    /** @var string 获取用户详情 */
    public const GET_USER_DETAIL_URL = '/userApi/User/GetUserDetialInfoByIds';
    /** @var string 批量获取用户详情 */
    public const MULTI_GET_USER_DETAIL_URL = '/userApi/User/GetUserDetialInfoByIds';
    /** @var string 查询员工分页列表 */
    public const GET_EMP_INFO_PAGE_LIST = '/userApi/Employee/GetEmpInfoPageList';
    /** @var string  获取所有公司 */
    public const GET_ALL_COMPANY_LIST = '/userApi/Organization/GetAllCompanyList';

    public const GET_LIST_INTERNAL_ACCOUNT = '/financeApi/Bank/GetListInternalAccount';
    /** @var string 通过自定义where条件获取用户信息 */
    public const GET_USER_INFO_BY_CUSTOMER = '/userApi/User/GetUserInfoByCustomer';

    /** @var string 获取岗位与组织的关联信息 */
    public const GET_POSITION_ORG = '/userApi/Position/GetPositionOrg';

    /** @var string 通过岗位Code、id集合查找员工 */
    public const GET_EMP_INFO_BY_POSITION_CODE_IDS = '/userApi/Employee/GetEmpInfoByPositionCodeIds';
    /** @var string 所有岗位列表 */
    public const GET_POSITION_LIST = '/userApi/Position/GetPositionList';

    /** @var string 通过公司Id或部门Id集合获取对应下所有用户Id */
    public const GET_USER_ID_BY_ORG_IDS = '/userApi/User/GetUserIdByOrgIds';
}
