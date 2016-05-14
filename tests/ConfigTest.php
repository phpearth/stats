<?php

namespace PHPWorldWide\Stats\Test;

use PHPWorldWide\Stats\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider configProvider
     */
    public function testGet($key, $expected)
    {
        $config = new Config(__DIR__.'/Fixtures/parameters.yml');

        $this->assertEquals($expected, $config->get($key));
    }

    public function configProvider()
    {
        return [
            ['fb_app_id', '123456789012345'],
            ['fb_app_secret', '12345'],
            ['urls', [['url_1', 1], ['url_2', 2], ['url_3', 3]]],
        ];
    }
}
