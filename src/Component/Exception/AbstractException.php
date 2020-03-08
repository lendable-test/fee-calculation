<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Component\Exception;

use Exception;

/**
 * Abstract exception
 */
abstract class AbstractException extends Exception
{

    /**
     * Gets error code
     *
     * @return int
     */
    abstract public function getErrorCode(): int;

    /**
     * AbstractException constructor.
     *
     * @param string $message    Message
     * @param array  $parameters Parameters
     */
    public function __construct(string $message, array $parameters = [])
    {
        foreach ($parameters as $key => $value) {
            if (is_array($value) === true) {
                $value = json_encode($value);
            }

            $message = str_replace(
                '{' . $key . '}',
                '[' . (string)$value . ']',
                $message
            );
        }

        parent::__construct($message, $this->getErrorCode());
    }
}
