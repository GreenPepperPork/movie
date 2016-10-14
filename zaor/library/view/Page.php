<?php
namespace zaor\view;

use zaor\Config;
class Page extends View
{
    // 模板变量
    protected $data = [];

    function __construct() {}

    public function fetch($pageType = '')
    {
        return parent::fetch($pageType ?: Config::get('PAGE_VIEW'));
    }
}
