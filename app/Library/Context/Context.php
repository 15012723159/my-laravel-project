<?php

namespace App\Library\Context;

/**
 * 上下文工具
 */
class Context extends AbsContext implements ContextInterface
{
    /**
     * @inheritDoc
     */
    public static function set(string $key, $value, string $scope = '')
    {
        $scope = self::getScope($scope);

        self::setVal($key, $value, $scope);
    }

    /**
     * @inheritDoc
     */
    public static function multiSet(array $pairs, string $scope = '')
    {
        $scope = self::getScope($scope);
        foreach ($pairs as $key => $value) {
            self::setVal($key, $value, $scope);
        }
    }

    /**
     * @inheritDoc
     */
    public static function get(string $key, $default = null, string $scope = '')
    {
        $keys = explode('.', $key);
        $contents = self::getScopeData($scope);
        foreach ($keys as $key) {
            if (!isset($contents[$key])) {
                return $default;
            }
            $contents = $contents[$key];
        }
        return ($contents !== null && $contents !== '') || is_null($default) ? $contents : $default;
    }

    /**
     * @inheritDoc
     */
    public static function has(string $key, string $scope = ''): bool
    {
        $keys = explode('.', $key);
        $contents = self::getScopeData($scope);
        foreach ($keys as $key) {
            if (!isset($contents[$key])) {
                return false;
            }
            $contents = $contents[$key];
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function all(string $scope = ''): array
    {
        return self::getScopeData($scope) ?? [];
    }

    /**
     * @inheritDoc
     */
    public static function delete(string $key, string $scope = '')
    {
        if (self::has($key, $scope)) {
            $scope = $scope ?: self::getScope($scope);
            $content = &self::$contents[$scope];
            $keys = explode('.', $key);
            $count = count($keys);
            foreach ($keys as $idx => $key) {
                if ($idx !== $count - 1) {
                    $content = &$content[$key];
                    continue;
                }
                unset($content[$key]);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function clear(string $scope = '')
    {
        $scope = self::getScope($scope);
        if (isset(self::$contents[$scope])) {
            self::$contents[$scope] = [];
        }
    }
}
