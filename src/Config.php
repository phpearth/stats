<?php

namespace PHPWorldWide\Stats;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Class Config
 */
class Config
{
    /**
     * @var array Yaml files with configuration values.
     */
    private $files = [];

    /**
     * @var array
     */
    private $values = [];

    /**
     * Config constructor. Sets up config parameters from YAML file.
     *
     * @param string $file YAML file location
     */
    public function __construct($file)
    {
        $this->addFile($file);
    }

    /**
     * Add file, later added files overwrite config values.
     *
     * @param string $file YAML file location.
     *
     * @throws \Exception
     */
    public function addFile($file)
    {
        $this->files[] = $file;

        $parser = new Parser();

        try {
            $values = $parser->parse(file_get_contents($file));
            $this->values = array_merge($this->values, $values);

            // set DateTime
            foreach ($this->values as $key => $value) {
                if (is_string($value) && false !== \DateTime::createFromFormat('Y-m-d G:i:s', $value)) {
                    $this->values[$key] = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
                }
            }
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
