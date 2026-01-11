<?php

declare(strict_types=1);

namespace App\Enums;

enum Title: string
{
    case Mr = 'Mr';
    case Mrs = 'Mrs';
    case Miss = 'Miss';
    case Ms = 'Ms';
    case Dr = 'Dr';
    case Prof = 'Prof';

    public static function fromString(string $raw): ?self
    {
        $key = strtolower(rtrim(trim($raw), '.'));

        return match ($key) {
            'mr', 'mister' => self::Mr,
            'mrs' => self::Mrs,
            'miss' => self::Miss,
            'ms' => self::Ms,
            'dr' => self::Dr,
            'prof' => self::Prof,
            default => null,
        };
    }
}
