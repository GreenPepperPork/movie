<?php
namespace zaor\View;

use zaor\exception\AppException;
class Fragement extends View
{
    function _load($config = [])
    {
        $this->import($config);

        $invoke = [$this, 'init'];
        if (is_callable($invoke)) {
            call_user_func($invoke);
        } else {
            // TODO 异常提示需要完善
            throw new AppException();
        }
    }

    // TODO 自定义路径
    public function fetch($pageType = '')
    {
        $callInfo = $this->getCalledInfo();
        return $callInfo['__CALL_DIR__'] . DS . $callInfo['__NAME__'] . HTML_EXT;
    }

    public function display($url = '')
    {
        include $this->fetch();
    }
}
