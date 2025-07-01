<?php

namespace SOW\BindingBundle\Utils;

class TypeUtils
{
    public const SCALAR_TYPES = ['integer', 'float', 'string', 'boolean', 'array'];

    /**
     * isNotScalar
     *
     * @param string|null $type
     *
     * @return bool
     */
    public static function isNotScalar(?string $type = null): bool
    {
        return (!empty($type) && !in_array($type, self::SCALAR_TYPES));
    }

    /**
     * isNotScalar
     *
     * @param string|null $type
     *
     * @return bool
     */
    public static function isScalar(?string $type = null): bool
    {
        return !self::isNotScalar($type);
    }
}
