<?php
namespace zaor;

class Exception extends \Exception
{
    protected $data = [];

    /**
     * 设置异常DEBUG信息
     * Exception Data
     * ---------------------------------------
     * Label 1
     *      key1 value1
     *      key2 value2
     *
     * Label 2
     *      key1 value1
     *      key2 value2
     *
     * @param string $label 数据分类标签
     * @param array  $data  需要显示的debug信息
     */
    final protected function setData($label, array $data)
    {
        $this->data[$label] = $data;
    }

    /**
     * 获取Debug调试信息
     * @param string $label 数据分类标签
     * @return array
     */
    final protected function getData($label = '')
    {
        return !$label ? $this->data : $this->data[$label];
    }
}
