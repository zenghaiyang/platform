<?php

namespace Platform\enum;

final class ReadType
{
    //0-未读，1-已读未处理 2.已处理
    public const READ_TYPE_0 = 0;
    public const READ_TYPE_1 = 1;
    public const READ_TYPE_2 = 2;

    public const READ_TYPE_0_TEXT = '未读';
    public const READ_TYPE_1_TEXT = '已读未处理';
    public const READ_TYPE_2_TEXT = '已处理';

    private static $arr = [
        self::READ_TYPE_0,
        self::READ_TYPE_1,
        self::READ_TYPE_2,
    ];

    private static $text = [
        self::READ_TYPE_0 => self::READ_TYPE_0_TEXT,
        self::READ_TYPE_1 => self::READ_TYPE_1_TEXT,
        self::READ_TYPE_2 => self::READ_TYPE_2_TEXT,
    ];

    public static function getArr(){
        return self::$arr;
    }

    public static function getText(){
        return self::$text;
    }


}
