<?php
namespace common\model\util;

use zaor\Config;

class Map
{
    private static $instance = null;

    function __construct()
    {
    }

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function addressToLatLng($address, $city = '')
    {
        if (empty($address)) {
            return false;
        }

        $params['address'] = $address;
        $city and $params['city'] = $city;

        $request = $this->request(Config::get('AMAP_GEO_TRANS_API'), $params);
        $geocodes = reset($request['geocodes']);
        list($lng, $lat) = explode(',', $geocodes['location']);

        return [
            'status'  => $request['status'] === '1',
            'address' => $geocodes['formatted_address'],
            'lng'     => (float) $lng,
            'lat'     => (float) $lat,
        ];
    }

    private function request($url, array $params, $method = 'get')
    {
        if (empty($params)) {
            return false;
        }

        $params['key'] = $this->key();
        $result = Curl::$method($url, $params);

        return json_decode($result, true);
    }

    private function key()
    {
        static $keys;

        if (empty($keys)) {
            $keys = Config::get('AMAP_KEY');
        }

        return is_array($keys) ? $keys[array_rand($keys)] : $keys;
    }
}