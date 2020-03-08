<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Component\Exception;

/**
 * Bad request exception
 */
class BadRequestException extends AbstractException
{

    /**
     * Gets error code
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        return 400;
    }
}
