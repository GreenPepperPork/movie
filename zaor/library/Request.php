<?php
namespace zaor;

class Request
{
    // 调度器
    protected $dispatch = null;

    protected $path = null;

    protected $pathinfo;

    protected $attribute;

    protected static $instance;

    public static function instance($options = [])
    {
        if (empty(self::$instance)) {
            self::$instance = new static($options);
        }
        return self::$instance;
    }

    /**
     * 获取页面请求参数
     *
     * @param string $input
     * @return mixed
     */
    public function input($input = '', $default = null)
    {
        $request = $_GET + $_POST + $_REQUEST;

        return isset($request[$input]) ? $request[$input] : $default;
    }

    /**
     * 获取当前请求的URL
     * @return string
     */
    public function url()
    {
        $httpTag = Config::get('HTTP_URL');
        return isset($_SERVER[$httpTag]) ? $_SERVER[$httpTag] : '';
    }

    /**
     *  获取当前请求的URL(不含QUERY_STRING)
     */
    public function baseUrl()
    {
        $url = $this->url();
        return strpos($url, '?') ? strstr($url, '?', true) : $url;
    }

    /**
     * 获取当前请求URL的pathinfo信息(不含URL后缀)
     * @access public
     * @return string
     */
    public function path()
    {
        if (is_null($this->path)) {
            // 去除正常的URL后缀
            $this->path = preg_replace('/\.' . $this->ext() . '$/i', '', $this->pathinfo());
        }
        return $this->path;
    }

    /**
     * 当前URL的访问后缀
     * @access public
     * @return string
     */
    protected function ext()
    {
        return pathinfo($this->pathinfo(), PATHINFO_EXTENSION);
    }

    /**
     * 获取当前请求URL的pathinfo信息（含URL后缀）
     * @access public
     * @return string
     */
    protected function pathinfo()
    {
        // todo CLI模式下以及自定义fastcgi_params定义下的路径信息
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * 响应类调度器
     * @param  mixed $dispatch
     * @return mixed
     */
    public function dispatch($dispatch = null)
    {
        if (!empty($dispatch)) {
            $this->dispatch = $dispatch;
        }

        return $this->dispatch;
    }

    /**
     * 设置请求类参数值(全局变量)
     *
     * @param string $name
     * @param mixed  $value
     * @return mixed
     */
    public function set($name, $value)
    {
        return $this->attribute[$name] = $value;
    }

    /**
     * 获取请求类参数值
     *
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        return isset($this->attribute[$name]) ? $this->attribute[$name] : null;
    }
}
