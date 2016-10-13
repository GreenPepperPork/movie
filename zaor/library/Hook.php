<?php
namespace zaor;

class Hook
{
    private static $tags;

    /**
     * 动态添加行为扩展到某个标签
     * @param string $tag      标签行为
     * @param mixed  $behavior 标签行为
     * @param bool   $first    是否放到开头进行执行
     */
    public static function add($tag, $behavior, $first = false)
    {
        if (!isset(self::$tags[$tag])) {
            self::$tags[$tag] = [];
        }

        if ($first) {
            array_unshift(self::$tags[$tag], $behavior);
        } else {
            self::$tags[$tag][] = $behavior;
        }
    }

    // TODO 批量导入行为标签
    public static function import($tags) {}

    /**
     * 获取具体行为标签行为信息
     *
     * @param string $tag 标签名称
     * @return mixed
     */
    public static function get($tag = '')
    {
        return empty($tag) ? self::$tags : self::$tags[$tag];
    }

    /**
     * 监听行为标签
     *
     * @param string $tag    行为标签
     * @param mixed  $params 行为参数
     * @param mixed  $extra  额外行为参数
     * @return bool|mixed
     */
    public static function listen($tag, $params = null, $extra = null)
    {
        $result = true;

        if (isset(self::$tags[$tag])) {
            foreach (self::$tags[$tag] as $behaviors) {
                $result = self::exec($behaviors, $tag, $params, $extra);

                if ($result === false) {
                    return $result;
                }
            }
        }

        return $result;
    }

    /**
     * 执行监听行为
     *
     * @param mixed $behavior 标签行为
     * @param mixed $params   行为参数
     * @param mixed $extra    额外行为参数
     * @return mixed
     */
    public static function exec($behavior, $params = null, $extra = null)
    {
        if ($behavior instanceof \Closure) {
            $result = call_user_func_array($behavior, [$params, $extra]);
        } else if (is_callable($behavior)) {
            $result = call_user_func_array($behavior, [$params, $extra]);
        } else {
            $obj = new $behavior;
            $obj->run($params, $extra);
        }

        return $result;
    }
}