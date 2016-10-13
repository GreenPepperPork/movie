<?php
namespace zaor;

class Componet
{
    const METHOD = '_load';

    /**
     * 加载组件页面(加载文件[php, phtml])
     * @param $path
     * @param array $config
     * @param string $module
     */
    public static function render($path, $config = [], $module = '')
    {
        $module = $module ?: MODULE_NAME;
        $method = isset($config['__METHOD']) ? $config['__METHOD'] : self::METHOD;

        // 根据路径解析类
        $class = Loader::parseClass($path, $module, Config::get('FRAGMENT_VIEW'));

        // TODO 调用机制需进一步调整
        try {
            Loader::autoload(Config::get('CLASS_COMPAT') . $class);
            if (class_exists($class, false)) {
                // 如果此类存在, 则路由到对应的PHP文件中
                call_user_func_array([new $class, $method], [$config]);
            }
        } catch (\Exception $e) {
            trigger_error($e->getMessage());
        }
    }

    /**
     * @param $path
     * @param array $infos
     * @return mixed
     */
    public static function load($path, $infos = [])
    {
        if (is_file($path)) {
            include $path;
        } else {
            trigger_error($path . ' is not exsist.');
        }
    }
}