<?php
use common\literal\CityLiteral;
use common\literal\PlatformLiteral;

return [
    // 高德LBS WEB API KEY
    'AMAP_KEY' => [
        '5aab82a6a79b8a07e62c7f18dd394d20'
    ],

    // 高德LBS 地理转换API
    'AMAP_GEO_TRANS_API' => 'http://restapi.amap.com/v3/geocode/geo',

    // 城市映射表 内部ID <=> 平台ID
    'CITY_MAPPING' => [

        /**
         * <格瓦拉>城市映射关系
         */
        PlatformLiteral::GEWARA => [
            // 上海
            CityLiteral::SHANGHAI => [
                'name'   => '上海',
                'code'   => 310000,
                'pinyin' => 'shanghai',

                // 爬虫爬取的区域,鉴于格瓦拉全城爬取的数量有限,所以只能拆分至区域
                'area'      => [
                    ['name' => '浦东区', 'code' => 310115],
                ]
            ],
            // 杭州
            CityLiteral::HANGZHOU => [
                'name'   => '杭州',
                'code'   => 330100,
                'pinyin' => 'hangzhou',

                // 爬虫爬取的区域,鉴于格瓦拉全城爬取的数量有限,所以只能拆分至区域
                'area'      => [
                    ['name' => '西湖区', 'code' => 330186],
                    ['name' => '滨江区', 'code' => 330108]
                ]
            ]
        ]
    ]
];