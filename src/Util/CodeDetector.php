<?php

namespace PHPWorldWide\Stats\Util;

class CodeDetector
{
    /**
     * @var int
     */
    private $minCodeLines;

    /**
     * @var array
     */
    private $patterns;

    /**
     * @param int $minCodeLines
     */
    public function setMinCodeLines($minCodeLines)
    {
        $this->minCodeLines = $minCodeLines;
    }

    /**
     * Set array of regex code patters.
     *
     * @param array $patterns
     */
    public function setPatterns($patterns)
    {
        $this->patterns = $patterns;
    }

    /**
     * Determine if message contains code.
     *
     * @param string $message
     * @return bool
     */
    public function isCode($message)
    {
        $lines = array_map('trim', explode("\n", $message));
        $codeLines = 0;
        foreach ($lines as $line) {
            if ($line == "") {
                continue;
            }

            if ($this->isCodeLine($line)) {
                if (++$codeLines >= $this->minCodeLines) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determines whether text line is a code line.
     *
     * @param string $line The line to check.
     *
     * @return boolean True if the line is a line of code, otherwise false.
     */
    private function isCodeLine($line)
    {
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $line) === 1) {
                return true;
            }
        }

        return false;
    }
}
