<?php

namespace App\Services;

class EmiCalculatorService
{
    /**
     * Standard amortising EMI:
     *   EMI = P * R * (1 + R)^N / ((1 + R)^N - 1)
     *
     * @param float $principal      Loan principal.
     * @param float $annualRate     Annual interest rate in percent (e.g. 8.5).
     * @param int   $tenureMonths   Number of monthly instalments.
     */
    public function compute(float $principal, float $annualRate, int $tenureMonths): array
    {
        $principal = max(0.0, $principal);
        $tenureMonths = max(1, $tenureMonths);

        $monthlyRate = ($annualRate / 12) / 100;

        if ($monthlyRate <= 0) {
            $emi = $principal / $tenureMonths;
        } else {
            $factor = pow(1 + $monthlyRate, $tenureMonths);
            $emi    = $principal * $monthlyRate * $factor / ($factor - 1);
        }

        $emi = round($emi, 2);
        $totalPayable = round($emi * $tenureMonths, 2);
        $totalInterest = round($totalPayable - $principal, 2);

        return [
            'monthly_emi'    => $emi,
            'total_payable'  => $totalPayable,
            'total_interest' => $totalInterest,
            'principal'      => $principal,
            'tenure_months'  => $tenureMonths,
            'annual_rate'    => $annualRate,
            'schedule'       => $this->amortisation($principal, $monthlyRate, $emi, $tenureMonths),
        ];
    }

    /**
     * @return array<int, array{month:int, principal:float, interest:float, balance:float}>
     */
    private function amortisation(float $principal, float $monthlyRate, float $emi, int $tenure): array
    {
        $balance = $principal;
        $rows = [];

        for ($i = 1; $i <= $tenure; $i++) {
            $interest  = round($balance * $monthlyRate, 2);
            $principalPart = round($emi - $interest, 2);
            $balance   = max(0.0, round($balance - $principalPart, 2));
            $rows[] = [
                'month'     => $i,
                'principal' => $principalPart,
                'interest'  => $interest,
                'balance'   => $balance,
            ];
        }

        return $rows;
    }
}
