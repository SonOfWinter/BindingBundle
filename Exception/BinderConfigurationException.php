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
class BinderConfigurationException extends \Exception
{
    /**
     * BinderConfigurationException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        $message = "",
        $code = 0,
        \Throwable $previous = null
    ) {
        if ($message == "") {
            $message = "The Binder is not configured";
        }
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
