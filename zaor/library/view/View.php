<?php
namespace zaor\View;

use zaor\Componet;

class View
{
    private $data = [];

    private static $calledClass;

    public function assign($name, $value = '')
    {
        $this->data[$name] = $value;
    }

    public function import(array $value, $range = '')
    {
        if ($range) {
            $this->data[$range] = array_merge(isset($this->data[$range]) ? $this->data[$range] : [], $value);
        } else {
            $this->data = array_merge(is_array($this->data) ? $this->data : [], $value);
        }
    }

    public function get($name = '')
    {
        return $name ? $this->data[$name] : $this->data;
    }

    public function display($url = '')
    {
        $path = $this->fetch($url);
        include $path;
    }

    public function render($path, $config = [], $module = '')
    {
        Componet::render($path, $config, $module);
    }

    public function getCalledInfo()
    {
        $class = get_called_class();
        if (!isset(self::$calledClass[$class])) {
            $reflect = new \ReflectionClass($class);
            self::$calledClass[$class] = [
                '__NAME__'     => $reflect->getShortName(),
                '__CALL__'     => $reflect->getFileName(),
                '__CALL_DIR__' => dirname($reflect->getFileName())
            ];
        }

        return self::$calledClass[$class];
    }

    public function fetch($pageType)
    {
        return APP_PATH . DS . MODULE_NAME . DS . $pageType . DS . CONTROLLER_NAME . DS . ACTION_NAME . HTML_EXT;
    }

    function __get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : '';
    }
}
