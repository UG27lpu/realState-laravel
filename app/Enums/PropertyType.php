<?php

namespace App\Enums;

enum PropertyType: string
{
    case Land = 'land';
    case House = 'house';
    case Apartment = 'apartment';
    case Commercial = 'commercial';

    public function label(): string
    {
        return match ($this) {
            self::Land => 'Land / Plot',
            self::House => 'Independent House',
            self::Apartment => 'Apartment / Flat',
            self::Commercial => 'Commercial Space',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Land => 'map',
            self::House => 'home',
            self::Apartment => 'building-2',
            self::Commercial => 'briefcase',
        };
    }

    public static function options(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            $out[$case->value] = $case->label();
        }
        return $out;
    }
}
