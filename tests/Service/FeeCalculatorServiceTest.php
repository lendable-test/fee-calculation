<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Tests;

use Lendable\Interview\Interpolation\{
    Model\LoanModel,
    Service\AbstractService,
    Component\Exception\ValidationException
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
        $actualFee = AbstractService::factory('feeCalculator')
            ->calculate($loanModel);

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
            $this->_defaultCalculatorDataProvider(),
            $this->_freeIntAmountCalculatorDataProvider(),
            $this->_freeFloatAmountCalculatorDataProvider(),
            $this->_freeTermCalculatorDataProvider(),
            $this->_freeTermAndAmountCalculatorDataProvider(),
            $this->_incorrectDataCalculatorDataProvider()
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

    /**
     * Free int amount calculator data provider
     *
     * @return array
     */
    private function _freeIntAmountCalculatorDataProvider(): array
    {
        return [
            [12, 1001, 54.0],
            [12, 1002, 53.0],
            [12, 1003, 52.0],
            [12, 1004, 51.0],
            [12, 1005, 55.0],
            [12, 1006, 54.0],
            [12, 1007, 53.0],
            [12, 1008, 52.0],
            [12, 1009, 51.0],
            [12, 1010, 55.0],
            [12, 1400, 70.0],
            [12, 1449, 71.0],
            [12, 1900, 90.0],
            [12, 1957, 93.0],
            [12, 1990, 90.0],
            [12, 4100, 115.0],
            [12, 4500, 110.0],
            [12, 4943, 102.0],
            [12, 15347, 308.0],
            [24, 1001, 74.0],
            [24, 1333, 82.0],
            [24, 2750, 115.0],
            [24, 3001, 124.0],
            [24, 7653, 307.0],
            [24, 19999, 801.0],
        ];
    }

    /**
     * Free float amount calculator data provider
     *
     * @return array
     */
    private function _freeFloatAmountCalculatorDataProvider(): array
    {
        return [
            [12, 1001.47, 53.53],
            [12, 1002.33, 52.67],
            [12, 1003.21, 51.79],
            [12, 1004.01, 50.99],
            [12, 1005.99, 54.01],
            [12, 1006.13, 53.87],
            [12, 1007.22, 52.78],
            [12, 1008.10, 51.9],
            [12, 1009.45, 50.55],
            [12, 1010.58, 54.42],
            [12, 1400.1, 69.9],
            [12, 1449.2, 70.8],
            [12, 1900.3, 89.7],
            [12, 1957.4, 92.6],
            [12, 1990.5, 94.5],
            [12, 4100.6, 114.4],
            [12, 4500.7, 109.3],
            [12, 4943.8, 101.2],
            [12, 15347.9, 307.1],
            [24, 1001.10, 73.9],
            [24, 1333.11, 81.89],
            [24, 2750.12, 119.88],
            [24, 3001.13, 123.87],
            [24, 7653.14, 306.86],
            [24, 19999.99, 800.01],
        ];
    }

    /**
     * Free term calculator data provider
     *
     * @return array
     */
    private function _freeTermCalculatorDataProvider(): array
    {
        return [
            [13, 1000, 55.0],
            [14, 2000, 95.0],
            [15, 3000, 100.0],
            [16, 4000, 130.0],
            [17, 5000, 145.0],
            [18, 6000, 180.0],
            [19, 7000, 225.0],
            [20, 8000, 270.0],
            [21, 9000, 315.0],
            [22, 10000, 370.0],
            [23, 11000, 425.0],
            [13, 12000, 260.0],
            [14, 13000, 305.0],
            [15, 14000, 350.0],
            [16, 15000, 400.0],
            [17, 16000, 455.0],
            [18, 17000, 510.0],
            [19, 18000, 570.0],
            [20, 19000, 635.0],
            [21, 20000, 700.0],
        ];
    }

    /**
     * Free term and amount calculator data provider
     *
     * @return array
     */
    private function _freeTermAndAmountCalculatorDataProvider(): array
    {
        return [
            [13, 1001, 54.0],
            [14, 2020, 95.0],
            [15, 3300.33, 109.67],
            [16, 4500.79, 134.21],
            [17, 5500, 160.0],
            [18, 6006.71, 183.29],
            [19, 7000.99, 254.01],
            [20, 8540, 285.0],
            [21, 9059.4, 320.6],
            [22, 10560, 390.0],
            [23, 11024.78, 425.22],
            [13, 12400, 270.0],
            [14, 13010, 305.0],
            [15, 14700.70, 369.3],
            [16, 15090, 405.0],
            [17, 16100, 460.0],
            [18, 17020.35, 514.65],
            [19, 18000.01, 604.99],
            [20, 19000.99, 639.01],
        ];
    }

    /**
     * Incorrect data calculator data provider
     *
     * @return array
     */
    private function _incorrectDataCalculatorDataProvider(): array
    {
        return [
            [11, 1000, 0.0, ValidationException::class],
            [25, 1000, 0.0, ValidationException::class],
            [12, 999, 0.0, ValidationException::class],
            [12, 20001, 0.0, ValidationException::class],
            ['12', 1000, 0.0, 'TypeError'],
            [12.0, 1000, 0.0, 'TypeError'],
            [12, '1000', 0.0, 'TypeError'],
            [0, 1000, 0.0, ValidationException::class],
            [12, 0, 0.0, ValidationException::class],
        ];
    }
}
