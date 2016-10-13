<?php
namespace zaor;

class Router
{
    /**
     * 解析模块的URL地址 [模块/控制器/操作?]参数1=值1&参数2=值2
     * @param string $url URL地址
     * @return string
     */
    public static function parseUrl($url)
    {
        // TODO URL部分处理
        $result = self::parseRoute($url);

        return $result;
    }

    /**
     * 解析规范的路由地址 地址格式 [模块/控制器/操作?]参数1=值1&参数2=值2...
     * @access private
     * @param string $url URL地址
     * @return array
     */
    private static function parseRoute($url)
    {
        list($path, $params) = array_values(parse_url($url));
        $info = explode('/', trim($path, '/'));

        // [模块|控制器|操作]
        $result['module']     = array_shift($info);
        $result['controller'] = implode('/', array_splice($info, 0, (count($info) - 1) ? (count($info) - 1) : 1));
        $result['action']     = array_shift($info);

        return ['type' => 'module', 'result' => $result, 'params' => $params];
    }
}
