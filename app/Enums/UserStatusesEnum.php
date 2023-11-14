<?php

declare(strict_types=1);

namespace App\Enums;

enum UserStatusesEnum: int
{
    case Inactive = 0;
    case Active = 1;
    case Blocked = 2;

    public static function forSelect(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    public static function getNameByValue(int $value): string
    {
        $enum = self::from($value);

        return $enum->name ?? 'No';
    }
}
