<?php
date_default_timezone_set('PRC');
// 系统信息
define('SYS_TIME', microtime(true));
define('SYS_MEM', memory_get_usage());
define('ZAOR', 'zaor');

// 版本信息
define('VERSION', '0.0.1');

// 环境变量
define('DS', '/');
define('EXT', '.php');
define('TPL_EXT', '.tpl');
define('HTML_EXT', '.html');
define('CONTROLLER_LAYER', 'controller');
define('ROOT_PATH', dirname(__DIR__));
define('WEB_PATH', ROOT_PATH . DS . 'web');
define('JOB_PATH', ROOT_PATH . DS . 'job');
define('ZAOR_PATH', ROOT_PATH . DS . 'zaor');
define('APP_PATH', WEB_PATH . DS . 'application');
define('LIB_PATH', ZAOR_PATH . DS . 'library');
define('PUBLIC_PATH', ROOT_PATH . DS . 'public');
define('TPL_PATH', PUBLIC_PATH . DS . 'tpl');

// 配置类参数
define('APP_CONF', 'APP_CONF');
define('COMMON_CONF', ZAOR_PATH . DS . 'common.php');

// 系统常量
define('APP_NAMESPACE', 'app');
define('JOB_NAMESPACE', 'job');
define('AUTO_LOAD', true);
define('APP_DEBUG', true);
define('DEBUG_TRACE_SWITCH', 'debug');