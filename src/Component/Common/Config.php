<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Component\Common;

use Lendable\Interview\Interpolation\Component\Exception\CommonException;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

/**
 * Class to work with config
 */
class Config
{

    /**
     * Config folder path
     */
    const CONFIG_PATH = __DIR__ . '/../../../config/application';

    /**
     * Errors
     */
    const ERROR_NOT_FOUND_MASK = 'Config not found with path: {path}';

    /**
     * Config object
     *
     * @var array
     */
    private $_config = [];

    /**
     * Singleton instance
     *
     * @var Config
     */
    private static $_instance = null;

    /**
     * Gets singleton instance
     *
     * @return Config
     */
    public static function getInstance(): Config
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Sets config
     *
     * Parses recursively files from the config/application folder
     *
     * @return void
     */
    private function _setConfig(): void
    {
        $directoryIterator = new RecursiveDirectoryIterator(
            self::CONFIG_PATH
        );

        $files = new RecursiveIteratorIterator($directoryIterator);

        foreach ($files as $file) {
            if ($file->isDir() === true) {
                continue;
            }

            $content = json_decode(
                file_get_contents(
                    $file->getPathname()
                ),
                true
            );

            if (json_last_error() === JSON_ERROR_NONE) {
                $this->_config = array_merge(
                    $this->_config,
                    $content
                );
            }
        }
    }

    /**
     * Gets config value
     *
     * @param array $path         Config path
     * @param bool  $checkNotNull Flag to check if result is not null
     *
     * @return mixed|null|float
     *
     * @throws CommonException
     */
    public function getValue(array $path = [], bool $checkNotNull = null)
    {
        if (count($this->_config) === 0) {
            $this->_setConfig();
        }

        $value = $this->_config;

        if (count($path) === 0) {
            return $value;
        }

        foreach ($path as $item) {
            if (array_key_exists($item, $value) === false) {
                if ($checkNotNull === true) {
                    throw new CommonException(
                        self::ERROR_NOT_FOUND_MASK,
                        [
                            'path' => json_encode($path),
                        ]
                    );
                }

                return null;
            }

            $value = $value[$item];
        }

        return $value;
    }
}
