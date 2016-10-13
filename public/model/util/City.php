<?php
namespace common\model\util;

use common\literal\CityLiteral;
use zaor\Config;

/**
 * 内部城市关系映射平台城市
 * Class City
 * @package common\model\util
 */
class City
{
    /**
     * 获取内部城市ID
     *
     * @return array
     */
    public static function getInternalCity()
    {
        return [
            'shanghai' => CityLiteral::SHANGHAI,
            'hangzhou' => CityLiteral::HANGZHOU
        ];
    }

    /**
     * 获取平台城市信息
     *
     * @see CityLiteral
     * @param int $platform 平台ID
     * @param int $city     内部城市ID
     */
    public static function getPlatormCityInfo($platform, $city = null)
    {
        $mapping = Config::get('CITY_MAPPING');

        return is_null($city) ? $mapping[$platform] : $mapping[$platform][$city];
    }
}