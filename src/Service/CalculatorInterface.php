<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Service;

use Lendable\Interview\Interpolation\Model\LoanModel;

/**
 * Calculator interface
 */
interface CalculatorInterface
{

    /**
     * Calculates a result based on loan model
     *
     * @param LoanModel $model Loan model
     *
     * @return float
     */
    public function calculate(LoanModel $model): float;
}
