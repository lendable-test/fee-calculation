<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Service;

use Lendable\Interview\Interpolation\Model\LoanModel;

/**
 * Service to calculate payment
 */
class PaymentCalculatorService extends AbstractService
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
        $amount = $model->getAmount();
        $term = $model->getTerm();
        $fee = $this->calculateFee($model);

        return round(
            (($amount + $fee) / $term),
            self::DECIMAL_PLACES
        );
    }
}
