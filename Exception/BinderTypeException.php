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
class BinderTypeException extends \Exception
{
    /**
     * BinderTypeException constructor.
     *
     * @param string          $typeExpected
     * @param string          $typeReceived
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $typeExpected,
        string $typeReceived,
        $code = 0,
        \Throwable $previous = null
    ) {
        $message = sprintf(
            "Wrong value type. Expected : %s, received : %s",
            $typeExpected,
            $typeReceived
        );
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
