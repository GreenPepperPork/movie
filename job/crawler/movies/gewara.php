<?php
namespace job\crawler\movies;

use \common\model\util\Map;
use \common\literal\PlatformLiteral;
use \common\model\util\City;
use \common\service\MovieService;
use \job\crawler;
use \DOMDocument;
use \DOMXPath;

/**
 * 获取格瓦拉电影 & 影院信息
 * 脚本执行频率 0 3 * * *
 *
 * @usage   php /data/movie/job/console.php --class=crawler_movies_gewara --movie=1 --cinema=1 --city=19
 * @author  yinggaozhen
 * @create  2016-10-04
 */
class gewara extends crawler
{
    const PLATFORM = 'gewara';

    /**
     * 格拉瓦电影列表
     */
    const MOVIE_REQ_URL  = 'http://m.gewara.com/movie/m/ajax/hotMovies.xhtml';

    /**
     * 格瓦拉影院列表
     */
    const CINEMA_REQ_URL = 'http://m.gewara.com/movie/m/ajax/getCinemaList.xhtml';

    public function initParams()
    {
        return [
            'city::',   // 请自行配置对应城市ID
            'movie::',  // 是否开启电影
            'cinema::', // 是否开启影院
        ];
    }

    public function launcher()
    {
        $movieSwitch  = (boolean) $this->getCommendArgs()['movie'];
        $cinemaSwitch = (boolean) $this->getCommendArgs()['cinema'];

        // 格瓦拉电影数据
        $movieSwitch and $this->getMovieList();

        // 格瓦拉影院数据
        $cinemaSwitch and $this->getCinemaList();
    }

    /**
     * 获取电影列表
     */
    public function getMovieList()
    {
        $movieFunc = $this->getMovieListFunc([], 3);

        while ($movieListHtml = $movieFunc()) {
            $document = new DOMDocument();
            $document->loadHTML($movieListHtml);

            foreach ((new DOMXPath($document))->query('//*[@class="ui_pic"]//a') as $node) {
                preg_match('/=(\d+)/', $node->getAttribute('href'), $movieInfo);
                $data['name'] = self::format($node->getElementsByTagName('img')->item(0)->getAttribute('alt'));
                $data['gewara_id'] = $movieInfo[1];

                MovieService::insertMovie($data, self::PLATFORM);
            }
        }
    }

    /**
     *  获取影院列表
     */
    public function getCinemaList()
    {
        // 长参指定城市范围
        $commendCities  = $this->getCityRange($this->getCommendArgs()['city']);
        // 配置文件中的城市范围
        $internalCities = City::getPlatormCityInfo(PlatformLiteral::GEWARA);

        foreach (array_intersect_key($internalCities, $commendCities) as $cityId => $cityInfo) {
            // 因为格瓦拉全城爬取的数量有限,所以爬取范围锁定至区域范围
            foreach ($cityInfo['area'] as $area) {
                $func = $this->getCinemaListFunc($cityInfo['code'], ['countycode' => $area['code']], 500);

                while ($listHtml = $func()) {
                    $document = new DOMDocument();
                    $document->loadHTML($listHtml);

                    foreach ((new DOMXPath($document))->query('//a[@id]') as $node) {
                        $cinemaGeo =  Map::instance()->addressToLatLng($node->childNodes->item(3)->nodeValue, $cityInfo['name']);

                        if (!$cinemaGeo['status']) {
                            continue;
                        }

                        $data['name']       = self::format($node->getElementsByTagName('b')->item(0)->nodeValue);
                        $data['city_id']    = $cityId;
                        $data['gewara_id']  = $node->getAttribute('id');
                        $data['lat']        = $cinemaGeo['lat'];
                        $data['lng']        = $cinemaGeo['lng'];
                        $data['address']    = $cinemaGeo['address'];

                        MovieService::insertCinema($data, self::PLATFORM);
                    }
                }
            }
        }
    }

    /**
     * 构建获取电影列表数据闭包函数,方便后期开多进程
     *
     * @param array $params  请求参数
     * @param int $loop      最大循环次数,防止意外死循环
     * @return \Closure
     */
    public function getMovieListFunc(array $params, $loop = 3)
    {
        return function () use (&$params, &$loop) {
            !isset($params['pageNo']) and $params['pageNo'] = 0;
            $listHtml = $this->snoopy->submit(self::MOVIE_REQ_URL, $params)->getResults();

            // 终止条件
            if (empty($listHtml) || !$loop) {
                return false;
            }

            ++$params['pageNo'] and --$loop;

            return '<?xml encoding="UTF-8">' . $listHtml;
        };
    }

    /**
     * 构建获取影院列表数据闭包函数,方便后期开多进程
     *
     * @param mixed $city    城市编号
     * @param array $params  请求参数
     * @param int   $loop    最大循环次数,防止意外死循环
     * @return \Closure
     */
    public function getCinemaListFunc($city, array $params, $loop = 2)
    {
        return function () use ($city, &$params, &$loop) {
            !isset($params['pageNo']) and $params['pageNo'] = 0;
            $this->snoopy->cookies['citycode'] = $city;
            $listHtml = $this->snoopy->submit(self::CINEMA_REQ_URL, $params)->getResults();

            // 终止条件
            if (empty($listHtml) || !$loop) {
                return false;
            }

            ++$params['pageNo'] and --$loop;

            return '<?xml encoding="UTF-8">' . $listHtml;
        };
    }
}