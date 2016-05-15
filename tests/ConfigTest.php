<?php

namespace PHPWorldWide\Stats\Tests;

use PHPWorldWide\Stats\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private $config;

    public function setUp()
    {
        $this->config = $config = new Config(__DIR__.'/Fixtures/parameters.yml.dist');
    }

    /**
     * @dataProvider distConfigProvider
     */
    public function testGet($key, $expected)
    {
        $this->assertEquals($expected, $this->config->get($key));
    }

    /**
     * @dataProvider configProvider
     */
    public function testAddFile($key, $expected)
    {
        $this->config->addFile(__DIR__.'/Fixtures/parameters.yml');

        $this->assertEquals($expected, $this->config->get($key));
    }

    public function distConfigProvider()
    {
        return [
            ['fb_app_id', 'changethisvalue'],
            ['fb_app_secret', 'appsecret'],
            ['urls', [['url_1', 1], ['url_2', 2], ['url_3', 3]]],
            ['start_datetime', \DateTime::createFromFormat('Y-m-d H:i:s', '2015-01-01 00:00:00')],
            ['end_datetime', \DateTime::createFromFormat('Y-m-d H:i:s', '2015-02-02 23:59:59')],
        ];
    }

    public function configProvider()
    {
        return [
            ['fb_app_id', '123456789012345'],
            ['fb_app_secret', '12345'],
            ['urls', [['url_1', 1], ['url_2', 2], ['url_3', 3]]],
            ['start_datetime', \DateTime::createFromFormat('Y-m-d H:i:s', '2016-05-15 00:00:00')],
            ['end_datetime', \DateTime::createFromFormat('Y-m-d H:i:s', '2016-05-22 23:59:59')],
        ];
    }
}
