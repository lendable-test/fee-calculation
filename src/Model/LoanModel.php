<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Model;

/**
 * A cut down version of a loan application containing
 * only the required properties for this test.
 */
class LoanModel
{
    /**
     * @var int
     */
    private $term;

    /**
     * @var float
     */
    private $amount;

    public function __construct(int $term, float $amount)
    {
        $this->term = $term;
        $this->amount = $amount;
    }

    /**
     * Term (loan duration) for this loan application
     * in number of months.
     */
    public function term(): int
    {
        return $this->term;
    }

    /**
     * Amount requested for this loan application.
     */
    public function amount(): float
    {
        return $this->amount;
    }
}