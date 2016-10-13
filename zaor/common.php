<?php
return [
    // 根目录
    'NAMESPACE' => [
        ZAOR           => LIB_PATH,
        APP_NAMESPACE  => APP_PATH,

        'common'       => PUBLIC_PATH,
        'vendor'       => true,
        'Snoopy'       => true
    ],

    // APP加载目录
    'APP_CONF' => [
        PUBLIC_PATH . DS . 'config.php',
        APP_PATH . DS . 'config.php',

        PUBLIC_PATH . DS . 'database.php'
    ],

    // 类兼容表示符
    'CLASS_COMPAT' => '@',

    // 加载模板类
    'TPL_RECORD'        => TPL_PATH . '/record.tpl',
    'TPL_APP_EXCEPTION' => TPL_PATH . '/appException.tpl',

    'HTTP_URL'  => 'REQUEST_URI',

    'PAGE_VIEW'     => 'page',
    'FRAGMENT_VIEW' => 'fragment',

    'default_module'     => 'index',
    'default_controller' => 'index',
    'default_action'     => 'index',
];