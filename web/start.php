<?php
namespace zaor;

require dirname(__DIR__ ) . '/zaor/base.php';
require LIB_PATH . DS . 'Loader.php';

$mode = include ZAOR_PATH . DS . 'common.php';

if (isset($mode)) {
    Loader::addNamespace($mode['NAMESPACE']);
}

// 自动注册
Loader::register();

// 注册错误和捕获异常
Error::register();

Config::set('', $mode, '_sys_');
if (isset($mode[APP_CONF])) {
    Config::load($mode[APP_CONF]);
}

// 加载Composer文件
require ROOT_PATH . DS . 'vendor/autoload.php';

// 升序主入口
if (AUTO_LOAD) {
    App::run();
}