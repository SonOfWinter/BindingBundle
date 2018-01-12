<?php

namespace SOW\BindingBundle;

/**
 * Interface BinderInterface
 * @package SOW\BindingBundle
 */
interface BinderInterface
{
    public function getBindingCollection();

    public function bind(&$object, array $params = []);
}