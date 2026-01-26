<?php

use App\Exceptions\ApiException;
use App\Library\Constant\GlobalContext;
use App\Library\Context\Context;

if (!function_exists('request_id')) {
    /**
     * 获取请求Id
     */
    function request_id(): string
    {
        return Context::get(GlobalContext::REQUEST_ID) ?? '';
    }
}

if (!function_exists('convert_to_cdn_url')) {
    /**
     * 获取cdn url
     */
    function convert_to_cdn_url($url): string
    {
        if (blank($url)) {
            return '';
        }
        //生产环境
        if (app()->environment(['production'])) {
            $urlInfo = parse_url($url);
            $host = $urlInfo['host'] ?? '';
            $path = $urlInfo['path'] ?? '';
            if ($host == config('cdn.replace_host')) {
                return 'https://' . config('cdn.host') . $path;
            }
        }
        return $url;
    }
}

if (!function_exists('rich_text_url_convert_to_cdn_url')) {
    /**
     * 富文本中的 url 替换为 cdn url 地址
     */
    function rich_text_url_convert_to_cdn_url($content)
    {
        //生产环境
        if (app()->environment(['production'])) {
            // 要查找的子串
            $find = config('cdn.replace_host');
            // 替换为的新子串
            $replace = config('cdn.host');
            $content = str_replace($find, $replace, $content);
        }
        return $content;
    }
}


if (!function_exists('get_file_key')) {
    /**
     * 获取文件key
     * @param $url
     * @return string
     */
    function get_file_key($url)
    {
        return ltrim(parse_url($url, PHP_URL_PATH), '/'); // 移除路径中的斜杠
    }
}

if (!function_exists('get_url_file_size')) {
    /**
     * @param $url
     * @return int|string
     */
    function get_url_file_size($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true); // 不返回 body 部分
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // 跟随重定向
        $data = curl_exec($ch);
        curl_close($ch);

        if ($data === false) {
            return '';
        }
        $contentLength = -1;

        if (preg_match('/^content-length: (\d+)\r$/m', strtolower($data), $matches)) {
            $contentLength = format_bytes(intval($matches[1]));
        }
        if ($contentLength == '0 B' || $contentLength == '-1') {
            try {
                $contentLength = format_bytes(strlen(file_get_contents($url)));
            } catch (Exception $e) {
            }

        }
        return $contentLength;
    }
}

if (!function_exists('format_bytes')) {
    /**
     * @param $bytes
     * @param int $precision
     * @return string
     */
    function format_bytes($bytes, int $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('format_timezone_date')) {
    /**
     * @param int $unix_time
     * @param string $timezone
     * @return string
     */
    function format_timezone_date(int $unix_time, string $timezone)
    {
        try {
            // 创建一个 DateTimeZone 对象
            $tz = new \DateTimeZone($timezone);
        } catch (\Exception $e) {
            $tz = new \DateTimeZone('UTC');
        }
        // 创建一个 DateTime 对象，并设置时区
        $dt = new \DateTime();
        $dt->setTimezone($tz);
        $dt->setTimestamp($unix_time);
        return $dt->format('Y-m-d H:i:s');
    }
}

if (!function_exists('get_utc_date')) {
    function get_utc_date($format = 'Y-m-d H:i:s'): string
    {
        $timezone = new \DateTimeZone('UTC');
        $dateTime = new DateTime('now', $timezone);
        return $dateTime->format($format);
    }
}


if (!function_exists('removeTags')) {
    /**
     * @throws Exception
     */
    function removeTags($html)
    {
        //如果是空字符串直接返回
        if (blank($html)) {
            return $html;
        }
        // 启用内部错误处理，防止警告
        libxml_use_internal_errors(true);
        // 放入div标签中，进行 HTML 实体转换
        $html = mb_convert_encoding('<div id="ac-maker">' . $html . '</div>', 'HTML-ENTITIES', 'UTF-8');
        // 创建一个 DOMDocument 对象
        $dom = new DOMDocument();
        // 将传入的 HTML 字符串加载到 DOMDocument 对象中
        $dom->loadHTML($html);
        // 获取解析中的错误
        $errors = libxml_get_errors();
        libxml_clear_errors();

        if (!empty($errors)) {
            throw new ApiException('Invalid Content');
        }
        // 返回去除了所有 HTML 标签后的文本内容
        return $dom->getElementById('ac-maker')->textContent;
    }
}
