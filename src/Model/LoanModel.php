<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Model;

use Lendable\Interview\Interpolation\Component\{
    Common\Config,
    Exception\ValidationException
};

/**
 * A cut down version of a loan application containing
 * only the required properties for this test.
 */
class LoanModel
{

    /**
     * Terms config path
     */
    const CONFIG_PATH_TERMS = ['terms'];

    /**
     * Errors
     */
    const ERROR_TERM_MIN_MASK = 'Term cannot be less than {min} months';
    const ERROR_TERM_MAX_MASK = 'Term cannot be more than {max} months';
    const ERROR_TERM_IS_NOT_SET = 'Term is not set';
    const ERROR_AMOUNT_MIN_MASK = 'Amount cannot be less than {min} pounds';
    const ERROR_AMOUNT_MAX_MASK = 'Amount cannot be more than {max} pounds';

    /**
     * Term
     *
     * @var integer
     */
    private $_term = 0;

    /**
     * Amount
     *
     * @var float
     */
    private $_amount = 0.0;

    /**
     * Term amounts
     *
     * @var array
     */
    private $_termAmounts = [];

    /**
     * LoanModel constructor
     *
     * @param int   $term   Term
     * @param float $amount Amount
     */
    public function __construct(int $term, float $amount)
    {
        $this
            ->setTerm($term)
            ->setAmount($amount);
    }

    /**
     * Sets term
     *
     * @param int $term Term
     *
     * @return LoanModel
     */
    public function setTerm(int $term): LoanModel
    {
        $this->_validateTerm($term);
        $this->_term = $term;
        $this->_setTermConfig();

        return $this;
    }

    /**
     * Gets term (loan duration) for this loan application
     * in number of months.
     *
     * @return integer
     */
    public function getTerm(): int
    {
        return $this->_term;
    }

    /**
     * Sets amount
     *
     * @param float $amount Amount
     *
     * @return LoanModel
     */
    public function setAmount(float $amount): LoanModel
    {
        $this->_validateAmount($amount);
        $this->_amount = $amount;

        return $this;
    }

    /**
     * Gets amount requested for this loan application.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->_amount;
    }

    /**
     * Gets term amounts
     *
     * @return array
     */
    public function getTermAmounts(): array
    {
        return $this->_termAmounts;
    }

    /**
     * Validates term
     *
     * @param int $term Term
     *
     * @return LoanModel
     *
     * @throws ValidationException
     */
    private function _validateTerm(int $term): LoanModel
    {
        $terms = array_keys(
            Config::getInstance()->getValue(
                self::CONFIG_PATH_TERMS,
                true
            )
        );

        $minValue = min($terms);
        if ($term < $minValue) {
            throw new ValidationException(
                self::ERROR_TERM_MIN_MASK,
                [
                    'min' => $minValue,
                ]
            );
        }

        $maxValue = max($terms);
        if ($term > $maxValue) {
            throw new ValidationException(
                self::ERROR_TERM_MAX_MASK,
                [
                    'max' => $maxValue,
                ]
            );
        }

        return $this;
    }

    /**
     * Sets term config
     *
     * @return LoanModel
     *
     * @throws ValidationException
     */
    private function _setTermConfig(): LoanModel
    {
        if ($this->_term === 0) {
            throw new ValidationException(self::ERROR_TERM_IS_NOT_SET);
        }

        $termsConfig = Config::getInstance()->getValue(
            self::CONFIG_PATH_TERMS,
            true
        );

        if (isset($termsConfig[$this->_term]) === true) {
            $this->_termAmounts = array_keys($termsConfig[$this->_term]);
            return $this;
        }

        $terms = array_keys($termsConfig);
        $closest = null;
        foreach ($terms as $term) {
            if ($closest === null
                || abs($this->_term - $closest) > abs($term - $this->_term)
            ) {
                $closest = $term;
            }
        }

        $this->_termAmounts = array_keys($termsConfig[$closest]);
        return $this;
    }

    /**
     * Validates amount
     *
     * @param float $amount Amount
     *
     * @return LoanModel
     *
     * @throws ValidationException
     */
    private function _validateAmount(float $amount): LoanModel
    {
        $minValue = min($this->_termAmounts);
        if ($amount < $minValue) {
            throw new ValidationException(
                self::ERROR_AMOUNT_MIN_MASK,
                [
                    'min' => $minValue,
                ]
            );
        }

        $maxValue = max($this->_termAmounts);
        if ($amount > $maxValue) {
            throw new ValidationException(
                self::ERROR_AMOUNT_MAX_MASK,
                [
                    'max' => $maxValue,
                ]
            );
        }

        return $this;
    }
}
