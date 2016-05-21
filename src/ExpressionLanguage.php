<?php

namespace PHPWorldWide\Stats;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage as BaseExpressionLanguage;
use Symfony\Component\ExpressionLanguage\ParserCache\ParserCacheInterface;
use PHPWorldWide\Stats\ExpressionLanguage\StringExpressionLanguageProvider;
use PHPWorldWide\Stats\ExpressionLanguage\NumberExpressionLanguageProvider;

class ExpressionLanguage extends BaseExpressionLanguage
{
    public function __construct(ParserCacheInterface $parser = null, array $providers = [])
    {
        // prepend the default provider to let users override it easily
        array_unshift($providers, new NumberExpressionLanguageProvider(), new StringExpressionLanguageProvider());

        parent::__construct($parser, $providers);
    }
}