<?php
namespace common\model\database;

use vendor\db\Query;
use vendor\db\Model;
use vendor\db\Db;
use zaor\Config;

class MovieModel extends Model
{
    public static function instance()
    {
        static $query;

        if (!isset($query) || empty($query)) {
            $connect = Db::connect(Config::get('movie'));
            $query = new Query($connect);
        }

        return $query;
    }
}