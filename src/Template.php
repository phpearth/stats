<?php

namespace PHPWorldWide\Stats;

class Template
{
    protected $filename;

    protected $variables = [];

    /**
     * Contstrutor.
     *
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Sets variable.
     *
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->variables[$key] = $value;
    }

    /**
     * Gets variable.
     *
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->variables[$key];
    }

    /**
     * Returns rendered template.
     *
     * @return string
     */
    public function render()
    {
        extract($this->variables);
        chdir(dirname($this->filename));
        ob_start();

        include basename($this->filename);

        return ob_get_clean();
    }
}
