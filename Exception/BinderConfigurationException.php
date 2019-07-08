<?php

/**
 * BinderConfigurationException
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle\Exception
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Exception;

/**
 * Class BinderConfigurationException
 *
 * @package SOW\BindingBundle\Exception
 */
class BinderConfigurationException extends BinderException
{
    public const MESSAGE = "The Binder is not configured";
    public const CODE = 2914401;

    /**
     * BinderConfigurationException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = self::MESSAGE, $code = self::CODE, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
