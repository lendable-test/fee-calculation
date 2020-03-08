<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Service;

use Lendable\Interview\Interpolation\{
    Component\Common\Config,
    Model\LoanModel
};

/**
 * Service to calculate fee
 */
class FeeCalculatorService implements CalculatorInterface
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
        $feeFromConfig = $this->_getFeeFromConfig(
            $model->getTerm(),
            $model->getAmount()
        );

        if ($feeFromConfig !== null) {
            return (float)$feeFromConfig;
        }

        return 0.0;
    }

    /**
     * Gets fee from config
     *
     * @param int   $term         Term
     * @param float $amount       Amount
     * @param bool  $checkNotNull Flag to check if result is not null
     *
     * @return float|null
     */
    private function _getFeeFromConfig(
        int $term,
        float $amount,
        bool $checkNotNull = null
    ): ?float {
        return Config::getInstance()->getValue(
            array_merge(
                LoanModel::CONFIG_PATH_TERMS,
                [
                    $term,
                    (string)$amount,
                ]
            ),
            $checkNotNull
        );
    }
}
