<?php

namespace App\Enums;

abstract class Enum
{
    protected static $map;

    public static function map()
    {
        return static::$map;
    }

    public static function keys()
    {
        return array_keys(static::$map);
    }

    public static function translate($key = null, $default = '')
    {
        return static::$map[$key] ?? $default;
    }

    public static function validate($type)
    {
        return isset(static::$map[$type]);
    }
}
