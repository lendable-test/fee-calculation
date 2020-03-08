<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Tests;

use Lendable\Interview\Interpolation\{
    Model\LoanModel,
    Service\FeeCalculatorService
};
use PHPUnit\Framework\TestCase;

/**
 * FeeCalculatorService test
 */
class FeeCalculatorServiceTest extends TestCase
{

    /**
     * Calculate function test
     *
     * @param int    $term              Term
     * @param float  $amount            Amount
     * @param float  $expectedFee       Expected fee
     * @param string $expectedException Expected exception
     *
     * @return void
     *
     * @dataProvider calculatorDataProvider
     */
    public function testCalculate(
        $term,
        $amount,
        float $expectedFee,
        string $expectedException = null
    ): void {
        if ($expectedException !== null) {
            $this->expectException($expectedException);
        }

        $loanModel = new LoanModel($term, $amount);
        $service = new FeeCalculatorService();

        $actualFee = $service->calculate($loanModel);

        $this->assertSame($expectedFee, $actualFee);
    }

    /**
     * Calculator data provider
     *
     * @return array
     */
    public function calculatorDataProvider(): array
    {
        return array_merge(
            $this->_defaultCalculatorDataProvider()
        );
    }

    /**
     * Default calculator data provider
     *
     * @return array
     */
    private function _defaultCalculatorDataProvider(): array
    {
        return [
            [12, 1000, 50.0],
            [12, 2000, 90.0],
            [12, 3000, 90.0],
            [12, 4000, 115.0],
            [12, 5000, 100.0],
            [12, 6000, 120.0],
            [12, 7000, 140.0],
            [12, 8000, 160.0],
            [12, 9000, 180.0],
            [12, 10000, 200.0],
            [12, 11000, 220.0],
            [12, 12000, 240.0],
            [12, 13000, 260.0],
            [12, 14000, 280.0],
            [12, 15000, 300.0],
            [12, 16000, 320.0],
            [12, 17000, 340.0],
            [12, 18000, 360.0],
            [12, 19000, 380.0],
            [12, 20000, 400.0],
            [24, 1000, 70.0],
            [24, 2000, 100.0],
            [24, 3000, 120.0],
            [24, 4000, 160.0],
            [24, 5000, 200.0],
            [24, 6000, 240.0],
            [24, 7000, 280.0],
            [24, 8000, 320.0],
            [24, 9000, 360.0],
            [24, 10000, 400.0],
            [24, 11000, 440.0],
            [24, 12000, 480.0],
            [24, 13000, 520.0],
            [24, 14000, 560.0],
            [24, 15000, 600.0],
            [24, 16000, 640.0],
            [24, 17000, 680.0],
            [24, 18000, 720.0],
            [24, 19000, 760.0],
            [24, 20000, 800.0],
        ];
    }
}
