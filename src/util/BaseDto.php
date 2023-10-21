<?php

namespace Platform\util;



class BaseDto
{
    /** @var int 默认分页页码 */
    public const PAGE_INDEX = 1;

    /** @var int 默认分页条数 */
    public const PAGE_SIZE = 20;

    public function load($data = [])
    {
        foreach ($data as $key => $value) {
            property_exists($this, $key) && $this->$key = $value;
        }

        return $this;
    }


}
