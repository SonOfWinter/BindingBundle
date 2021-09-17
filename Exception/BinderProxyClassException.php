<?php
/**
 * @package  SOW\BindingBundle\Exception
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace SOW\BindingBundle\Exception;

use Throwable;

/**
 * Class BinderProxyClassException
 *
 * @package SOW\BindingBundle\Exception
 */
class BinderProxyClassException extends BinderException
{
    public const MESSAGE = "Don't use Doctrine Proxy class with Binder";
    public const CODE = 2914403;

    /**
     * BinderProxyClassException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = self::MESSAGE,
        $code = self::CODE,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
