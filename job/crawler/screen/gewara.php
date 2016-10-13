<?php
namespace job\crawler\screen;

use common\literal\PlatformLiteral;
use common\service\MovieService;
use \job\crawler;
use \DOMDocument;
use \DOMXPath;

/**
 * 获取格瓦拉场次信息
 * 脚本执行频率 0 *\/6 * * *
 *
 * @usage   php /data/movie/job/console.php --class=crawler_screen_gewara --city=19
 * @author  yinggaozhen
 * @create  2016-10-06
 * @tip
 *   校验地址 : http://m.gewara.com/movie/m/cacheSeat.xhtml?mpid=342315089
 *   其中mpid即为screen_id
 */
class gewara extends crawler
{
    const PLATFORM = 'gewara';

    const SCREEN_REQ_URL  = 'http://m.gewara.com/movie/m/ajax/chooseMovieOpi.xhtml';

    public function initParams()
    {
        return [
            'city::'
        ];
    }

    public function launcher()
    {
        $cities = $this->getCityRange($this->getCommendArgs()['city']);

        // 电影列表
        $movieList  = $this->getMovieList();
        // 影院列表
        $cinemaList = $this->getCinemaList($cities);
        // 日期
        $dateRange  = self::getDateRange();

        // 循环嵌套循环是为了更好的循环...
        foreach ($movieList as $movie) {
            foreach ($cinemaList as $cinema) {
                foreach ($dateRange as $date) {
                    $mid = $movie['gewara_id'];
                    $cid = $cinema['gewara_id'];

                    $screenHtml = $this->getScreenList($mid, $cid, $date);

                    $document = new DOMDocument();
                    $document->loadHTML($screenHtml);
                    foreach ((new DOMXPath($document))->query('//a') as $node) {
                        // 场次ID
                        preg_match('/mpid=(\d+)/', $node->getAttribute('href'), $screen);
                        $sid = (int) $screen[1];

                        $detail = $node->getElementsByTagName('span');

                        // 开场时间
                        preg_match_all('/\d{2}:\d{2}/', $detail->item(0)->nodeValue, $time);
                        list($start, $end) = [$time[0][0], $time[0][1]];

                        // 场次类型以及厅室
                        $tag  = self::format($detail->item(1)->getElementsByTagName('b')->item(0)->nodeValue);
                        $room = self::format($detail->item(1)->getElementsByTagName('em')->item(0)->nodeValue);

                        // 价格
                        preg_match_all('/\d+/', $detail->item(2)->nodeValue, $price);
                        list($discount, $normal) = [$price[0][0], $price[0][1]];

                        // TODO send mail warning
                        if (empty($start) || empty($discount) || empty($sid)) {
                            continue;
                        }

                        $data['tag']       = $tag;
                        $data['room']      = $room;
                        $data['price']     = (float) $discount;
                        $data['platform']  = PlatformLiteral::GEWARA;
                        $data['movie_id']  = $mid;
                        $data['cinema_id'] = $cid;
                        $data['screen_id'] = $sid;
                        $data['open_time'] = strtotime($date . ' ' . $start);

                        MovieService::insertScreen($data, self::PLATFORM);
                    }
                }
            }
        }
    }

    public function getScreenList($mid, $cid, $date)
    {
        $params['mid'] = $mid;
        $params['cid'] = $cid;
        $params['openDate'] = date('Y-m-d', strtotime($date));

        $listHtml = $this->snoopy->submit(self::SCREEN_REQ_URL, $params)->getResults();
        return '<?xml encoding="UTF-8">' . $listHtml;
    }

    public function getMovieList()
    {
        return MovieService::getMovieList();
    }

    public function getCinemaList($cityies = [])
    {
        $cityies = $cityies ?: [];

        return MovieService::getCinemaList($cityies);
    }
}