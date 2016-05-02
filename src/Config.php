<?php

namespace PHPWorldWide\Stats;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Class Config
 * @package PHPWorldWide\Stats
 */
class Config
{
    /**
     * @var mixed
     */
    private $values;

    /**
     * Config constructor. Sets up config parameters from YAML file.
     *
     * @param string $file YAML file location
     *
     * @throws \Exception
     */
    public function __construct($file)
    {
        $parser = new Parser();

        try {
            $this->values = $parser->parse(file_get_contents($file));
        } catch (ParseException $e) {
            throw new \Exception('Unable to parse the YAML string: '.$e->getMessage());
        }
    }

    /**
     * Returns configuration value by key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->values[$key];
    }
}
