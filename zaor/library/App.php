<?php
namespace zaor;

use zaor\exception\ViewException;

class App
{
    public static function run($request = null)
    {
        is_null($request) && $request = Request::instance();

        defined('DEBUG_TRACE') or define('DEBUG_TRACE', $request->input(DEBUG_TRACE_SWITCH));

        // TODO 从响应类里获取调度器
        $dispatch = self::route($request, []);

        $config = Config::get();

        // TODO exception
        switch ($dispatch['type']) {
            // 模块/控制器/操作
            case 'module' :
                $data = self::module($dispatch['result'], $config);
                break;
            // 控制器调用
            case 'controller' :
                $data = Loader::action($dispatch['controller'], $dispatch['params']);
                break;
        }

        // 监听并执行注册事件
        Hook::listen('shutdown');

        // TODO Response Event
    }

    // 调用模块
    public static function module($result, $config)
    {
        if (is_string($result)) {
            $result = explode('/', rtrim($result, '/'));
        }

        // TODO 多模块部署、模块初始化
        $moduleName = !$result['module'] ? $config['default_module'] : strtolower($result['module']);
        defined($moduleName) or define('MODULE_NAME', $moduleName);

        $controllerName = !$result['controller'] ? $config['default_controller'] : strtolower($result['controller']);
        defined($controllerName) or define('CONTROLLER_NAME', $controllerName);

        $actionName = !$result['action'] ? $config['default_action'] : strtolower($result['action']);
        defined($actionName) or define('ACTION_NAME', $actionName);

        $instance = Loader::controller($controllerName);

        $call = [$instance, $actionName];
        if (empty($instance) || empty($actionName)) {
            // TODO 这里的具体操作应当以注入方式解决
            throw new ViewException();
        }

        $data = self::invokeMethod($call);

        return $data;
    }

    public static function route($request, array $config)
    {
        // TODO 路由到具体url，获取为调度器功能
        $path = $request instanceof Request ? $request->path() : $request;

        // TODO 路由设置
        // 1 - 路由检测，是否开启路由
        // 2 - 导入路由设置
        // 3 - 路由检测 & 重定向访问路径

        // TODO AUTO
        $_REQUEST = array_merge($_GET, $_POST, $_COOKIE);

        // 解析地址
        $result = Router::parseUrl($path);

        return $request->dispatch($result);
    }

    public static function invokeMethod($method, $args = [])
    {
        if (is_array($method)) {
            $class = is_object($method[0]) ? $method[0] : new $method[0];
            if (method_exists($class, $method[1])) {
                $reflect = new \ReflectionMethod($class, $method[1]);
            } else {
                throw new ViewException();
            }
        }

        return $reflect->invokeArgs(isset($class) ? $class : null, $args);
    }
}
