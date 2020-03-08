<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Service;

use Lendable\Interview\Interpolation\Model\LoanModel;

/**
 * Service to calculate fee
 */
class FeeCalculatorService extends AbstractService
{

    /**
     * Calculates fee based on loan model
     *
     * @param LoanModel $model Model
     *
     * @return float
     */
    public function calculate(LoanModel $model): float
    {
        return round(
            $this->calculateFee($model),
            self::DECIMAL_PLACES
        );
    }
}
