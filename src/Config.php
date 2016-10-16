<?php

namespace PHPWorldWide\Stats;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

/**
 * Configuration which reads values from YAML files.
 */
class Config
{
    /**
     * @var array
     */
    private $values = [];

    /**
     * Constructor. Sets up config parameters from YAML file(s). Later files
     * overwrite config values.
     *
     * @param array $files YAML configuration file(s)
     * @throws \Exception
     */
    public function __construct(array $files)
    {
        foreach ($files as $file) {
            $parser = new Parser();
            try {
                $values = $parser->parse(file_get_contents($file), false, false, false);
                $this->values = array_merge($this->values, $values);
            } catch (ParseException $e) {
                throw new \Exception('Unable to parse the YAML string: '.$e->getMessage());
            }
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

    /**
     * Returns configuration value by key from parameters configuration.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getParameter($key)
    {
        return $this->values['parameters'][$key];
    }

    /**
     * Manually set the configuration parameter value by key.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setParameter($key, $value)
    {
        $this->values['parameters'][$key] = $value;
    }
}
