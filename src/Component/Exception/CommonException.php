<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Component\Exception;

/**
 * Common exception
 */
class CommonException extends AbstractException
{

    /**
     * Gets error code
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        return 500;
    }
}
