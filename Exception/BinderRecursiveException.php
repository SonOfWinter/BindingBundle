<?php

/**
 * PHP Version 7.1, 7.2
 *
 * @package  SOW\BindingBundle\Exception
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace SOW\BindingBundle\Exception;

/**
 * Class BinderRecursiveException
 *
 * @package SOW\BindingBundle\Exception
 */
class BinderRecursiveException extends BinderException
{
    public const MESSAGE = "recursive call limit reached";
    public const CODE = 2914407;

    /**
     * BinderProxyClassException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = self::MESSAGE,
        $code = self::CODE,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
