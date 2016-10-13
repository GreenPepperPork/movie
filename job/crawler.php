<?php
namespace job;

use zaor\Debugger;
use \Snoopy\Snoopy;

/**
 * 基础抽象爬虫类
 *
 * @usage php /data/movie/job/console.php --class=crawler_taobao --city=hangzhou
 * @package job
 */
abstract class crawler
{
    /**
     * @var \Snoopy\Snoopy
     */
    protected $snoopy = null;

    private $commendArgs = null;

    /**
     * 初始化长参
     * @return array
     */
    protected function initParams()
    {
        return [];
    }

    /**
     * 主体函数
     */
    public function init()
    {
        Debugger::instance()->info('start');

        $this->snoopy = new Snoopy;
        $this->launcher();

        Debugger::instance()->info('end');

        $diff = Debugger::instance()->diff('start', 'end');
        echo "***** Job End, Time : {$diff['time']}, Memory : {$diff['memory']}MB *****" . PHP_EOL;
    }

    /**
     * 主体实现函数
     * @return mixed
     */
    abstract public function launcher();

    /**
     * 获取长参值
     * @return mixed
     */
    public function getCommendArgs()
    {
        if (is_null($this->commendArgs)) {
            $this->commendArgs = getopt('', $this->initParams());
        }

        return $this->commendArgs;
    }

    /**
     * 获取城市集合,格式如11~12,17,返回最终结果为[11, 12, 17]的城市集合
     *
     * @param string $city 城市
     * @return array
     */
    public function getCityRange($city)
    {
        $result = $range = [];

        foreach (explode(',', $city) as $item) {
            if (strpos('~', $item) === false) {
                array_push($range, $item);
            } else {
                list($start, $end) = explode('~', $item);
                abs($end - $start) < 100 and $range = array_merge($range, range($start, $end));
            }

        }

        foreach ($range as $city) {
            $result[$city] = $city;
        }

        return $result;
    }

    public static function getDateRange($range = 3, $format = 'Y-m-d')
    {
        $dateRange = [];
        for ($i = 0; $i < $range; $i++) {
            $dateRange[] = date('Y-m-d', strtotime("+{$i} day"));
        }
        return $dateRange;
    }

    public static function format($string)
    {
        return trim(str_replace(['（', '）', '：', ' '], ['(', ')', ':', ''], $string));
    }
}