<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Tests;

use Lendable\Interview\Interpolation\{
    Model\LoanModel,
    Component\Exception\ValidationException
};
use PHPUnit\Framework\TestCase;

/**
 * LoanModel test
 */
class LoanModelTest extends TestCase
{

    /**
     * Test validation
     *
     * @param int    $term              Term
     * @param float  $amount            Amount
     * @param string $expectedException Expected exception
     *
     * @return void
     *
     * @dataProvider validationDataProvider
     */
    public function testValidation(
        $term,
        $amount,
        string $expectedException = null
    ): void {
        if ($expectedException !== null) {
            $this->expectException($expectedException);
        }

        $loanModel = new LoanModel($term, $amount);

        $this->assertSame($term, $loanModel->getTerm());
        $this->assertEquals($amount, $loanModel->getAmount());
    }

    /**
     * Validation data provider
     *
     * @return array
     */
    public function validationDataProvider(): array
    {
        return array_merge(
            $this->_correctDataProvider(),
            $this->_incorrectDataProvider()
        );
    }

    /**
     * Correct data provider
     *
     * @return array
     */
    private function _correctDataProvider(): array
    {
        return [
            [12, 1000],
            [13, 2001.9],
            [14, 3000],
            [15, 4000],
            [16, 5000],
            [17, 6000.0],
            [18, 7000],
            [19, 8000.0],
            [20, 9000],
            [21, 10000.30],
            [22, 13100.0],
            [23, 19500.0],
            [24, 20000.0],
        ];
    }

    /**
     * Incorrect data provider
     *
     * @return array
     */
    private function _incorrectDataProvider(): array
    {
        return [
            [11, 1000, ValidationException::class],
            [25, 1000, ValidationException::class],
            [12, 999, ValidationException::class],
            [12, 20001, ValidationException::class],
            ['12', 1000, 'TypeError'],
            [12.0, 1000, 'TypeError'],
            [12, '1000', 'TypeError'],
            [0, 1000, ValidationException::class],
            [12, 0, ValidationException::class],
        ];
    }
}
