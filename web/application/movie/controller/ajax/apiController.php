<?php
namespace app\movie\controller\ajax;

use zaor\Request;
use zaor\view\Page;
use common\model\database\movieModel;

class apiController extends Page
{
    public function select()
    {
        // 获取请求参数
        $params = Request::instance()->input('test');

        // 获取数据库信息
        $student = movieModel::instance()->table('test')->limit(1)->select();

        echo json_encode($student);
    }
}