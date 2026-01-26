<?php

namespace App\Library;

class Utils
{
    /**
     * K-V数据设置
     * @param array $data
     * @param string $key
     * @param mixed $value
     */
    public static function setVal(array &$data, string $key, $value)
    {
        $keys = explode('.', $key);
        $count = count($keys) - 1;
        foreach ($keys as $idx => $subKey) {
            // 赋值操作
            if ($subKey == '[]') {
                $data[] = $value;
                break;
            } elseif ($idx === $count) {
                $data[$subKey] = $value;
                break;
            }
            // 检查键名是否存在
            if (!key_exists($subKey, $data)) {
                $data[$subKey] = [];
            }
            // 移动游标
            $data = &$data[$subKey];
        }
    }
}
