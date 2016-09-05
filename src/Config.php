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
     * @param string|array $files YAML configuration file(s)
     */
    public function __construct(array $files)
    {
        foreach ($files as $file) {
            $parser = new Parser();
            try {
                $values = $parser->parse(file_get_contents($file), false, false, false);
                $this->values = array_merge($this->values, $values);

                // set DateTime
                foreach ($this->values['parameters'] as $key => $value) {
                    if (is_string($value) && false !== \DateTime::createFromFormat('Y-m-d H:i:s', $value)) {
                        $this->values['parameters'][$key] = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
                    }
                }
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
}
