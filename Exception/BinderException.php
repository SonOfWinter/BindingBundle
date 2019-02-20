<?php

/**
 * BinderException
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle\Exception
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Exception;

/**
 * Class BinderException
 *
 * @package SOW\BindingBundle\Exception
 */
class BinderException extends \Exception
{
    /**
     * BinderException constructor.
     *
     * @param $message
     * @param $code
     * @param \Throwable|null $previous
     */
    public function __construct($message, $code, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
