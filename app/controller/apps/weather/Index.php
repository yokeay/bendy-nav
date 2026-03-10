<?php

namespace app\controller\apps\weather;

use app\model\CardModel;
use app\PluginsBase;
use IP2Location\Database;

$directory = (__DIR__ . '/vendor/IP2Location/src');

$files = glob($directory . '/*.php');

foreach ($files as $file) {
    require_once $file;
}

class Index extends PluginsBase
{
    public $gateway = '';

    function _initialize()
    {
        parent::_initialize();
        $this->gateway = CardModel::config("weather", "gateway", "https://devapi.qweather.com");
    }

    function ip(): \think\response\Json
    {
        $file = __DIR__ . '/ipLocation/IP2LOCATION-LITE-DB5.BIN';
        if (file_exists($file)) {
            $ip = getRealIp();
            $db = new Database($file, Database::FILE_IO);
            try {
                $records = $db->lookup($ip, Database::ALL);
                $ipInfo = [
                    'ipAddress' => $records['ipAddress'],
                    'latitude' => $records['latitude'],
                    'longitude' => $records['longitude'],
                    'cityName' => $records['cityName'],
                    'regionName' => $records['regionName'],
                    'countryName' => $records['countryName']
                ];
                if ($ipInfo['latitude'] == 0 && $ipInfo['longitude'] == 0) {
                    $ipInfo['latitude'] = 39.91;
                    $ipInfo['longitude'] = 116.41;
                }
                return json(['code' => 1, 'msg' => 'success', 'data' => $ipInfo]);
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => '不支持ipv6']);
            }
        }

        return json(['code' => 0, 'msg' => '地理位置数据包不存在']);
    }

    function setting()
    {
        $this->getAdmin();
        if ($this->request->isPost()) {
            $form = $this->request->post();
            CardModel::saveConfigs("weather", $form);
            return $this->success("保存成功");
        }
        if ($this->request->isPut()) {
            $form = CardModel::configs('weather');
            return $this->success('ok', $form);
        }

        return $this->fetch("setting.html");
    }

    function everyDay(): \think\response\Json
    {

        $location = $this->request->get("location", "101010100");
        try {
            $result = \Axios::http()->get($this->gateway . '/v7/weather/7d', [
                'query' => [
                    'location' => $location,
                    'key' => CardModel::config('weather', 'key'),
                ]
            ]);
            if ($result->getStatusCode() === 200) {
                $json = \Axios::toJson($result->getBody()->getContents());
                if ($json && $json['code'] == "200") {
                    return $this->success($json['daily']);
                }
            }
        } catch (\Exception $e) {
        }
        return $this->error("数据获取错误");
    }

    function now(): \think\response\Json
    {

        $location = $this->request->get('location', '101010100');
        try {
            $result = \Axios::http()->get($this->gateway . '/v7/weather/now', [
                'query' => [
                    'location' => $location,
                    'key' => CardModel::config('weather', 'key'),
                ]
            ]);
            if ($result->getStatusCode() === 200) {
                $json = \Axios::toJson($result->getBody()->getContents());
                if ($json && $json['code'] == '200') {
                    return $this->success($json['now']);
                }
            }
        } catch (\Exception $e) {
        }
        return $this->error('数据获取错误');
    }

    function locationToCity(): \think\response\Json
    {

        $location = $this->request->all('location', '101010100');
        try {
            $result = \Axios::http()->get('https://geoapi.qweather.com/v2/city/lookup', [
                'query' => [
                    'location' => $location,
                    'key' => CardModel::config('weather', 'key'),
                ]
            ]);
            if ($result->getStatusCode() === 200) {
                $json = \Axios::toJson($result->getBody()->getContents());
                if ($json && $json['code'] == '200') {
                    if (count($json['location']) > 0) {
                        return $this->success($json['location'][0]);
                    }
                }
            }
        } catch (\Exception $e) {
        }
        return $this->error('数据获取错误');
    }

    function citySearch(): \think\response\Json
    {
        $city = $this->request->post("city", "");
        if (trim($city)) {
            try {
                $result = \Axios::http()->get('https://geoapi.qweather.com/v2/city/lookup', [
                    'query' => [
                        'location' => $city,
                        'key' => CardModel::config('weather', 'key'),
                    ]
                ]);
                if ($result->getStatusCode() === 200) {
                    $json = \Axios::toJson($result->getBody()->getContents());
                    if ($json && $json['code'] == '200') {
                        if (count($json['location']) > 0) {
                            return $this->success($json['location']);
                        }
                    }
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        return $this->error('数据获取错误');
    }
}