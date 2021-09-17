<?php
/**
 * BinderException
 *
 * @package  SOW\BindingBundle\Exception
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Exception;

use Exception;
use Throwable;

/**
 * Class BinderException
 *
 * @package SOW\BindingBundle\Exception
 */
class BinderException extends Exception
{
    /**
     * BinderException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message, int $code, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
