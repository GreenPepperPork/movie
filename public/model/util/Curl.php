<?php
namespace common\model\util;

class Curl
{
    private static function execute($method, $url, $fields = '', $agent = '', $headers = '')
    {
        $ch = static::create();

        if (!is_resource($ch) || empty($url)) {
            return false;
        }

        // 是否显示头部信息
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 设置curl超时秒数
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $method = strtolower($method);
        switch (strtolower($method)) {
            case 'post' :
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_URL, $url);
                break;
            case 'get' :
                if (!empty($fields) && is_array($fields)) {
                    $url .= '?' . http_build_query($fields);
                }
                curl_setopt($ch, CURLOPT_URL, $url);
                break;
        }
        $agent and curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        $headers and curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close($ch);

        if (curl_errno($ch)) {
            return [curl_error($ch), curl_errno($ch)];
        }

        return is_string($result) ? $result : false;
    }

    private static function create()
    {
        if (!function_exists('curl_init')) {
            throw new \Exception('`CURL` Extenstion Not Install!');
        }

        return curl_init();
    }

    public static function post($url, $fields = [], $timeout = 100, $headers = '')
    {
        $result = static::execute('post', $url, $fields, $timeout, $headers);
        return $result;
    }

    public static function get($url, $fields = [], $timeout = 100, $headers = '')
    {
        $result = static::execute('get', $url, $fields, $timeout, $headers);
        return $result;
    }
}