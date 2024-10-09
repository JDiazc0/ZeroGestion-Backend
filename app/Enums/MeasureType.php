<?php

namespace App\Enums;


enum MeasureType: string
{
    case KILOGRAM = 'kg';
    case GRAM = 'g';
    case LITER = 'l';
    case MILLILITER = 'ml';
    case UNIT = 'unit';
    case METER = 'm';
    case CENTIMETER = 'cm';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function default(): self
    {
        return self::UNIT;
    }
}
