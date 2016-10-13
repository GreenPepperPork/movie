<?php
namespace app\movie\controller;

use zaor\view\Page;
use app\movie\model\movie;

class introController extends Page
{
    public function index()
    {
        // 应用Model层调用测试
        // $dbTest = movieModel::test();

        $this->assign('say', movie::say());
        $this->assign('time', movie::time());

        $this->display();
    }

    public function gallery()
    {
        $this->display();
    }
}