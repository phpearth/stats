<?php

namespace PhpEarth\Stats\Tests;

use PhpEarth\Stats\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private $config;

    public function setUp()
    {
        $this->config = new Config([
            __DIR__.'/Fixtures/parameters.yml',
            __DIR__.'/Fixtures/points.yml'
        ]);

        $this->config->setParameter('start_datetime', \DateTime::createFromFormat('Y-m-d H:i:s', '2016-05-15 00:00:00'));
        $this->config->setParameter('end_datetime', \DateTime::createFromFormat('Y-m-d H:i:s', '2016-05-22 23:59:59'));
    }

    /**
     * @dataProvider configProvider
     */
    public function testGet($key, $expected)
    {
        $this->assertEquals($expected, $this->config->get($key));
    }

    /**
     * @dataProvider parametersProvider
     */
    public function testGetParameter($key, $expected)
    {
        $this->assertEquals($expected, $this->config->getParameter($key));
    }

    public function configProvider()
    {
        return [
            ['urls', [['url_1', 1], ['url_2', 2], ['url_3', 3]]]
        ];
    }

    public function parametersProvider()
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
