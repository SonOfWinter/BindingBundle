<?php
/**
 * BinderTypeException
 *
 * @package  SOW\BindingBundle\Exception
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Exception;

use Throwable;

/**
 * Class BinderTypeException
 *
 * @package  SOW\BindingBundle\Exception
 */
class BinderTypeException extends BinderException
{
    public const CODE = 2914402;
    public const MESSAGE = "Wrong %s parameter type. Expected : %s, received : %s";

    private string $typeExpected;

    private string $typeReceived;

    private string $property;

    /**
     * BinderTypeException constructor.
     *
     * @param string $typeExpected
     * @param string $typeReceived
     * @param string $property
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $typeExpected,
        string $typeReceived,
        string $property,
        int $code = self::CODE,
        Throwable $previous = null
    ) {
        $message = sprintf(
            self::MESSAGE,
            $property,
            $typeExpected,
            $typeReceived
        );
        $this->property = $property;
        $this->typeExpected = $typeExpected;
        $this->typeReceived = $typeReceived;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Getter for typeExpected
     *
     * @return string
     */
    public function getTypeExpected(): string
    {
        return $this->typeExpected;
    }

    /**
     * Getter for typeReceived
     *
     * @return string
     */
    public function getTypeReceived(): string
    {
        return $this->typeReceived;
    }

    /**
     * Getter for property
     *
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }
}
