<?php

namespace App\Services;

class InvestmentReturnService
{
    /**
     * Compound growth projection with optional yearly contributions and
     * rental yield assumption.
     *
     * @param float $principal      Initial outlay.
     * @param float $annualGrowth   Capital appreciation rate (% per year).
     * @param int   $years          Investment horizon in years.
     * @param float $annualContribution Optional yearly top-up.
     * @param float $rentalYield    Optional rental yield (% of property value).
     */
    public function project(
        float $principal,
        float $annualGrowth,
        int $years,
        float $annualContribution = 0.0,
        float $rentalYield = 0.0,
    ): array {
        $principal  = max(0.0, $principal);
        $years      = max(1, $years);
        $growthRate = $annualGrowth / 100;
        $rentRate   = $rentalYield / 100;

        $value      = $principal;
        $totalIn    = $principal;
        $totalRent  = 0.0;
        $schedule   = [];

        for ($y = 1; $y <= $years; $y++) {
            $rental = $value * $rentRate;
            $totalRent += $rental;

            $value = ($value + $annualContribution) * (1 + $growthRate);
            $totalIn += $annualContribution;

            $schedule[] = [
                'year'      => $y,
                'value'     => round($value, 2),
                'rental'    => round($rental, 2),
                'invested'  => round($totalIn, 2),
            ];
        }

        $finalValue = round($value, 2);
        $totalReturn = round($finalValue + $totalRent - $totalIn, 2);

        return [
            'final_value'    => $finalValue,
            'total_invested' => round($totalIn, 2),
            'total_rental'   => round($totalRent, 2),
            'total_return'   => $totalReturn,
            'roi_percent'    => $totalIn > 0 ? round(($totalReturn / $totalIn) * 100, 2) : 0,
            'schedule'       => $schedule,
        ];
    }
}
