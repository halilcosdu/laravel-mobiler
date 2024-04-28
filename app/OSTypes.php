<?php

namespace App;

use Illuminate\Support\Str;

enum OSTypes: string
{
    case ios = 'ios';
    case android = 'android';
    case other = 'other';

    public static function getSelect(): array
    {
        return array_combine(
            array_map(fn ($osType) => $osType->name, self::cases()),
            array_map(fn ($osType) => Str::title($osType->value), self::cases())
        );
    }
}
