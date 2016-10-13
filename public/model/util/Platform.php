<?php
namespace common\model\util;

use common\literal\PlatformLiteral;
use zaor\Config;

/**
 * 平台关系映射
 * Class Platform
 * @package common\model\util
 */
class Platform
{
    public static function getInternalPlatform()
    {
        return [
            PlatformLiteral::GEWARA
        ];
    }
}