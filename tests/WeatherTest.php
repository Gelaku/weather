<?php
/**
 * 单元测试
 * https://zhangxueren.club/2018/11/build_composer_2.html
 */

namespace Gelaku\Weather\Tests;

use Gelaku\Weather\Exceptions\InvalidArgumentException;
use Gelaku\Weather\Weather;
use PHPUnit\Framework\TestCase;

class WeatherTest extends TestCase
{
    protected $key = '高德key';

    public function testGetWeather()
    {
    }

    public function testGetLiveWeather()
    {
    }

    public function testGetForecastsWeather()
    {
    }

    public function testGetHttpClient()
    {
    }

    public function testSetGuzzleOptions()
    {
    }

    // 检查 $type 参数
    public function testGetWeatherWithInvalidType()
    {
        $w = new Weather($this->key);

        // 断言会抛出此异常类
        $this->expectException(InvalidArgumentException::class);

        // 断言异常消息为 'Invalid type value(base/all): foo'
        $this->expectExceptionMessage('Invalid type value(base/all): foo');

        $w->getWeather('深圳', 'all');

        $this->fail('Failed to assert getWeather throw exception with invalid argument.');
    }

    // 检查 $format 参数
    public function testGetWeatherWithInvalidFormat()
    {
        $w = new Weather($this->key);

        // 断言会抛出此异常类
        $this->expectException(InvalidArgumentException::class);

        // 断言异常消息为 'Invalid response format: array'
        $this->expectExceptionMessage('Invalid response format: array');

        // 因为支持的格式为 xml/json，所以传入 array 会抛出异常
        $w->getWeather('深圳', 'base', 'array');

        // 如果没有抛出异常，就会运行到这行，标记当前测试没成功
        $this->fail('Failed to assert getWeather throw exception with invalid argument.');
    }

}