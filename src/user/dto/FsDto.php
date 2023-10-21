<?php

namespace Platform\user\dto;
use Platform\util\BaseDto;

/**
 * 飞书
 */
class FsDto extends BaseDto
{
    /** @var 飞书union id */
    public $unionId;
    /** @var 飞书 user id */
    public $fsEmpId;

    /** @var ?string 飞书openid */
    public $openId;

    /** @var ?string 飞书app登录app的openid */
    public $appLoginAppOpenId;
}
