<?php

namespace App\Enum;

enum TravelOrderStatusEnum: string
{
    case REQUESTED = 'requested';
    case APPROVED = 'approved';
    case CANCELED = 'canceled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function valuesAsString(): string
    {
        return implode(',', self::values());
    }

}
