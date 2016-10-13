<?php
namespace zaor;

use zaor\exception\AppException;

class Loader
{
    protected static $namespace = [];

    // 加载器注册
    public static function register($autoload = '')
    {
        spl_autoload_register($autoload ? $autoload : [__CLASS__, 'autoload']);
    }

    /**
     * 自动加载
     *
     * @param string $class
     * @return bool
     * @throws AppException
     */
    public static function autoload($class)
    {
        $class = ltrim(str_replace('@', '', $class, $compat), '\\');

        if (!empty(self::$namespace)) {
            list($name, $class) = explode('\\', $class, 2);

            if (isset(self::$namespace[$name])) {
                $path = self::$namespace[$name];
            } else {
                throw new AppException($name . ' namespace is not define');
            }
        }

        $basename = $path . DS . str_replace('\\', DS, $class);

        // TODO 可以做成单例加载
        if (is_file($filename = $basename . EXT)) {
            include $filename;
        } else if ($compat && is_file($filename = $basename . HTML_EXT)) {
            include $filename;
        } else {
            throw new AppException($filename . ' can not find');
        }
    }

    /**
     * 获取实例，可执行方法
     * @param  string $class  类名
     * @param  string $method 方法名
     *
     * @return mixed
     * @throws Exception
     */
    public static function instance($class, $method = '')
    {
        static $_instance = [];
        $indentity = $class . $method;

        if (!isset($_instance[$indentity])) {
            if (class_exists($class)) {
                $object = new $class();
                if (!empty($method) && method_exists($object, $method)) {
                    $_instance[$indentity] = call_user_func_array([&$object, $method], []);
                } else {
                    $_instance[$indentity] = $object;
                }
            } else {
                throw new Exception('class not exist :' . $class, 10007);
            }
        }

        return $_instance[$indentity];
    }

    /**
     * 添加命名空间，后续自动加载将根据namespace进行寻径
     * @param array|string $namespace 命名空间
     * @param string       $path      路径
     */
    public static function addNamespace($namespace, $path = '')
    {
        if (is_array($namespace)) {
            self::$namespace = array_merge(self::$namespace, $namespace);
        } else {
            self::$namespace[$namespace] = $path;
        }
    }

    // 控制器实例化
    public static function controller($name, $module = '', $layer = '')
    {
        static $_instance = [];

        $layer  = $layer  ?: CONTROLLER_LAYER;
        $module = $module ?: MODULE_NAME;

        if (isset($_instance[$name . $layer])) {
            return $_instance[$name . $layer];
        }

        $class = self::parseClass($name, $module, $layer);

        if (class_exists($class)) {
            $action = new $class();
            $_instance[$name . $layer] = $action;
            return $action;
        }

        // TODO action is empty
        return false;
    }

    /**
     * 远程调用模块的操作方法 参数格式 [模块/控制器/]操作
     * @param string $url 调用地址
     * @param string $layer 要调用的控制层名称
     * @return mixed
     */
    public static function action($url, $args = [], $layer = CONTROLLER_LAYER)
    {
        $info = pathinfo($url);

        $module = dirname($info['dirname']);
        define('MODULE_NAME', $module);

        $controller = basename($info['dirname']);
        $action = $info['basename'];

        // TODO 完善Module,Controller,Action三者之间的关系
        $class = Loader::controller($controller);

        App::invokeMethod([$class, $action], $args);
    }

    // 导入所需的类库，具备缓存功能
    public static function import(){}

    // 模型实例化
    public static function model(){}

    // 字符串风格转换
    public static function parseName(){}

    // 解析应用类名称
    public static function parseClass($name, $module, $layer)
    {
        $name = str_replace(['/', '.'], '\\', $name);
        $array = explode('\\', $name);
        $path  = implode('\\', $array);

        // TODO 这里还需要完善
        return '\\' . APP_NAMESPACE . '\\' . $module . '\\' . $layer . '\\' . $path . ucfirst($layer);
    }
}
