<?php
namespace app\movie\model;
use \common\model\database\movieModel;

class movie
{
    public static function say()
    {
        return '执子之手，与子偕老';
    }

    public static function time()
    {
        return date('Y-m-d H:i:s');
    }

    public function select()
    {
        // TODO ORM多数据库配置读取
        movieModel::test();
    }
}