<?php

namespace App\Enums;

class TestEnum extends Enum
{
    const WORKING = 'working';

    const FINISH = 'finish';

    protected static $map = [
        self::WORKING => '正在进行',
        self::FINISH => '已完工'
    ];
}
