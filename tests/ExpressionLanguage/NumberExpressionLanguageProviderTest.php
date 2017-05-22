<?php

namespace PhpEarth\Stats\Tests\ExpressionLanguage;

use PhpEarth\Stats\ExpressionLanguage\NumberExpressionLanguageProvider;
use PHPUnit\Framework\TestCase;

class NumberExpressionLanguageProviderTest extends TestCase
{
    public function testGetFunctions()
    {
        $provider = new NumberExpressionLanguageProvider();

        foreach ($provider->getFunctions() as $function) {
            $this->assertInstanceOf('\Symfony\Component\ExpressionLanguage\ExpressionFunction', $function);
        }
    }
}
