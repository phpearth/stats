<?php

namespace PHPWorldWide\Stats\Tests\ExpressionLanguage;

use PHPWorldWide\Stats\ExpressionLanguage\StringExpressionLanguageProvider;
use PHPUnit\Framework\TestCase;

class StringExpressionLanguageProviderTest extends TestCase
{
    public function testGetFunctions()
    {
        $provider = new StringExpressionLanguageProvider();

        foreach ($provider->getFunctions() as $function) {
            $this->assertInstanceOf('\Symfony\Component\ExpressionLanguage\ExpressionFunction', $function);
        }
    }
}
