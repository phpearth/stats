<?php

namespace PhpEarth\Stats\Tests;

use PhpEarth\Stats\Config;
use PhpEarth\Stats\Util\CodeDetector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class CodeDetectorTest extends TestCase
{
    public function testIsCode()
    {
        $config = new Config([
            __DIR__.'/../../config/parameters.yaml.dist',
            __DIR__.'/../../config/points.yaml',
        ]);

        $codeDetector = new CodeDetector();
        $codeDetector->setMinCodeLines($config->get('points')['min_code_lines']);
        $codeDetector->setPatterns($config->get('points')['code_regex']);

        $objects = Yaml::parse(file_get_contents(__DIR__.'/../Fixtures/data/topics.yaml'));
        $objects = $objects['topics'];

        foreach ($objects as $topic) {
            $this->assertEquals($codeDetector->isCode($topic['message']), $topic['is_code']);
        }
    }
}
