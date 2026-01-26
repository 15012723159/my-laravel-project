<?php

namespace App\Http\Traits;

/**
 * 单例模式（三私）
 */
trait SingletonPrivate
{
    protected static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}
