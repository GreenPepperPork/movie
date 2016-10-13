<?php
namespace zaor;

class Config
{
    // 配置参数
    protected static $config = [];
    // 参数作用域
    private static $range = '_sys_';

    /**
     * 加载配置文件
     *
     * @param  string $files 配置文件名
     * @param  string $name  配置名称(如设置二级配置)
     * @return mixed
     */
    public static function load($files, $name = '', $range = '')
    {
        $range = $range ? :self::$range;
        if (!isset(self::$config[$range])) {
            self::$config[$range] = [];
        }

        if (!empty($files)) {
            foreach ($files as $zoom => $file) {
                if (is_file($file)) {
                    // 加载文件
                    self::set($name, include $file, $range);
                } else if (is_dir($file)) {
                    // 加载目录
                    $scanFiles = array_diff(scandir($file), ['.', '..']);
                    if (empty($scanFiles)) return;
                    foreach ($scanFiles as $scanFile) {
                        $file = $file . DS . $scanFile;
                        is_file($file) AND self::set($name, include $file, $range);
                    }
                }
            }
        }

        return self::$config[$range];
    }

    public static function set($name, $value, $range = '')
    {
        $range = $range ?: self::$range;
        $name  = $name ? strtolower($name) : null;
        !isset(self::$config[$range]) && self::$config[$range] = [];

        if (is_string($value)) {
            if (!strpos($name, '.')) {
                self::$config[$range][$name] = $value;
            } else {
                $name = explode('.', $name);
                self::$config[$range][$name[0]][$name[1]] = $value;
            }
            return true;
        } elseif (is_array($value)) {
            if (!empty($name)) {
                !isset(self::$config[$range][$name]) AND self::$config[$range][$name] = [];
                self::$config[$range][$name] = array_merge(self::$config[$range][$name], $value);
                return self::$config[$range][$name];
            } else {
                self::$config[$range] = array_change_key_case(array_merge(self::$config[$range], $value));
            }
        }
        return self::$config[$range];
    }

    public static function get($name = null, $range = '')
    {
        // TODO 相关配置命名需要调整
        $range = $range ?: self::$range;
        $name = !empty($name) ? strtolower($name) : null;

        if (empty($name) && isset(self::$config[$range])) {
            return self::$config[$range];
        }

        if (!strpos($name, '.')) {
            return isset(self::$config[$range][$name]) ? self::$config[$range][$name] : null;
        } else {
            $names = explode('.', $name);
            return isset(self::$config[$names[0]][$names[1]]) ? self::$config[$names[0]][$names[1]] : null;
        }
    }

    /**
     * 重置配置参数
     * @param string|boolean $range 清理范围，若为true则全部清空
     */
    public static function reset($range =  '')
    {
        $range = $range ? : self::$range;
        true === $range ? self::$config = [] : self::$config[$range] = [];
    }
}
