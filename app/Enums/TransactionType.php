<?php

namespace App\Enums;

enum TransactionType: string
{
    case INCOME = 'income';
    case EGRESS = 'egress';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
