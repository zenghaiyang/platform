<?php
/**
 * Desc: 性别
 * User: lca
 * Date-Time: 2023/9/26 10:09
 */

namespace Platform\enum;

final class Sex
{
    public const UNKNOWN = 0;

    public const MAN = 1;

    public const WOMAN = 2;

    public const UNKNOWN_TEXT = '未知';

    public const MAN_TEXT = '男';

    public const WOMAN_TEXT = '女';

    public $sex;

    public $sexText;

    /**
     * 设置属性
     * @param ?int $sex 性别
     * @param ?string $sexText 性别描述
     * @author lca
     * @date 2023/9/27 15:23
     */
    public function setProperties(int $sex = null, string $sexText = null): void
    {
        if ($sex === self::MAN || $sexText === self::MAN_TEXT) {
            $this->sex = self::MAN;
            $this->sexText = self::MAN_TEXT;
        } elseif ($sex === self::WOMAN || $sexText === self::WOMAN_TEXT) {
            $this->sex = self::WOMAN;
            $this->sexText = self::WOMAN_TEXT;
        } elseif ($sex === self::UNKNOWN || $sexText === self::UNKNOWN_TEXT) {
            $this->sex = self::UNKNOWN;
            $this->sexText = self::UNKNOWN_TEXT;
        }
    }

    /**
     * 转换性别
     * @return array|null
     * @author lca
     * @date 2023/9/26 10:14
     */
    public function getSexArr(): ?array
    {
        if ($this->sex === self::MAN || $this->sexText === self::MAN_TEXT) {
            return ['sex' => self::MAN, 'sex_text' => self::MAN_TEXT];
        }

        if ($this->sex === self::WOMAN || $this->sexText === self::WOMAN_TEXT) {
            return ['sex' => self::WOMAN, 'sex_text' => self::WOMAN_TEXT];
        }

        if ($this->sex === self::UNKNOWN || $this->sexText === self::UNKNOWN_TEXT) {
            return ['sex' => self::UNKNOWN, 'sex_text' => self::UNKNOWN_TEXT];
        }

        return null;
    }
}
