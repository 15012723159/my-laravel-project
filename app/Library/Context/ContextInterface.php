<?php

namespace App\Library\Context;

/**
 * 上下文Interface
 */
interface ContextInterface
{
    /**
     * 设置单个数据
     * @param string $key   键名
     * @param mixed  $value 数据
     * @param string $scope 作用域
     */
    public static function set(string $key, $value, string $scope = '');

    /**
     * 设置多个数据
     * @param array  $pairs 键值对<string, mixed>
     * @param string $scope 作用域
     */
    public static function multiSet(array $pairs, string $scope = '');

    /**
     * 获取指定KEY数据
     * @param string     $key     键名
     * @param mixed|null $default 默认数据
     * @param string     $scope   作用域
     *
     * @return mixed
     * @author Johnson
     */
    public static function get(string $key, $default = null, string $scope = '');

    /**
     * 检查指定KEY是否存在
     * @param string $key 键名
     * @param string $scope 作用域
     *
     * @return bool
     * @author Johnson
     */
    public static function has(string $key, string $scope = ''): bool;

    /**
     * 获取所有上下文数据
     * @param string $scope 作用域
     *
     * @return array
     * @author Johnson
     */
    public static function all(string $scope = ''): array;

    /**
     * 删除指定上下文数据
     * @param string $key 键名
     * @param string $scope 作用域
     */
    public static function delete(string $key, string $scope = '');

    /**
     * 清除指定作用域数据
     * @param string $scope 作用域
     */
    public static function clear(string $scope = '');
}
