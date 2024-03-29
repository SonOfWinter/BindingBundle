<?php
/**
 * BinderMinValueException
 *
 * @package  SOW\BindingBundle\Exception
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Exception;

use Throwable;

/**
 * Class BinderMinValueException
 *
 * @package  SOW\BindingBundle\Exception
 */
class BinderMinValueException extends BinderException
{
    public const MESSAGE = "%s must have a value more than : %s";
    public const CODE = 2914405;

    private string $key;

    private int $min;

    /**
     * BinderMinValueException constructor.
     *
     * @param string $key
     * @param string $message
     * @param int $min
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $key = '',
        int $min = 0,
        string $message = "",
        int $code = self::CODE,
        Throwable $previous = null
    ) {
        if ($message === "") {
            $message = sprintf(self::MESSAGE, $key, $min);
        }
        $this->key = $key;
        $this->min = $min;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Getter for key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Getter for min
     *
     * @return int
     */
    public function getMin()
    {
        return $this->min;
    }
}
