<?php

namespace SOW\BindingBundle\Exception;

class BinderTypeException extends \Exception
{
    /**
     * BinderConfigurationException constructor.
     */
    public function __construct(string $typeExpected, string $typeReceived, $code = 0, \Throwable $previous = null)
    {
        $message = sprintf("Wrong value type. Expected : %s, received : %s", $typeExpected, $typeReceived);
        parent::__construct($message, $code, $previous);
    }
}