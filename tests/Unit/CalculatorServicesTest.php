<?php

namespace Tests\Unit;

use App\Services\EmiCalculatorService;
use Tests\TestCase;

class CalculatorServicesTest extends TestCase
{
    public function test_emi_for_known_inputs(): void
    {
        $result = (new EmiCalculatorService())->compute(5_000_000, 8, 240);

        $this->assertEquals(240, count($result['schedule']));
        $this->assertGreaterThan(41_000, $result['monthly_emi']);
        $this->assertLessThan(42_500, $result['monthly_emi']);
        $this->assertGreaterThan(5_000_000, $result['total_payable']);
    }

    public function test_emi_with_zero_interest(): void
    {
        $result = (new EmiCalculatorService())->compute(120_000, 0, 12);

        $this->assertEqualsWithDelta(10_000.0, $result['monthly_emi'], 0.01);
        $this->assertEqualsWithDelta(0.0, $result['total_interest'], 0.01);
    }
}
