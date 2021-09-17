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
class BinderIncludeException extends BinderException
{
    public const MESSAGE = "Missing mandatory keys : %s";
    public const CODE = 2914404;

    private array $missingKeys;

    /**
     * BinderProxyClassException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param array $missingKeys
     */
    public function __construct(
        array $missingKeys = [],
        string $message = "",
        int $code = self::CODE,
        Throwable $previous = null
    ) {
        if ($message === "") {
            $message = sprintf(self::MESSAGE, implode(", ", $missingKeys));
        }
        $this->missingKeys = $missingKeys;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Getter for missingKeys
     *
     * @return array
     */
    public function getMissingKeys(): array
    {
        return $this->missingKeys;
    }
}
