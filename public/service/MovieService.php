<?php
namespace common\service;

use \common\model\database\MovieModel;

class MovieService
{
    /**
     * 获取电影列表
     *
     * @return mixed
     */
    public static function getMovieList()
    {
        $where['isvalid'] = 1;

        return MovieModel::instance()->table('pre_movie')->where($where)->select();
    }

    /**
     * 获取影院列表
     *
     * @param array $cities
     * @return mixed
     */
    public static function getCinemaList($cities = [])
    {
        return MovieModel::instance()->table('pre_cinema')->select();
    }

    /**
     * 插入各个平台的影院信息,如果其他影院已经有了,则插入,否则更新
     * @param array $data  保存更新数据
     * @param $type string 后期用于日志记录
     */
    public static function insertCinema($data, $type)
    {
        try {
            MovieModel::instance()->table('pre_cinema')->insert($data);
            echo "[{$type}] cinema {$data['name']} write success" . PHP_EOL;
        } catch (\Exception $e) {
            // TODO record error log
            $where['name'] = $data['name'];
            MovieModel::instance()->table('pre_cinema')->where($where)->update($data);
        }
    }

    /**
     * 插入各个平台的电影信息,如果其他影院已经有了,则插入,否则更新
     * @param array $data  保存更新数据
     * @param $type string 后期用于日志记录
     */
    public static function insertMovie($data, $type)
    {
        try {
            MovieModel::instance()->table('pre_movie')->insert($data);
            echo "[{$type}] movie {$data['name']} write success" . PHP_EOL;
        } catch (\Exception $e) {
            // TODO record error log
            $where['name'] = $data['name'];
            MovieModel::instance()->table('pre_movie')->where($where)->update($data);
        }
    }

    /**
     * 插入各个平台的场次信息,如果其他场次已经有了,则插入,否则更新
     * @param array $data  保存更新数据
     * @param $type string 后期用于日志记录
     */
    public static function insertScreen($data, $type)
    {
        try {
            MovieModel::instance()->table('pre_screen')->insert($data);
            echo "[{$type}] screen {$data['movie_id']} - {$data['cinema_id']} - {$data['screen_id']} write success" . PHP_EOL;
        } catch (\Exception $e) {
            $where['movie_id']  = $data['movie_id'];
            $where['cinema_id'] = $data['cinema_id'];
            $where['screen_id'] = $data['screen_id'];

            // TODO record error log
            MovieModel::instance()->table('pre_screen')->where($where)->update($data);
            echo "[{$type}] screen {$data['movie_id']} - {$data['cinema_id']} - {$data['screen_id']} update ~" . PHP_EOL;
        }
    }
}