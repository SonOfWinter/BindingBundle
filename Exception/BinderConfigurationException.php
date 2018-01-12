<?php

namespace SOW\BindingBundle\Exception;

class BinderConfigurationException extends \Exception
{
    /**
     * BinderConfigurationException constructor.
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        if ($message == "") {
            $message = "The Binder is not configured";
            }
        parent::__construct($message, $code, $previous);
    }
}