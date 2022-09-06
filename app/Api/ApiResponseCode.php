<?php

namespace App\Api;

class ApiResponseCode
{
    const SUCCESS = 0;

    const DATA_FIELD_INVALID = 350;

    public static $responseCodeMap = [
        self::SUCCESS => 'success',
        self::DATA_FIELD_INVALID => '参数错误'
    ];

    public static function getMessage($code)
    {
        return isset($code, self::$responseCodeMap) ? self::$responseCodeMap [$code] : '';
    }
}