<?php
namespace zaor;

// TODO 添加统一寄存错误类
use zaor\exception\ErrorException;

class Error
{
    public static function register()
    {
        error_reporting(E_ALL & ~E_NOTICE);
        set_error_handler([__CLASS__, 'appError']);
        set_exception_handler([__CLASS__, 'appException']);

        register_shutdown_function([__CLASS__, 'autoShutDownFunc']);

        APP_DEBUG && ini_set('display_errors', 'on');
    }

    public static function appError($level, $message, $file = '', $line = 0, $context = [])
    {
        // 错误级别设置
        if (error_reporting() && $level && DEBUG_TRACE) {
            $errorException = new ErrorException($level, $message, $file, $line, $context);
            Debugger::instance()->info($file, $errorException, Debugger::TYPE_ERR);
        }
    }

    public static function appException(Exception $e)
    {
        // TODO 可以通过注入的方式解决依赖
        self::getExceptionHandler()->report($e);

        if ($e instanceof \zaor\exception\ViewException) {
            self::getExceptionHandler()->render(Config::get('TPL_APP_EXCEPTION'));
        } else if (defined(DEBUG_TRACE_SWITCH)) {
            self::getExceptionHandler()->render(Config::get('TPL_APP_EXCEPTION'));
        } else {
            echo "File: {$e->getFile()}, line : {$e->getLine()}, {$e->getMessage()}";
        }
    }

    public static function autoShutDownFunc()
    {
        if (defined('DEBUG_TRACE') && DEBUG_TRACE) {
            Componet::load(Config::get('TPL_RECORD'), Debugger::instance()->get());
        }
    }

    public static function getExceptionHandler()
    {
        static $handler;

        if (empty($handler)) {
            $class = Config::get('Exception_Handler');
            if (class_exists($class)) {
                $handler = $class;
            } else {
                $handler = new Handle();
            }
        }

        return $handler;
    }
}
