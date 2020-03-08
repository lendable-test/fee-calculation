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
     * Multiple value
     *
     * The fee should then be
     * "rounded up" such that (fee + loan amount) is an exact multiple of 5.
     */
    const FEE_MULTIPLE_VALUE = 5;

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

        $amount = $model->getAmount();

        $amounts = $this->_getLowerAndHigherAmounts($model);
        $lowerAmount = $amounts[0];
        $higherAmount = $amounts[1];

        $fees = $this->_getLowerAndHigherFees(
            $model,
            $lowerAmount,
            $higherAmount
        );
        $lowerFee = $fees[0];
        $higherFee = $fees[1];

        $amountArea = ($higherAmount - $lowerAmount);
        $amountDiff = ($amount - $lowerAmount);
        $amountCoefficient = 1;
        if ((int)$amountArea !== 0
            && (int)$amountDiff !== 0
        ) {
            $amountCoefficient = ($amountDiff / $amountArea);
        }

        $feeArea = ($higherFee - $lowerFee);
        $feeDiff = ($amountCoefficient * $feeArea);
        $fee = ($lowerFee + $feeDiff);

        $reminder = fmod(($amount + $fee), self::FEE_MULTIPLE_VALUE);

        if ($reminder > 0) {
            $fee += self::FEE_MULTIPLE_VALUE - $reminder;
        }

        return $fee;
    }

    /**
     * Gets lower and higher amounts
     *
     * @param LoanModel $model Model
     *
     * @return array
     */
    private function _getLowerAndHigherAmounts(LoanModel $model): array
    {
        $amount = $model->getAmount();
        $termAmounts = $model->getTermAmounts();

        if (in_array($amount, $termAmounts) === true) {
            return [
                $amount,
                $amount,
            ];
        }

        $lastAmountKey = (count($termAmounts) - 1);
        $lowerAmount = $termAmounts[0];
        $higherAmount = $termAmounts[$lastAmountKey];

        for ($key = 0; $key < ($lastAmountKey - 1); $key++) {
            if ($amount > $termAmounts[$key]
                && $amount < $termAmounts[($key + 1)]
            ) {
                $lowerAmount = $termAmounts[$key];
                $higherAmount = $termAmounts[($key + 1)];
                break;
            }
        }

        return [
            $lowerAmount,
            $higherAmount,
        ];
    }

    /**
     * Gets lower and higher fees
     *
     * @param LoanModel $model        Model
     * @param float     $lowerAmount  Lower amount
     * @param float     $higherAmount Higher amount
     *
     * @return array
     */
    private function _getLowerAndHigherFees(
        LoanModel $model,
        float $lowerAmount,
        float $higherAmount
    ): array {
        $feesFromConfig = $this->_getLowerAndHigherFeesFromConfig(
            $model,
            $lowerAmount,
            $higherAmount
        );

        if ($feesFromConfig !== null) {
            return $feesFromConfig;
        }

        return $this->_getLowerAndHigherGeneratedFees(
            $model,
            $lowerAmount,
            $higherAmount
        );
    }

    /**
     * Gets lower and higher generated fees
     *
     * @param LoanModel $model        Model
     * @param float     $lowerAmount  Lower amount
     * @param float     $higherAmount Higher amount
     *
     * @return array
     */
    private function _getLowerAndHigherGeneratedFees(
        LoanModel $model,
        float $lowerAmount,
        float $higherAmount
    ): array {
        $terms = array_keys(
            Config::getInstance()->getValue(
                LoanModel::CONFIG_PATH_TERMS,
                true
            )
        );

        $term = $model->getTerm();

        $lastTermKey = (count($terms) - 1);
        $lowerTerm = $terms[0];
        $higherTerm = $terms[$lastTermKey];

        for ($key = 0; $key < ($lastTermKey - 1); $key++) {
            if ($term > $terms[$key]
                && $term < $terms[($key + 1)]
            ) {
                $lowerTerm = $terms[$key];
                $higherTerm = $terms[($key + 1)];
                break;
            }
        }

        $lowerTermLowerFee = $this->_getFeeFromConfig(
            $lowerTerm,
            $lowerAmount,
            true
        );
        $lowerTermHigherFee = $this->_getFeeFromConfig(
            $lowerTerm,
            $higherAmount,
            true
        );
        $higherTermLowerFee = $this->_getFeeFromConfig(
            $higherTerm,
            $lowerAmount,
            true
        );
        $higherTermHigherFee = $this->_getFeeFromConfig(
            $higherTerm,
            $higherAmount,
            true
        );

        $termDiff = ($higherTerm - $term);
        $termArea = ($higherTerm - $lowerTerm);
        $termCoefficient = 1;
        if ((int)$termDiff !== 0
            && (int)$termArea !== 0
        ) {
            $termCoefficient = ($termDiff / $termArea);
        }

        $lowerFeeArea = ($higherTermLowerFee - $lowerTermLowerFee);
        $lowerFeeDiff = ($termCoefficient * $lowerFeeArea);
        $lowerFee = ($higherTermLowerFee - $lowerFeeDiff);

        $higherFeeArea = ($higherTermHigherFee - $lowerTermHigherFee);
        $higherFeeDiff = ($termCoefficient * $higherFeeArea);
        $higherFee = ($higherTermHigherFee - $higherFeeDiff);

        return [
            $lowerFee,
            $higherFee,
        ];
    }

    /**
     * Gets lower and higher fees from config
     *
     * @param LoanModel $model        Model
     * @param float     $lowerAmount  Lower amount
     * @param float     $higherAmount Higher amount
     *
     * @return array
     */
    private function _getLowerAndHigherFeesFromConfig(
        LoanModel $model,
        float $lowerAmount,
        float $higherAmount
    ): ?array {
        $term = $model->getTerm();

        $lowerFee = $this->_getFeeFromConfig($term, $lowerAmount);
        $higherFee = $this->_getFeeFromConfig($term, $higherAmount);

        if ($lowerFee === null
            || $higherFee === null
        ) {
            return null;
        }

        return [
            $lowerFee,
            $higherFee,
        ];
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
