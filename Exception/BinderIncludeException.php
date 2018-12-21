<?php

/**
 * BinderTypeException
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle\Exception
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Exception;

/**
 * Class BinderTypeException
 *
 * @package  SOW\BindingBundle\Exception
 */
class BinderIncludeException extends \Exception
{
    public const MESSAGE = "Missing mandatory keys";
    public const CODE = 2914404;

    /**
     * BinderProxyClassException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = self::MESSAGE,
        $code = self::CODE,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
