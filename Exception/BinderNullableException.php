<?php

/**
 * PHP Version 7.1, 7.2
 *
 * @package  SOW\BindingBundle\Exception
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace SOW\BindingBundle\Exception;

/**
 * Class BinderNullableException
 *
 * @package SOW\BindingBundle\Exception
 */
class BinderNullableException extends BinderException
{
    public const MESSAGE = "Key %s cannot be null";
    public const CODE = 2914408;

    /**
     * @var string
     */
    private $key;

    /**
     * BinderProxyClassException constructor.
     *
     * @param string $key
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $key = '',
        string $message = "",
        $code = self::CODE,
        \Throwable $previous = null
    ) {
        if ($message === "") {
            $message = sprintf(self::MESSAGE, $key);
        }
        $this->key = $key;
        parent::__construct($message, $code, $previous);
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
