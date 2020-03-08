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
 * PaymentCalculatorService test
 */
class PaymentCalculatorServiceTest extends TestCase
{

    /**
     * Calculate function test
     *
     * @param int    $term              Term
     * @param float  $amount            Amount
     * @param float  $expectedPayment   Expected payment
     * @param string $expectedException Expected exception
     *
     * @return void
     *
     * @dataProvider calculatorDataProvider
     */
    public function testCalculate(
        $term,
        $amount,
        float $expectedPayment,
        string $expectedException = null
    ): void {
        if ($expectedException !== null) {
            $this->expectException($expectedException);
        }

        $loanModel = new LoanModel($term, $amount);
        $actualPayment = AbstractService::factory('paymentCalculator')
            ->calculate($loanModel);

        $this->assertSame($expectedPayment, $actualPayment);
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
            [12, 1000, 87.5],
            [12, 2000, 174.17],
            [12, 3000, 257.5],
            [12, 4000, 342.92],
            [12, 5000, 425.0],
            [12, 6000, 510.0],
            [12, 7000, 595.0],
            [12, 8000, 680.0],
            [12, 9000, 765.0],
            [12, 10000, 850.0],
            [12, 11000, 935.0],
            [12, 12000, 1020.0],
            [12, 13000, 1105.0],
            [12, 14000, 1190.0],
            [12, 15000, 1275.0],
            [12, 16000, 1360.0],
            [12, 17000, 1445.0],
            [12, 18000, 1530.0],
            [12, 19000, 1615.0],
            [12, 20000, 1700.0],
            [24, 1000, 44.58],
            [24, 2000, 87.5],
            [24, 2750, 119.38],
            [24, 3000, 130.0],
            [24, 4000, 173.33],
            [24, 5000, 216.67],
            [24, 6000, 260.0],
            [24, 7000, 303.33],
            [24, 8000, 346.67],
            [24, 9000, 390.0],
            [24, 10000, 433.33],
            [24, 11000, 476.67],
            [24, 12000, 520.0],
            [24, 13000, 563.33],
            [24, 14000, 606.67],
            [24, 15000, 650.0],
            [24, 16000, 693.33],
            [24, 17000, 736.67],
            [24, 18000, 780.0],
            [24, 19000, 823.33],
            [24, 20000, 866.67],
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
