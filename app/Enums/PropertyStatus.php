<?php

namespace App\Enums;

enum PropertyStatus: string
{
    case ForSale = 'for_sale';
    case ForRent = 'for_rent';
    case Sold = 'sold';
    case Rented = 'rented';

    public function label(): string
    {
        return match ($this) {
            self::ForSale => 'For sale',
            self::ForRent => 'For rent',
            self::Sold => 'Sold',
            self::Rented => 'Rented out',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::ForSale => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
            self::ForRent => 'bg-sky-100 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300',
            self::Sold => 'bg-zinc-200 text-zinc-700 dark:bg-zinc-700/50 dark:text-zinc-300',
            self::Rented => 'bg-zinc-200 text-zinc-700 dark:bg-zinc-700/50 dark:text-zinc-300',
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
