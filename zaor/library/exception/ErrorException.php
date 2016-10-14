<?php
namespace zaor\exception;

use \zaor\exception;
class ErrorException extends Exception
{
    /**
     * 错误异常类构造函数
     *
     * @param string  $severity 错误级别
     * @param string  $message  错误信息
     * @param string  $file     错误文件路径
     * @param integer $line     错误行数
     * @param array   $context  错误上下文
     */
    public function __construct($severity, $message, $file, $line, array $context = [])
    {
        $this->severity = $severity;
        $this->message  = $message;
        $this->file     = $file;
        $this->line     = $line;
        $this->code     = 0;

        empty($context) || $this->setData('Error Context', $context);
    }

    public function getServerity()
    {
        return $this->severity;
    }
}
