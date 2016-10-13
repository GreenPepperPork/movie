<?php
namespace app\movie\fragment\common;

use zaor\view\Fragement;

class headFragment extends Fragement
{
    public function init()
    {
        $this->assign('menu', '网站首页');

        $this->display();
    }
}