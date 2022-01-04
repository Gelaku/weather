<?php
/**
 * 获取天气数据
 * https://zhangxueren.club/2018/11/build_composer_1.html
 */

namespace Gelaku\Weather;

use Gelaku\Weather\Exceptions\HttpException;
use Gelaku\Weather\Exceptions\InvalidArgumentException;
use GuzzleHttp\Client;

class Weather
{
    protected $key;
    protected $guzzleOptions = [];

    /**
     * 构造函数设置 API Key
     * @param  string  $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * 返回 guzzle 实例
     * @return Client
     */
    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    /**
     * 自定义实例参数
     * @param  array  $options
     */
    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    /**
     * 获取实时天气信息
     * @param  string  $city
     * @param  string  $format
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLiveWeather(string $city, string $format = 'json')
    {
        return $this->getWeather($city, 'base', $format);
    }

    /**
     * 获取天气预报
     * @param  string  $city
     * @param  string  $format
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getForecastsWeather(string $city, string $format = 'json')
    {
        return $this->getWeather($city, 'all', $format);
    }

    /**
     * 返回天气数据
     * @param  string  $city  城市编码
     * @param  string|string  $type  气象类型
     * @param  string|string  $format  返回格式
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getWeather(string $city, string $type = 'base', string $format = 'json')
    {
        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';

        // 对 $type 与 $format 参数进行检查，不在范围内的抛出异常
        if (!in_array(strtolower($type), ['base', 'all'])) {
            throw new InvalidArgumentException('Invalid type value(base/all)：'.$type);
        }

        if (!in_array(strtolower($format), ['xml', 'json'])) {
            throw new InvalidArgumentException('Invalid response format：'.$format);
        }

        // 封装 query 参数，并对空值进行过滤
        $query = array_filter([
            'key'        => $this->key,
            'city'       => $city,
            'extensions' => strtolower($type),
            'output'     => strtolower($format),
        ]);

        try {
            // 调用 getHttpClient 获取实例，并调用该实例的 get 方法
            $response = $this->getHttpClient()->get($url, ['query' => $query])->getBody()->getContents();

            // 返回值根据 $format 返回不同的格式
            return 'json' === $format ? json_decode($response, true) : $response;
        } catch (\Exception $e) {
            // 当调用出现异常时捕获并抛出，消息为捕获到的异常消息
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }
}