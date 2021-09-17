<?php
/**
 * BinderMaxValueException
 *
 * @package  SOW\BindingBundle\Exception
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Exception;

use Throwable;

/**
 * Class BinderMaxValueException
 *
 * @package  SOW\BindingBundle\Exception
 */
class BinderMaxValueException extends BinderException
{
    public const MESSAGE = "%s must have a value less than : %s";
    public const CODE = 2914406;

    private string $key;

    private int $max;

    /**
     * BinderMaxValueException constructor.
     *
     * @param string $key
     * @param int $max
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $key = '',
        int $max = 0,
        string $message = "",
        int $code = self::CODE,
        Throwable $previous = null
    ) {
        if ($message === "") {
            $message = sprintf(self::MESSAGE, $key, $max);
        }
        $this->key = $key;
        $this->max = $max;
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
     * Getter for max
     *
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }
}
