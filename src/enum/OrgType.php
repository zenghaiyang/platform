<?php
/**
 * Desc: 组织类型
 * User: lca
 * Date-Time: 2023/10/9 19:32
 */

namespace Platform\enum;

class OrgType
{
    /** @var int 集团 */
    public const ORG_TYPE_1 = 1;
    public const ORG_TYPE_1_TEXT = '集团';

    /** @var int 集团部门 */
    public const ORG_TYPE_2 = 2;
    public const ORG_TYPE_2_TEXT = '集团部门';

    /** @var int 子品牌 */
    public const ORG_TYPE_3 = 3;
    public const ORG_TYPE_3_TEXT = '子品牌';

    /** @var int 子品牌分类 */
    public const ORG_TYPE_4 = 4;
    public const ORG_TYPE_4_TEXT = '子品牌分类';

    /** @var int 子品牌公司 */
    public const ORG_TYPE_5 = 5;
    public const ORG_TYPE_5_TEXT = '子品牌公司';

    /** @var int 集团中心 */
    public const ORG_TYPE_6 = 6;
    public const ORG_TYPE_6_TEXT = '集团中心';

    /** @var int 总公司 */
    public const ORG_TYPE_7 = 7;
    public const ORG_TYPE_7_TEXT = '总公司';

    /** @var int 子公司 */
    public const ORG_TYPE_8 = 8;
    public const ORG_TYPE_8_TEXT = '子公司';

    /** @var int 区域 */
    public const ORG_TYPE_9 = 9;
    public const ORG_TYPE_9_TEXT = '区域';

    /** @var int 分公司 */
    public const ORG_TYPE_10 = 10;
    public const ORG_TYPE_10_TEXT = '分公司';

    /** @var int 部门 */
    public const ORG_TYPE_11 = 11;
    public const ORG_TYPE_11_TEXT = '部门';

    /** @var int 小组 */
    public const ORG_TYPE_12 = 12;
    public const ORG_TYPE_12_TEXT = '小组';

    /** @var int[] 组织 */
    public const COMPANY_TYPE_ARR = [
        OrgType::ORG_TYPE_1,
        OrgType::ORG_TYPE_5,
        OrgType::ORG_TYPE_7,
        OrgType::ORG_TYPE_8,
        OrgType::ORG_TYPE_10
    ];

    /** @var int[] 组织部门 */
    public const DEPARTMENT_TYPE_ARR = [OrgType::ORG_TYPE_2, OrgType::ORG_TYPE_11, OrgType::ORG_TYPE_12];
}
