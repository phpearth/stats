<?php

namespace PHPWorldWide\Stats;

use Symfony\Component\Yaml\Parser;

class Config
{
    private $values = [];

    public function __construct($file)
    {
        // set up config parameters from yaml
        $parser = new Parser();

        try {
            $this->values = $parser->parse(file_get_contents($file));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
    }

    public function get($key)
    {
        return $this->values[$key];
    }
}