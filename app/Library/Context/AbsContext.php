<?php

namespace App\Library\Context;

use App\Http\Traits\SingletonPrivate;
use App\Library\Utils;

/**
 * 上下文抽象类
 */
class AbsContext
{
    use SingletonPrivate;

    /**
     * 上下文数据
     */
    protected static $contents = [];

    /**
     * 默认作用域
     */
    private static $defaultScope = '_default_';

    /**
     * 获取作用域
     * @param string $scope 指定作用域
     *
     * @return string
     * @author Johnson
     */
    protected static function getScope(string $scope = ''): string
    {
        return $scope !== '' ? $scope : self::$defaultScope;
    }

    /**
     * 获取指定作用域数据
     * @param string $scope 作用域
     *
     * @return mixed
     * @author Johnson
     */
    protected static function getScopeData(string $scope)
    {
        $scope = self::getScope($scope);

        return self::$contents[$scope] ?? null;
    }

    /**
     * 设置上下文值
     * @param string $key 键名
     * @param mixed $value 键值（支持多级stu.name）
     * @param string $scope 作用域
     */
    protected static function setVal(string $key, $value, string $scope)
    {
        if (!isset(self::$contents[$scope])) {
            self::$contents[$scope] = [];
        }

        $contents = &self::$contents[$scope];
        Utils::setVal($contents, $key, $value);
    }


}
